<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockOpnameController extends Controller
{
    /**
     * Display the stock opname Kasir page
     */
    public function index()
    {
        $query = StockOpname::with(['user', 'items.rawMaterial']);
        $totalOpnames = StockOpname::count();
        $thisMonthOpnames = StockOpname::whereMonth('opname_date', now()->month)
                                       ->whereYear('opname_date', now()->year)
                                       ->count();
        $todayOpnames = StockOpname::whereDate('opname_date', today())->count();
        $totalMaterials = RawMaterial::count();
        $stockOpnames = $query->latest('opname_date')->latest()->paginate(10);

        $rawMaterials = RawMaterial::orderBy('name')->get();
        // Calculate system stock for display purpose only
        $systemStocks = RawMaterial::pluck('stock', 'id')->toArray();
        
        $opnames = StockOpname::with('items')->whereDate('opname_date', today())->latest()->get();

        return view('kasir.stock_opname', compact('rawMaterials', 'systemStocks', 'opnames', 'totalOpnames', 'thisMonthOpnames', 'todayOpnames','stockOpnames','totalMaterials', 'rawMaterials'));
    }

    /**
     * Store new stock opname as pending
     */
    public function store(Request $request)
    {
        $request->validate([
            'opname_date' => 'required|date',
            'shift' => 'required|string|max:50|in:Pagi,Siang,Malam',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.qty' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        // Check for duplicate on the same date and shift that isn't rejected
        // $exists = StockOpname::where('opname_date', $request->opname_date)
        //                      ->where('shift', $request->shift)
        //                      ->where('status', '!=', 'rejected')
        //                      ->exists();
        
        // if ($exists) {
        //     return redirect()->back()
        //                    ->withInput()
        //                    ->with('error', 'Stock opname untuk tanggal dan shift ini sudah diajukan!');
        // }

        DB::transaction(function () use ($request) {
            $stockOpname = StockOpname::create([
                'opname_date' => $request->opname_date,
                'shift' => $request->shift,
                'user_id' => Auth::user()->id ?? 1,
                'status' => 'pending',
                'notes' => $request->notes
            ]);

            foreach ($request->items as $item) {
                StockOpnameItem::create([
                    'stock_opname_id' => $stockOpname->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $item['qty'],
                ]);
                // Note: We do NOT update the `RawMaterial` stock here in the Kasir view 
                // because it must be approved first by the back office.
            }
        });

        return redirect()->route('kasir.stock-opnames.index')
                        ->with('success', 'Laporan Stock Opname berhasil disubmit dan menunggu persetujuan!');
    }
}
