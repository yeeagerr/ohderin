<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('rawMaterial');

        // Filter by raw material
        if ($request->filled('raw_material')) {
            $query->where('raw_material_id', $request->raw_material);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('purchase_date', $request->month);
        }

        $purchases = $query->latest('purchase_date')->paginate(10);
        $rawMaterials = RawMaterial::orderBy('name')->get();

        // Stats
        $totalPurchases = Purchase::count();
        $totalSpent = Purchase::sum(\DB::raw('qty * price'));
        $thisMonthSpent = Purchase::whereMonth('purchase_date', now()->month)
                                  ->whereYear('purchase_date', now()->year)
                                  ->sum(\DB::raw('qty * price'));
        $todayPurchases = Purchase::whereDate('purchase_date', today())->count();

        return view('dashboard.purchase', compact(
            'purchases', 
            'rawMaterials', 
            'totalPurchases', 
            'totalSpent', 
            'thisMonthSpent',
            'todayPurchases'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'qty' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        Purchase::create($request->all());

        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil ditambahkan!');
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'qty' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        $purchase->update($request->all());

        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil diperbarui!');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil dihapus!');
    }
}