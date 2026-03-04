<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\Product;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        // Get recipes with eager loading using pagination
        $query = Recipe::with(['product.category', 'items.rawMaterial']);

        // Filter by product
        if ($request->filled('product')) {
            $query->where('product_id', $request->product);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        $recipes = $query->latest()->paginate(10);

        // Get data more efficiently - only what we need
        $recipesDataByProduct = Recipe::pluck('product_id')->unique()->all();

        // Get products without recipe - only what we need for dropdown
        $productsWithoutRecipe = Product::where('is_package', false)
            ->whereNotIn('id', $recipesDataByProduct)
            ->select('id', 'name')
            ->orderBy('name')
            ->limit(1000)
            ->get();

        // Get raw materials - limit to avoid loading too much
        $rawMaterials = RawMaterial::select('id', 'name', 'unit', 'cost')
            ->orderBy('name')
            ->limit(5000)
            ->get();

        $categories = \App\Models\Category::select('id', 'name')
            ->orderBy('name')
            ->get();

        // Calculate stats efficiently
        $totalRecipes = Recipe::count();
        $totalProducts = Product::where('is_package', false)->count();
        $productsWithRecipe = count($recipesDataByProduct);
        $totalIngredients = RecipeItem::count();

        return view('dashboard.recipe', compact(
            'recipes',
            'productsWithoutRecipe',
            'rawMaterials',
            'categories',
            'totalRecipes',
            'totalProducts',
            'productsWithRecipe',
            'totalIngredients'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:recipes,product_id',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.qty' => 'required|numeric|min:0.0001',
        ]);

        DB::transaction(function () use ($request) {
            $recipe = Recipe::create([
                'product_id' => $request->product_id
            ]);

            foreach ($request->items as $item) {
                RecipeItem::create([
                    'recipe_id' => $recipe->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $item['qty'],
                ]);
            }
        });

        return redirect()->route('recipes.index')->with('success', 'Resep berhasil ditambahkan!');
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['product.category', 'items.rawMaterial']);
        return response()->json($recipe);
    }

    public function update(Request $request, Recipe $recipe)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.qty' => 'required|numeric|min:0.0001',
        ]);

        DB::transaction(function () use ($request, $recipe) {
            // Delete existing items
            $recipe->items()->delete();

            // Create new items
            foreach ($request->items as $item) {
                RecipeItem::create([
                    'recipe_id' => $recipe->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $item['qty'],
                ]);
            }
        });

        return redirect()->route('recipes.index')->with('success', 'Resep berhasil diperbarui!');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('recipes.index')->with('success', 'Resep berhasil dihapus!');
    }
}