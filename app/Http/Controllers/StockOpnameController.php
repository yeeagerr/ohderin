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
        $query = StockOpname::with(['user', 'items.rawMaterial.units']);

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
        $rawMaterials = RawMaterial::with('units')->orderBy('name')->get();
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
     * Calculate system stocks based on the stock property on the RawMaterial model
     */
    private function calculateSystemStocks()
    {
        return RawMaterial::pluck('stock', 'id')->toArray();
    }

    /**
     * Get stock summary for a specific date
     */
    public function getStockSummary(Request $request)
    {
        $date = $request->get('date', today()->toDateString());
        
        $systemStocks = $this->calculateSystemStocks();
        $rawMaterials = RawMaterial::with('units')->orderBy('name')->get();
        
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
            'items.*.raw_material_unit_id' => 'nullable|exists:raw_material_units,id',
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
                'status' => 'approved', // Direct from BO is auto approved
            ]);

            foreach ($request->items as $item) {
                $material = RawMaterial::findOrFail($item['raw_material_id']);
                $qty = $material->quantityToBaseUnit($item['qty'], $item['raw_material_unit_id'] ?? null);

                StockOpnameItem::create([
                    'stock_opname_id' => $stockOpname->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $qty,
                ]);
                
                // Opname from backoffice directly overrides the system stock
                RawMaterial::where('id', $item['raw_material_id'])->update(['stock' => $qty]);
            }
        });

        return redirect()->route('stock-opnames.index')
                        ->with('success', 'Stock opname berhasil ditambahkan!');
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load(['user', 'items.rawMaterial.units']);
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
            'items.*.raw_material_unit_id' => 'nullable|exists:raw_material_units,id',
            'items.*.qty' => 'required|numeric|min:0',
        ]);

        // Check for duplicate (excluding current)
        // $exists = StockOpname::where('opname_date', $request->opname_date)
        //                      ->where('shift', $request->shift)
        //                      ->where('id', '!=', $stockOpname->id)
        //                      ->exists();
        
        // if ($exists) {
        //     return redirect()->back()
        //                    ->withInput()
        //                    ->with('error', 'Stock opname untuk tanggal dan shift ini sudah ada!');
        // }

        DB::transaction(function () use ($request, $stockOpname) {
            // First revert previous stock if it was approved
            if ($stockOpname->status === 'approved') {
                // To cleanly revert, we actually can't easily do it without knowing the stock *before* the opname.
                // Since Opnames overwrite completely, editing an approved opname will simply overwrite again.
            }

            $stockOpname->update([
                'opname_date' => $request->opname_date,
                'shift' => $request->shift,
                'status' => 'approved' // If edited from BO, forces approval
            ]);

            // Delete existing items
            $stockOpname->items()->delete();

            // Create new items
            foreach ($request->items as $item) {
                $material = RawMaterial::findOrFail($item['raw_material_id']);
                $qty = $material->quantityToBaseUnit($item['qty'], $item['raw_material_unit_id'] ?? null);

                StockOpnameItem::create([
                    'stock_opname_id' => $stockOpname->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $qty,
                ]);

                // Overrides system stock
                RawMaterial::where('id', $item['raw_material_id'])->update(['stock' => $qty]);
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

    public function approve(StockOpname $stockOpname)
    {
        if ($stockOpname->status === 'approved') {
            return redirect()->back()->with('error', 'Laporan sudah disetujui sebelumnya.');
        }

        DB::transaction(function () use ($stockOpname) {
            $stockOpname->update(['status' => 'approved']);
            
            // Apply the stock overrides
            foreach ($stockOpname->items as $item) {
                RawMaterial::where('id', $item->raw_material_id)->update(['stock' => $item->qty]);
            }
        });

        return redirect()->route('stock-opnames.index')->with('success', 'Stock Opname berhasil disetujui. Stok sistem telah diperbarui.');
    }

    public function reject(StockOpname $stockOpname)
    {
        if ($stockOpname->status === 'approved') {
            return redirect()->back()->with('error', 'Tidak dapat menolak laporan yang sudah disetujui.');
        }

        $stockOpname->update(['status' => 'rejected']);

        return redirect()->route('stock-opnames.index')->with('success', 'Stock Opname telah ditolak.');
    }

    /**
     * Print/Export stock opname report
     */
    public function print(StockOpname $stockOpname)
    {
        $stockOpname->load(['user', 'items.rawMaterial.units']);
        $systemStocks = $this->calculateSystemStocks();
        
        return view('dashboard.stock_opname', compact('stockOpname', 'systemStocks'));
    }
}
