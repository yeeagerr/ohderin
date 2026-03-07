<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use App\Models\RawMaterial;
use App\Models\Purchase;
use App\Models\SaleItem;
use App\Models\RecipeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOpname::with(['user', 'items.rawMaterial']);

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('opname_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('opname_date', '<=', $request->end_date);
        }

        // Filter by shift
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $stockOpnames = $query->latest('opname_date')->latest()->paginate(10);
        $rawMaterials = RawMaterial::orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get();

        // Calculate system stock for each raw material
        $systemStocks = $this->calculateSystemStocks();

        // Stats
        $totalOpnames = StockOpname::count();
        $thisMonthOpnames = StockOpname::whereMonth('opname_date', now()->month)
                                       ->whereYear('opname_date', now()->year)
                                       ->count();
        $todayOpnames = StockOpname::whereDate('opname_date', today())->count();
        $totalMaterials = RawMaterial::count();

        return view('dashboard.stock_opname', compact(
            'stockOpnames',
            'rawMaterials',
            'users',
            'systemStocks',
            'totalOpnames',
            'thisMonthOpnames',
            'todayOpnames',
            'totalMaterials'
        ));
    }

    /**
     * Calculate system stocks based on purchases minus usage from sales
     */
    private function calculateSystemStocks()
    {
        // Get total purchases per raw material
        $purchases = Purchase::selectRaw('raw_material_id, SUM(qty) as total_qty')
                            ->groupBy('raw_material_id')
                            ->pluck('total_qty', 'raw_material_id')
                            ->toArray();

        // Get last opname per material (as starting point if exists)
        $lastOpnames = [];
        $lastOpnameRecord = StockOpname::latest('opname_date')->first();
        
        if ($lastOpnameRecord) {
            $lastOpnames = StockOpnameItem::where('stock_opname_id', $lastOpnameRecord->id)
                                          ->pluck('qty', 'raw_material_id')
                                          ->toArray();
        }

        // Merge: use last opname if exists, otherwise use purchases
        $systemStocks = [];
        $allMaterialIds = array_unique(array_merge(array_keys($purchases), array_keys($lastOpnames)));
        
        foreach ($allMaterialIds as $materialId) {
            // If we have last opname, use that + purchases after opname date
            // For simplicity, just use total purchases for now
            $systemStocks[$materialId] = $purchases[$materialId] ?? 0;
        }

        return $systemStocks;
    }

    /**
     * Get stock summary for a specific date
     */
    public function getStockSummary(Request $request)
    {
        $date = $request->get('date', today()->toDateString());
        
        $systemStocks = $this->calculateSystemStocks();
        $rawMaterials = RawMaterial::orderBy('name')->get();
        
        $summary = $rawMaterials->map(function ($material) use ($systemStocks) {
            return [
                'id' => $material->id,
                'name' => $material->name,
                'unit' => $material->unit,
                'system_stock' => $systemStocks[$material->id] ?? 0,
                'minimal_stock' => $material->minimal_stock,
                'is_low' => ($systemStocks[$material->id] ?? 0) <= $material->minimal_stock,
            ];
        });

        return response()->json($summary);
    }

    public function store(Request $request)
    {
        $request->validate([
            'opname_date' => 'required|date',
            'shift' => 'required|string|max:50|in:Pagi,Siang,Malam',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.qty' => 'required|numeric|min:0',
        ]);

        // Check for duplicate opname on same date and shift
        $exists = StockOpname::where('opname_date', $request->opname_date)
                             ->where('shift', $request->shift)
                             ->exists();
        
        if ($exists) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Stock opname untuk tanggal dan shift ini sudah ada!');
        }

        DB::transaction(function () use ($request) {
            $stockOpname = StockOpname::create([
                'opname_date' => $request->opname_date,
                'shift' => $request->shift,
                'user_id' => Auth::user()->id ?? 1,
            ]);

            foreach ($request->items as $item) {
                StockOpnameItem::create([
                    'stock_opname_id' => $stockOpname->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $item['qty'],
                ]);
            }
        });

        return redirect()->route('stock-opnames.index')
                        ->with('success', 'Stock opname berhasil ditambahkan!');
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load(['user', 'items.rawMaterial']);
        $systemStocks = $this->calculateSystemStocks();
        
        return response()->json([
            'opname' => $stockOpname,
            'system_stocks' => $systemStocks
        ]);
    }

    public function update(Request $request, StockOpname $stockOpname)
    {
        $request->validate([
            'opname_date' => 'required|date',
            'shift' => 'required|string|max:50|in:Pagi,Siang,Malam',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.qty' => 'required|numeric|min:0',
        ]);

        // Check for duplicate (excluding current)
        $exists = StockOpname::where('opname_date', $request->opname_date)
                             ->where('shift', $request->shift)
                             ->where('id', '!=', $stockOpname->id)
                             ->exists();
        
        if ($exists) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Stock opname untuk tanggal dan shift ini sudah ada!');
        }

        DB::transaction(function () use ($request, $stockOpname) {
            $stockOpname->update([
                'opname_date' => $request->opname_date,
                'shift' => $request->shift,
            ]);

            // Delete existing items
            $stockOpname->items()->delete();

            // Create new items
            foreach ($request->items as $item) {
                StockOpnameItem::create([
                    'stock_opname_id' => $stockOpname->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $item['qty'],
                ]);
            }
        });

        return redirect()->route('stock-opnames.index')
                        ->with('success', 'Stock opname berhasil diperbarui!');
    }

    public function destroy(StockOpname $stockOpname)
    {
        $stockOpname->delete(); // Items will cascade delete
        
        return redirect()->route('stock-opnames.index')
                        ->with('success', 'Stock opname berhasil dihapus!');
    }

    /**
     * Print/Export stock opname report
     */
    public function print(StockOpname $stockOpname)
    {
        $stockOpname->load(['user', 'items.rawMaterial']);
        $systemStocks = $this->calculateSystemStocks();
        
        return view('dashboard.stock_opname', compact('stockOpname', 'systemStocks'));
    }
}