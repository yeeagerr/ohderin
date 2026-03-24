<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPackage;
use App\Models\Recipe;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->with('packageItems')->with('recipe');

        // Search by name
        if ($request->filled('search')) {
            $search = $request->input('search', '');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by category
        if ($request->filled('category')) {
            $category = $request->input('category');
            $query->where('category_id', $category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->input('status', '');
            $query->where('is_active', $status === 'active');
        }

        // Filter by type
        if ($request->filled('type')) {
            $type = $request->input('type', '');
            $query->where('is_package', $type === 'package');
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();

        // Load raw materials for recipe section
        $rawMaterials = \App\Models\RawMaterial::select('id', 'name', 'unit', 'cost')
            ->orderBy('name')
            ->limit(5000)
            ->get();

        // Only load non-package products with limited fields
        $allProducts = Product::where('is_package', false)
            ->select('id', 'name')
            ->orderBy('name')
            ->limit(500)
            ->get();

        return view('dashboard.product', compact('products', 'categories', 'rawMaterials', 'allProducts'));
    }

    public function searchProducts(Request $request)
    {
        $search = $request->get('q', '');
        $products = Product::where('is_package', false)
            ->where('name', 'like', '%' . $search . '%')
            ->select('id', 'name')
            ->limit(20)
            ->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'is_package' => 'boolean',
            'is_active' => 'boolean',
            'package_products' => 'nullable|array',
            'package_products.*.product_id' => 'nullable|exists:products,id',
            'package_products.*.quantity' => 'nullable|numeric|min:1',
            // Recipe fields
            'recipe_quantity' => 'nullable|numeric|min:0.0001',
            'recipe_items' => 'nullable|array',
            'recipe_items.*.raw_material_id' => 'nullable|exists:raw_materials,id',
            'recipe_items.*.qty' => 'nullable|numeric|min:0.0001',
        ]);

        DB::transaction(function () use ($request) {
            $imageService = new ImageService();
            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $imageService->processImage($request->file('image'));
            }

            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'image' => $imagePath,
                'is_package' => $request->has('is_package'),
                'is_active' => $request->has('is_active') ? true : true,
            ]);

            // Handle package products
            if ($request->has('is_package') && $request->filled('package_products')) {
                $packageProducts = $request->input('package_products', []);
                $insertData = [];

                foreach ($packageProducts as $packageItem) {
                    if (!empty($packageItem['product_id'])) {
                        $insertData[] = [
                            'package_id' => $product->id,
                            'product_id' => $packageItem['product_id'],
                            'qty' => !empty($packageItem['quantity']) ? $packageItem['quantity'] : 1,
                        ];
                    }
                }

                if (!empty($insertData)) {
                    ProductPackage::insert($insertData);
                }
            }

            // Handle recipe if not a package
            if (!$request->has('is_package')) {
                $recipe = Recipe::create([
                    'product_id' => $product->id,
                    'quantity' => $request->input('recipe_quantity') ?? null,
                ]);

                if ($request->filled('recipe_items')) {
                    $items = [];
                    foreach ($request->input('recipe_items', []) as $item) {
                        if (!empty($item['raw_material_id']) && !empty($item['qty'])) {
                            $items[] = [
                                'recipe_id' => $recipe->id,
                                'raw_material_id' => $item['raw_material_id'],
                                'qty' => $item['qty'],
                            ];
                        }
                    }
                    if (!empty($items)) {
                        \App\Models\RecipeItem::insert($items);
                    }
                }
            }
        });

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'is_package' => 'boolean',
            'is_active' => 'boolean',
            'package_products' => 'nullable|array',
            'package_products.*.product_id' => 'nullable|exists:products,id',
            'package_products.*.quantity' => 'nullable|numeric|min:1',
            // Recipe fields
            'recipe_quantity' => 'nullable|numeric|min:0.0001',
            'recipe_items' => 'nullable|array',
            'recipe_items.*.raw_material_id' => 'nullable|exists:raw_materials,id',
            'recipe_items.*.qty' => 'nullable|numeric|min:0.0001',
        ]);

        DB::transaction(function () use ($request, $product) {
            $imageService = new ImageService();
            $updateData = [
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'is_package' => $request->has('is_package'),
                'is_active' => $request->has('is_active'),
            ];

            // Handle image update
            if ($request->hasFile('image')) {
                if ($product->image) {
                    $imageService->deleteImage($product->image);
                }
                $updateData['image'] = $imageService->processImage($request->file('image'));
            }

            $product->update($updateData);

            // Handle package products
            if ($request->has('is_package')) {
                ProductPackage::where('package_id', $product->id)->delete();

                if ($request->filled('package_products')) {
                    $packageProducts = $request->input('package_products', []);
                    $insertData = [];

                    foreach ($packageProducts as $packageItem) {
                        if (!empty($packageItem['product_id'])) {
                            $insertData[] = [
                                'package_id' => $product->id,
                                'product_id' => $packageItem['product_id'],
                                'qty' => !empty($packageItem['quantity']) ? $packageItem['quantity'] : 1,
                            ];
                        }
                    }

                    if (!empty($insertData)) {
                        ProductPackage::insert($insertData);
                    }
                }
            } else {
                ProductPackage::where('package_id', $product->id)->delete();

                // Handle recipe update for non-package products
                $recipe = $product->recipe;
                if (!$recipe) {
                    $recipe = Recipe::create([
                        'product_id' => $product->id,
                        'quantity' => $request->input('recipe_quantity') ?? null,
                    ]);
                } else {
                    $recipe->update(['quantity' => $request->input('recipe_quantity') ?? null]);
                }

                // Update recipe items
                $recipe->items()->delete();
                if ($request->filled('recipe_items')) {
                    $items = [];
                    foreach ($request->input('recipe_items', []) as $item) {
                        if (!empty($item['raw_material_id']) && !empty($item['qty'])) {
                            $items[] = [
                                'recipe_id' => $recipe->id,
                                'raw_material_id' => $item['raw_material_id'],
                                'qty' => $item['qty'],
                            ];
                        }
                    }
                    if (!empty($items)) {
                        \App\Models\RecipeItem::insert($items);
                    }
                }
            }
        });

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            $imageService = new ImageService();

            // Delete image
            if ($product->image) {
                $imageService->deleteImage($product->image);
            }

            // Delete package relationships
            ProductPackage::where('package_id', $product->id)
                ->orWhere('product_id', $product->id)
                ->delete();

            // Delete recipe
            Recipe::where('product_id', $product->id)->delete();

            $product->delete();
        });

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return redirect()->route('products.index')->with('success', 'Status produk berhasil diubah!');
    }

    /**
     * Get product data for modal
     */
    public function getProduct(Product $product)
    {
        $product->load(['category', 'recipe.items.rawMaterial', 'packageItems.product']);
        return response()->json($product);
    }
}