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

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        // Filter by unit
        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('stock', '<=', 'minimal_stock');
            } elseif ($request->stock_status === 'safe') {
                $query->whereColumn('stock', '>', 'minimal_stock');
            }
        }

        match ($request->input('sort')) {
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'cost_asc' => $query->orderBy('cost'),
            'cost_desc' => $query->orderByDesc('cost'),
            'stock_asc' => $query->orderBy('stock'),
            'stock_desc' => $query->orderByDesc('stock'),
            default => $query->latest(),
        };

        $perPage = $request->input('per_page', 10);
        if ($perPage === 'all') {
            $perPage = max((clone $query)->count(), 1);
        } else {
            $perPage = in_array((int) $perPage, [10, 25, 50, 100], true) ? (int) $perPage : 10;
        }

        $rawMaterials = $query->paginate($perPage)->withQueryString();

        // Get unique units for filter
        $units = RawMaterial::whereNotNull('unit')->distinct()->orderBy('unit')->pluck('unit');

        $stats = [
            'total_materials' => RawMaterial::count(),
            'total_cost' => (float) RawMaterial::sum('cost'),
            'unit_count' => RawMaterial::distinct('unit')->count('unit'),
            'average_cost' => (float) RawMaterial::avg('cost'),
        ];

        return view('dashboard.raw_material', compact('rawMaterials', 'units', 'stats'));
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
