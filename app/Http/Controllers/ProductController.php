<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPackage;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('is_package', $request->type === 'package');
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();
        // Only load non-package products with limited fields
        $allProducts = Product::where('is_package', false)
            ->select('id', 'name')
            ->orderBy('name')
            ->limit(500)
            ->get();

        return view('dashboard.product', compact('products', 'categories', 'allProducts'));
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
            'is_package' => 'boolean',
            'is_active' => 'boolean',
            'package_product_id' => 'nullable|array',
            'package_product_id.*' => 'nullable|exists:products,id',
            'package_qty' => 'nullable|array',
            'package_qty.*' => 'nullable|numeric|min:1',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'is_package' => $request->has('is_package'),
            'is_active' => $request->has('is_active') ? true : true,
        ]);

        // Handle package products
        if ($request->has('is_package') && $request->filled('package_product_id')) {
            $packageProducts = $request->input('package_product_id', []);
            $packageQties = $request->input('package_qty', []);
            $insertData = [];
            
            foreach ($packageProducts as $index => $productId) {
                if (!empty($productId)) {
                    $insertData[] = [
                        'package_id' => $product->id,
                        'product_id' => $productId,
                        'qty' => !empty($packageQties[$index]) ? $packageQties[$index] : 1,
                    ];
                }
            }
            
            if (!empty($insertData)) {
                ProductPackage::insert($insertData);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'is_package' => 'boolean',
            'is_active' => 'boolean',
            // 'package_product_id' => 'nullable|array',
            // 'package_product_id.*' => 'exists:products,id',
            // 'package_qty' => 'nullable|array',
            // 'package_qty.*' => 'required_with:package_product_id.*|numeric|min:1',
        ]);

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'is_package' => $request->has('is_package'),
            'is_active' => $request->has('is_active'),
        ]);

        // Delete existing package products if switching to non-package or updating items
        if ($request->has('is_package')) {
            // Delete old package items
            ProductPackage::where('package_id', $product->id)->delete();
            
            // Add new package items
            if ($request->filled('package_product_id')) {
                $packageProducts = $request->input('package_product_id', []);
                $packageQties = $request->input('package_qty', []);
                $insertData = [];
                
                foreach ($packageProducts as $index => $productId) {
                    if (!empty($productId)) {
                        $insertData[] = [
                            'package_id' => $product->id,
                            'product_id' => $productId,
                            'qty' => !empty($packageQties[$index]) ? $packageQties[$index] : 1,
                        ];
                    }
                }
                
                if (!empty($insertData)) {
                    ProductPackage::insert($insertData);
                }
            }
        } else {
            // If not a package, delete all package items
            ProductPackage::where('package_id', $product->id)->delete();
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        ProductPackage::where('package_id', $product->id)
            ->orWhere('product_id', $product->id)
            ->delete();
        
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return redirect()->route('products.index')->with('success', 'Status produk berhasil diubah!');
    }
}