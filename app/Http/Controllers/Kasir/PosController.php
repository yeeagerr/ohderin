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

    public function getDrafts()
    {
        $draftsPaginated = Sale::with('items.product')
            ->where('status', 'draft')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $drafts = $draftsPaginated->map(function ($draft) {
            return [
                'id' => $draft->id,
                'order_number' => $draft->order_number,
                'total' => $draft->total,
                'created_at' => $draft->created_at->format('d M Y H:i'),
                'items_count' => $draft->items->count(),
                'items_summary' => $draft->items->pluck('product.name')->join(', '),
            ];
        });

        return response()->json([
            'drafts' => $drafts->all(),
            'hasMore' => $draftsPaginated->hasMorePages(),
            'currentPage' => $draftsPaginated->currentPage(),
            'lastPage' => $draftsPaginated->lastPage(),
        ]);
    }

    public function resumeDraft($id)
    {
        $draft = Sale::where('id', $id)->where('status', 'draft')->first();

        if (!$draft) {
            return response()->json([
                'success' => false,
                'message' => 'Draft tidak ditemukan',
            ], 404);
        }

        $items = $draft->items()->with('product')->get()->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'price' => $item->price,
                'qty' => $item->qty,
                'category' => $item->product->category,
                'note' => $item->note,
            ];
        });

        return response()->json([
            'success' => true,
            'draft_id' => $draft->id,
            'order_number' => $draft->order_number,
            'items' => $items,
            'order_type' => $draft->order_type,
            'payment_method' => $draft->payment_method,
        ]);
    }

    public function deleteDraft($id)
    {
        $draft = Sale::where('id', $id)->where('status', 'draft')->first();

        if (!$draft) {
            return response()->json([
                'success' => false,
                'message' => 'Draft tidak ditemukan',
            ], 404);
        }

        $draft->delete();

        return response()->json([
            'success' => true,
            'message' => 'Draft berhasil dihapus',
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

    public function holdOrder(Request $request)
    {
        try {
            DB::beginTransaction();

            // Check if updating existing draft
            $draftId = $request->input('draft_id');

            if ($draftId) {
                // Update existing draft
                $sale = Sale::findOrFail($draftId);

                // Update sale details (keep as draft)
                $sale->update([
                    'order_type' => $request->order_type,
                    'total' => $request->total,
                    'payment_method' => $request->payment_method || 'drafted',
                ]);

                // Delete old sale items
                SaleItem::where('sale_id', $sale->id)->delete();
            } else {
                // Create new draft
                // Generate order number
                $lastSale = Sale::latest()->first();
                $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(($lastSale ? $lastSale->id + 1 : 1), 5, '0', STR_PAD_LEFT);

                // Create sale
                $sale = Sale::create([
                    'order_number' => $orderNumber,
                    'order_type' => $request->order_type,
                    'total' => $request->total,
                    'payment_method' => $request->payment_method || 'drafted',
                    'user_id' => Auth::user()->id ?? 1,
                    'status' => 'draft',
                ]);
            }

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
                'message' => $draftId ? 'Draft berhasil diupdate!' : 'Transaksi disimpan sebagai draft!',
                'order_number' => $sale->order_number,
                'sale_id' => $sale->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process checkout and create or update sale
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
            'draft_id' => 'nullable|exists:sales,id', // Optional draft_id for updating existing draft
        ]);

        try {
            DB::beginTransaction();

            // Check if updating existing draft
            $draftId = $request->input('draft_id');

            if ($draftId) {
                // Update existing draft to completed
                $sale = Sale::findOrFail($draftId);

                // Update sale details and mark as completed
                $sale->update([
                    'order_type' => $request->order_type,
                    'total' => $request->total,
                    'payment_method' => $request->payment_method,
                    'status' => 'completed', // Change status from draft to completed
                ]);

                // Delete old sale items
                SaleItem::where('sale_id', $sale->id)->delete();
            } else {
                // Create new sale
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
                    'status' => 'completed', // New transactions are completed by default
                ]);
            }

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
                'message' => $draftId ? 'Draft berhasil diselesaikan!' : 'Transaksi berhasil!',
                'order_number' => $sale->order_number,
                'sale_id' => $sale->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
