<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('rawMaterial.units');

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
        $rawMaterials = RawMaterial::with('units')->orderBy('name')->get();

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
            'raw_material_unit_id' => 'nullable|exists:raw_material_units,id',
            'qty' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            $material = RawMaterial::findOrFail($request->raw_material_id);
            $unitId = $request->input('raw_material_unit_id');
            $qty = $material->quantityToBaseUnit($request->qty, $unitId);
            $price = $material->priceToBaseUnit($request->price, $unitId);

            $purchase = Purchase::create([
                'raw_material_id' => $request->raw_material_id,
                'qty' => $qty,
                'price' => $price,
                'purchase_date' => $request->purchase_date,
            ]);
            $purchase->rawMaterial->increment('stock', $purchase->qty);
        });

        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil ditambahkan!');
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'raw_material_unit_id' => 'nullable|exists:raw_material_units,id',
            'qty' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $purchase) {
            $oldQty = $purchase->qty;
            $material = RawMaterial::findOrFail($request->raw_material_id);
            $unitId = $request->input('raw_material_unit_id');
            $qty = $material->quantityToBaseUnit($request->qty, $unitId);
            $price = $material->priceToBaseUnit($request->price, $unitId);
            
            $purchase->fill([
                'raw_material_id' => $request->raw_material_id,
                'qty' => $qty,
                'price' => $price,
                'purchase_date' => $request->purchase_date,
            ]);
            
            if ($purchase->isDirty('raw_material_id')) {
                // If material changed, revert old completely and increment new completely
                $oldMaterialId = $purchase->getOriginal('raw_material_id');
                $oldMaterial = RawMaterial::find($oldMaterialId);
                if ($oldMaterial) {
                    $oldMaterial->decrement('stock', $oldQty);
                }
                
                $purchase->save();
                $purchase->rawMaterial->increment('stock', $purchase->qty);
            } else {
                // Same material, just adjust difference
                $purchase->save();
                $difference = $purchase->qty - $oldQty;
                if ($difference != 0) {
                    $purchase->rawMaterial->increment('stock', $difference);
                }
            }
        });

        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil diperbarui!');
    }

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            $purchase->rawMaterial->decrement('stock', $purchase->qty);
            $purchase->delete();
        });
        
        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil dihapus!');
    }
}
