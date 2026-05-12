<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\Sale;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display Orders page
     */
    public function index()
    {
        $registers = Register::orderBy('name')->get();
        return view('kasir.orders', compact('registers'));
    }

    /**
     * AJAX: Get orders with search, filter, pagination
     */
    public function getOrders(Request $request)
    {
        $query = Sale::with(['items.product', 'cashier', 'table', 'register']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by order number
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('register_id') && $request->register_id !== 'all') {
            $query->where('register_id', $request->register_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        $data = $orders->through(function ($sale) {
            return [
                'id' => $sale->id,
                'order_number' => $sale->order_number,
                'order_type' => $sale->order_type,
                'total' => $sale->total,
                'payment_method' => $sale->payment_method,
                'status' => $sale->status,
                'table_name' => $sale->table->name ?? null,
                'split_bill_group' => $sale->split_bill_group,
                'items_count' => $sale->items->count(),
                'cashier_name' => $sale->cashier->name ?? 'Unknown',
                'register_name' => $sale->register->name ?? '-',
                'created_at' => $sale->created_at->format('d M Y'),
                'created_time' => $sale->created_at->format('H:i'),
            ];
        });

        // Counts for tabs
        $counts = [
            'all' => Sale::count(),
            'completed' => Sale::completed()->count(),
            'draft' => Sale::draft()->count(),
            'refunded' => Sale::where('status', 'refunded')->count(),
        ];

        return response()->json([
            'orders' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
            'counts' => $counts,
        ]);
    }

    /**
     * AJAX: Get order detail
     */
    public function getOrderDetail($id)
    {
        $sale = Sale::with(['items.product.modifiers', 'items.modifiers.modifier', 'cashier', 'register', 'table'])->findOrFail($id);

        return response()->json([
            'id' => $sale->id,
            'order_number' => $sale->order_number,
            'order_type' => $sale->order_type,
            'total' => $sale->total,
            'payment_method' => $sale->payment_method,
            'status' => $sale->status,
            'table_name' => $sale->table->name ?? null,
            'split_bill_group' => $sale->split_bill_group,
            'cashier_name' => $sale->cashier->name ?? 'Unknown',
            'register_name' => $sale->register->name ?? '-',
            'created_at' => $sale->created_at->format('d M Y'),
            'created_time' => $sale->created_at->format('H:i'),
            'refund_amount' => $sale->refund_amount,
            'refund_reason' => $sale->refund_reason,
            'refunded_at' => $sale->refunded_at ? $sale->refunded_at->format('d M Y H:i') : null,
            'items' => $sale->items->map(function ($item) {
                $modifierAdjustment = $item->modifiers->sum(function ($modifier) {
                    return (float) ($modifier->modifier->price_adjustment ?? 0) * (int) ($modifier->quantity ?? 1);
                });

                return [
                    'id' => $item->id,
                    'name' => $item->product->name ?? 'Deleted Product',
                    'product_id' => $item->product->id ?? '-',
                    'category' => $item->product->category ?? '-',
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'subtotal' => ($item->price + $modifierAdjustment) * $item->qty,
                    'note' => $item->note,
                    'allowed_modifiers' => $item->product->modifiers->where('is_active', true)->values(),
                    'modifiers' => $item->modifiers->map(function ($modifier) {
                        return [
                            'modifier_id' => $modifier->modifier_id,
                            'name' => $modifier->modifier->name ?? 'Modifier',
                            'price_adjustment' => (float) ($modifier->modifier->price_adjustment ?? 0),
                            'quantity' => $modifier->quantity ?? 1,
                        ];
                    }),
                ];
            }),
        ]);
    }

    /**
     * AJAX: Process refund for a completed order
     */
    public function refund(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);

        // Validate only completed orders can be refunded
        if ($sale->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya completed orders yang bisa di-refund'
            ], 422);
        }

        // Validate refund amount
        $refundAmount = (float) $request->input('refund_amount');
        if ($refundAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Refund amount harus lebih besar dari 0'
            ], 422);
        }

        if ($refundAmount > (float) $sale->total) {
            return response()->json([
                'success' => false,
                'message' => 'Refund amount tidak boleh lebih besar dari total order'
            ], 422);
        }

        // Validate reason is provided
        $reason = trim($request->input('refund_reason', ''));
        if (empty($reason)) {
            return response()->json([
                'success' => false,
                'message' => 'Alasan refund wajib diisi'
            ], 422);
        }

        // Update sale with refund data and change status to refunded
        $sale->update([
            'status' => 'refunded',
            'refund_amount' => $refundAmount,
            'refund_reason' => $reason,
            'refunded_at' => now(),
            'refunded_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Refund berhasil diproses',
            'refund_amount' => $refundAmount,
            'refunded_at' => $sale->refunded_at->format('d M Y H:i'),
        ]);
    }
}
