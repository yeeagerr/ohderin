<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;

class TransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    // $cashier = User::whereHas('role', function ($q) {
    //     $q->where('name', 'Cashier');
    // })->first();

    // if (!$cashier) {
    //     $cashier = User::first(); // Fallback if no cashier
    // }

    // // Get some random products
    // $products = Product::where('is_active', true)->inRandomOrder()->limit(50)->get();

    // if ($products->isEmpty()) {
    //     return;
    // }

    // for ($i = 0; $i < 100; $i++) {
    //     $total = 0;
    //     $items = [];

    //     // Randomly select 1-5 products for this order
    //     $orderProducts = $products->random(rand(1, 5));

    //     foreach ($orderProducts as $product) {
    //         $qty = rand(1, 3);
    //         $price = $product->price;
    //         $subtotal = $qty * $price;
    //         $total += $subtotal;

    //         $items[] = [
    //             'product_id' => $product->id,
    //             'qty' => $qty,
    //             'price' => $price,
    //             'note' => null,
    //         ];
    //     }

    //     $sale = Sale::create([
    //         'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
    //         'order_type' => rand(0, 1) ? 'dine_in' : 'take_away',
    //         'total' => $total,
    //         'payment_method' => ['cash', 'qris', 'debit', 'credit'][rand(0, 3)],
    //         'user_id' => $cashier->id,
    //     ]);

    //     foreach ($items as $item) {
    //         SaleItem::create([
    //             'sale_id' => $sale->id,
    //             'product_id' => $item['product_id'],
    //             'qty' => $item['qty'],
    //             'price' => $item['price'],
    //             'note' => $item['note'],
    //         ]);
    //     }
    // }
    }
}
