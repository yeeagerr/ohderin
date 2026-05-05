<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RawMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = RawMaterial::with('units');

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

    private function syncUnits(RawMaterial $rawMaterial, array $units = [])
    {
        $unitRows = collect($units)
            ->filter(fn ($unit) => !empty($unit['name']))
            ->map(fn ($unit) => [
                'name' => trim($unit['name']),
                'ratio' => (float) ($unit['ratio'] ?? 1),
            ])
            ->filter(fn ($unit) => $unit['ratio'] > 0)
            ->keyBy(fn ($unit) => strtolower($unit['name']));

        $unitRows->put(strtolower($rawMaterial->unit), [
            'name' => $rawMaterial->unit,
            'ratio' => 1,
        ]);

        $rawMaterial->units()->delete();
        $rawMaterial->units()->createMany($unitRows->values()->all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'unit' => 'required|string|max:20',
            'minimal_stock' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'units' => 'nullable|array',
            'units.*.name' => 'nullable|string|max:50',
            'units.*.ratio' => 'nullable|numeric|min:0.0001',
        ]);

        DB::transaction(function () use ($request) {
            $rawMaterial = RawMaterial::create($request->only(['name', 'unit', 'minimal_stock', 'cost']));
            $this->syncUnits($rawMaterial, $request->input('units', []));
        });

        return redirect()->route('raw-materials.index')->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'unit' => 'required|string|max:20',
            'minimal_stock' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'units' => 'nullable|array',
            'units.*.name' => 'nullable|string|max:50',
            'units.*.ratio' => 'nullable|numeric|min:0.0001',
        ]);

        DB::transaction(function () use ($request, $rawMaterial) {
            $rawMaterial->update($request->only(['name', 'unit', 'minimal_stock', 'cost']));
            $this->syncUnits($rawMaterial, $request->input('units', []));
        });

        return redirect()->route('raw-materials.index')->with('success', 'Bahan baku berhasil diperbarui!');
    }

    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();
        return redirect()->route('raw-materials.index')->with('success', 'Bahan baku berhasil dihapus!');
    }
}
