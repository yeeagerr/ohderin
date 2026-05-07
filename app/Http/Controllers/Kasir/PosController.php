<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Modifier;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleItemModifier;
use App\Models\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Apply shared visibility rules for products shown in POS.
     *
     * Rules:
     * - Regular product: must have a recipe with quantity and at least one recipe item.
     * - Combo/package: must have package items, and every component product must exist,
     *   be active, not a package, and have a valid recipe.
     */
    private function applyPosVisibilityConstraints(Builder $query): Builder
    {
        return $query->where(function (Builder $productQuery) {
            $productQuery
                ->where(function (Builder $regularProductQuery) {
                    $regularProductQuery
                        ->where('is_package', false)
                        ->whereHas('recipe', function (Builder $recipeQuery) {
                            $recipeQuery
                                ->whereNotNull('quantity')
                                ->whereHas('items');
                        });
                })
                ->orWhere(function (Builder $packageProductQuery) {
                    $packageProductQuery
                        ->where('is_package', true)
                        ->whereHas('packageItems')
                        ->whereDoesntHave('packageItems', function (Builder $packageItemQuery) {
                            $packageItemQuery->where(function (Builder $invalidPackageItemQuery) {
                                $invalidPackageItemQuery
                                    ->whereDoesntHave('product')
                                    ->orWhereHas('product', function (Builder $componentProductQuery) {
                                        $componentProductQuery
                                            ->where('is_active', false)
                                            ->orWhere('is_package', true)
                                            ->orWhereDoesntHave('recipe', function (Builder $componentRecipeQuery) {
                                                $componentRecipeQuery
                                                    ->whereNotNull('quantity')
                                                    ->whereHas('items');
                                            });
                                    });
                            });
                        });
                });
        });
    }

    /**
     * Generate unique order number based on today's date
     */
    private function generateOrderNumber()
    {
        $today = date('Ymd');
        $prefix = 'ORD-' . $today . '-';

        $todayCount = Sale::where('order_number', 'like', $prefix . '%')
            ->count();

        $nextNumber = str_pad($todayCount + 1, 5, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }

    /**
     * Helper to deduct raw materials based on product recipe/package
     */
    private function deductStockForProduct($productId, $qty)
    {
        $product = Product::with(['recipe.items.rawMaterial', 'packageItems.product'])->find($productId);
        if (!$product)
            return;

        if ($product->is_package) {
            foreach ($product->packageItems as $packageItem) {
                $this->deductStockForProduct($packageItem->product_id, $packageItem->qty * $qty);
            }
        } else {
            if ($product->recipe) {
                foreach ($product->recipe->items as $recipeItem) {
                    $rawMaterial = $recipeItem->rawMaterial;
                    if ($rawMaterial) {
                        $rawMaterial->decrement('stock', $recipeItem->qty * $qty);
                    }
                }
            }
        }
    }

    private function saveSaleItems($sale, $items, $deductStock = false)
    {
        $productModifierIds = Product::with('modifiers:id')->whereIn('id', collect($items)->pluck('product_id'))->get()
            ->mapWithKeys(function ($product) {
                return [$product->id => $product->modifiers->pluck('id')->all()];
            });

        foreach ($items as $item) {
            $saleItem = SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'note' => $item['note'] ?? null,
            ]);

            if (!empty($item['modifiers']) && is_array($item['modifiers'])) {
                $allowedModifierIds = $productModifierIds->get($item['product_id'], []);
                foreach ($item['modifiers'] as $modifierData) {
                    $modifierId = $modifierData['modifier_id'] ?? null;
                    if ($modifierId && in_array((int) $modifierId, $allowedModifierIds)) {
                        SaleItemModifier::create([
                            'sale_item_id' => $saleItem->id,
                            'modifier_id' => $modifierId,
                            'quantity' => $modifierData['quantity'] ?? 1,
                        ]);
                    }
                }
            }

            if ($deductStock) {
                $this->deductStockForProduct($item['product_id'], $item['qty']);
            }
        }
    }

    private function normalizePaymentMethod(?string $paymentMethod, string $fallback = 'cash'): string
    {
        return in_array($paymentMethod, ['cash', 'qris', 'debit', 'credit'], true)
            ? $paymentMethod
            : $fallback;
    }

    /**
     * Display POS page with initial products and categories
     */
    public function index()
    {
        $products = Product::with([
            'category',
            'modifiers' => function ($query) {
                $query->where('is_active', true)->orderBy('name');
            }
        ])->where('is_active', true);

        $this->applyPosVisibilityConstraints($products);
        $products = $products->orderBy('name')->paginate(20);

        $categories = Product::where('is_active', true)
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name')
            ->distinct()
            ->orderBy('categories.name');
        $this->applyPosVisibilityConstraints($categories);
        $categories = $categories->get();

        $tables = Table::orderBy('name')->get();
        $modifiers = Modifier::where('is_active', true)->orderBy('name')->get();
        $productModifiers = Product::with([
            'modifiers' => function ($query) {
                $query->where('is_active', true)->orderBy('name');
            }
        ])
            ->where('is_active', true);
        $this->applyPosVisibilityConstraints($productModifiers);
        $productModifiers = $productModifiers
            ->get()
            ->mapWithKeys(function ($product) {
                return [$product->id => $product->modifiers->values()];
            });

        return view('kasir.pos', compact('products', 'categories', 'tables', 'modifiers', 'productModifiers'));
    }

    /**
     * AJAX endpoint for products with search and category filter
     */
    public function getProducts(Request $request)
    {
        $query = Product::with([
            'category',
            'modifiers' => function ($query) {
                $query->where('is_active', true)->orderBy('name');
            }
        ])
            ->where('is_active', true);
        $this->applyPosVisibilityConstraints($query);

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
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
        $draftsPaginated = Sale::with(['items.product', 'table'])
            ->where('status', 'draft')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $drafts = $draftsPaginated->map(function ($draft) {
            return [
                'id' => $draft->id,
                'order_number' => $draft->order_number,
                'total' => $draft->total,
                'table_id' => $draft->table_id,
                'table_name' => $draft->table->name ?? null,
                'split_bill_group' => $draft->split_bill_group,
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

        $items = $draft->items()->with([
            'product.category',
            'product.modifiers' => function ($query) {
                $query->where('is_active', true)->orderBy('name');
            },
            'modifiers.modifier'
        ])->get()->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'price' => (float) $item->price,
                'qty' => (int) $item->qty,
                'category' => $item->product->category,
                'note' => $item->note,
                'allowed_modifiers' => $item->product->modifiers->values(),
                'modifiers' => $item->modifiers->map(function ($modifier) {
                    return [
                        'modifier_id' => $modifier->modifier_id,
                        'name' => $modifier->modifier->name ?? 'Modifier',
                        'price_adjustment' => (float) ($modifier->modifier->price_adjustment ?? 0),
                        'quantity' => (int) ($modifier->quantity ?? 1),
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'success' => true,
            'draft_id' => $draft->id,
            'order_number' => $draft->order_number,
            'items' => $items,
            'order_type' => $draft->order_type,
            'payment_method' => $draft->payment_method,
            'table_id' => $draft->table_id,
            'split_bill_group' => $draft->split_bill_group,
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
        $categories = Product::where('products.is_active', true)
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name')
            ->distinct()
            ->orderBy('categories.name');
        $this->applyPosVisibilityConstraints($categories);
        $categories = $categories->get();

        return response()->json($categories);
    }

    public function holdOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.note' => 'nullable|string|max:500',
            'items.*.modifiers' => 'nullable|array',
            'items.*.modifiers.*.modifier_id' => 'nullable|exists:modifiers,id',
            'items.*.modifiers.*.quantity' => 'nullable|integer|min:1',
            'order_type' => 'required|in:dine_in,take_away',
            'payment_method' => 'nullable|in:cash,qris,debit,credit,drafted',
            'total' => 'required|numeric|min:0',
            'draft_id' => 'nullable|exists:sales,id',
            'table_id' => 'nullable|exists:tables,id',
            'split_bill_group' => 'nullable|string|max:100',
        ]);

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
                    'payment_method' => $this->normalizePaymentMethod($request->payment_method),
                    'table_id' => $request->table_id,
                    'split_bill_group' => $request->split_bill_group,
                ]);

                // Delete old sale items
                SaleItem::where('sale_id', $sale->id)->delete();
            } else {
                // Create new draft
                // Generate order number
                $orderNumber = $this->generateOrderNumber();

                // Create sale
                $sale = Sale::create([
                    'order_number' => $orderNumber,
                    'order_type' => $request->order_type,
                    'total' => $request->total,
                    'payment_method' => $this->normalizePaymentMethod($request->payment_method),
                    'table_id' => $request->table_id,
                    'split_bill_group' => $request->split_bill_group,
                    'user_id' => Auth::user()->id ?? 1,
                    'status' => 'draft',
                ]);
            }

            // Create sale items
            $this->saveSaleItems($sale, $request->items, false);

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
            'items.*.note' => 'nullable|string|max:500',
            'items.*.modifiers' => 'nullable|array',
            'items.*.modifiers.*.modifier_id' => 'nullable|exists:modifiers,id',
            'items.*.modifiers.*.quantity' => 'nullable|integer|min:1',
            'order_type' => 'required|in:dine_in,take_away',
            'payment_method' => 'required|in:cash,qris,debit,credit',
            'total' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'change_amount' => 'required|numeric',
            'draft_id' => 'nullable|exists:sales,id',
            'table_id' => 'nullable|exists:tables,id',
            'split_bill_group' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $draftId = $request->input('draft_id');

            if ($draftId) {
                // Update existing draft to completed
                $sale = Sale::findOrFail($draftId);

                $sale->update([
                    'order_type' => $request->order_type,
                    'total' => $request->total,
                    'paid_amount' => $request->paid_amount,
                    'change_amount' => $request->change_amount,
                    'payment_method' => $request->payment_method,
                    'table_id' => $request->table_id,
                    'split_bill_group' => $request->split_bill_group,
                    'status' => "completed",
                ]);

                SaleItem::where('sale_id', $sale->id)->delete();
            } else {
                $orderNumber = $this->generateOrderNumber();

                $sale = Sale::create([
                    'order_number' => $orderNumber,
                    'order_type' => $request->order_type,
                    'total' => $request->total,
                    'paid_amount' => $request->paid_amount,
                    'change_amount' => $request->change_amount,
                    'payment_method' => $request->payment_method,
                    'table_id' => $request->table_id,
                    'split_bill_group' => $request->split_bill_group,
                    'user_id' => Auth::user()->id ?? 1,
                    'status' => "completed",
                ]);
            }

            $this->saveSaleItems($sale, $request->items, true);

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
