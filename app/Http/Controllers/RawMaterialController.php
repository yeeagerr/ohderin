<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = RawMaterial::query();

        // Filter by unit
        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('minimal_stock', '>', 'minimal_stock'); // Untuk demo, nanti bisa diganti dengan actual stock
            }
        }

        $rawMaterials = $query->latest()->paginate(10);

        // Get unique units for filter
        $units = RawMaterial::distinct()->pluck('unit');

        return view('dashboard.raw_material', compact('rawMaterials', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'unit' => 'required|string|max:20',
            'minimal_stock' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
        ]);

        RawMaterial::create($request->all());

        return redirect()->route('raw-materials.index')->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'unit' => 'required|string|max:20',
            'minimal_stock' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
        ]);

        $rawMaterial->update($request->all());

        return redirect()->route('raw-materials.index')->with('success', 'Bahan baku berhasil diperbarui!');
    }

    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();
        return redirect()->route('raw-materials.index')->with('success', 'Bahan baku berhasil dihapus!');
    }
}