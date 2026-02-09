<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display POS page with initial products and categories
     */
    public function index()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->paginate(20);

        $categories = Product::where('is_active', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('kasir.pos', compact('products', 'categories'));
    }

    /**
     * AJAX endpoint for products with search and category filter
     */
    public function getProducts(Request $request)
    {
        $query = Product::where('is_active', true);

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('name')->paginate(20);

        return response()->json([
            'products' => $products->items(),
            'hasMore' => $products->hasMorePages(),
            'currentPage' => $products->currentPage(),
            'lastPage' => $products->lastPage(),
        ]);
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        $categories = Product::where('is_active', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return response()->json($categories);
    }

    /**
     * Process checkout and create sale
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'order_type' => 'required|in:dine_in,take_away',
            'payment_method' => 'required|in:cash,qris,debit,credit',
            'total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generate order number
            $lastSale = Sale::latest()->first();
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(($lastSale ? $lastSale->id + 1 : 1), 5, '0', STR_PAD_LEFT);

            // Create sale
            $sale = Sale::create([
                'order_number' => $orderNumber,
                'order_type' => $request->order_type,
                'total' => $request->total,
                'payment_method' => $request->payment_method,
                'user_id' => Auth::user()->id ?? 1, // Default to user 1 if not authenticated
            ]);

            // Create sale items
            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'note' => $item['note'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'order_number' => $orderNumber,
                'sale_id' => $sale->id,
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
