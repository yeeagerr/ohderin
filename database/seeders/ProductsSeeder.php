<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $categoryMap = DB::table('categories')->pluck('id', 'name')->toArray();
        $now = Carbon::now();

        $products = [

            // FOOD
            ['name' => 'Nasi Goreng Spesial', 'category' => 'Food', 'price' => 20000],
            ['name' => 'Ayam Geprek Sambal Iblis', 'category' => 'Food', 'price' => 18000],
            ['name' => 'Ayam Bakar Madu', 'category' => 'Food', 'price' => 22000],
            ['name' => 'Soto Ayam Kampung', 'category' => 'Food', 'price' => 17000],
            ['name' => 'Bakso Urat Jumbo', 'category' => 'Food', 'price' => 19000],
            ['name' => 'Mie Goreng Jawa', 'category' => 'Food', 'price' => 16000],
            ['name' => 'Nasi Ayam Teriyaki', 'category' => 'Food', 'price' => 21000],

            // FUN / CUTE FOOD
            ['name' => 'Nasi Goreng UFO 👽', 'category' => 'Food', 'price' => 23000],
            ['name' => 'Ayam Geprek Sultan 👑', 'category' => 'Food', 'price' => 25000],
            ['name' => 'Bakso Monster 🧌', 'category' => 'Food', 'price' => 24000],
            ['name' => 'Mie Goreng Ninja 🥷', 'category' => 'Food', 'price' => 20000],
            ['name' => 'Nasi Ayam Bahagia 😋', 'category' => 'Food', 'price' => 21000],

            // DRINK
            ['name' => 'Es Teh Manis', 'category' => 'Drink', 'price' => 5000],
            ['name' => 'Es Teh Lemon', 'category' => 'Drink', 'price' => 7000],
            ['name' => 'Jus Jeruk Segar', 'category' => 'Drink', 'price' => 10000],
            ['name' => 'Jus Mangga', 'category' => 'Drink', 'price' => 11000],
            ['name' => 'Es Kopi Susu', 'category' => 'Drink', 'price' => 15000],
            ['name' => 'Americano', 'category' => 'Drink', 'price' => 16000],
            ['name' => 'Cappuccino', 'category' => 'Drink', 'price' => 17000],

            // FUN DRINK
            ['name' => 'Es Teh Santai 🧊', 'category' => 'Drink', 'price' => 6000],
            ['name' => 'Kopi Bangunin Kamu ☕', 'category' => 'Drink', 'price' => 18000],
            ['name' => 'Jus Super Fresh 🍊', 'category' => 'Drink', 'price' => 12000],

            // SNACK
            ['name' => 'Kentang Goreng Crispy', 'category' => 'Snack', 'price' => 12000],
            ['name' => 'Pisang Goreng Crispy', 'category' => 'Snack', 'price' => 10000],
            ['name' => 'Tahu Crispy', 'category' => 'Snack', 'price' => 9000],
            ['name' => 'Tempe Mendoan', 'category' => 'Snack', 'price' => 9000],
            ['name' => 'Roti Bakar Coklat', 'category' => 'Snack', 'price' => 11000],

            // FUN SNACK
            ['name' => 'Kentang Bahagia 🍟', 'category' => 'Snack', 'price' => 13000],
            ['name' => 'Pisang Joget 🍌', 'category' => 'Snack', 'price' => 11000],
            ['name' => 'Roti Bakar Cinta ❤️', 'category' => 'Snack', 'price' => 12000],
        ];

        $data = [];

        foreach ($products as $product) {

            if (!isset($categoryMap[$product['category']])) {
                continue;
            }

            $data[] = [
                'name' => $product['name'],
                'category_id' => $categoryMap[$product['category']],
                'price' => $product['price'],
                'is_package' => false,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('products')->insert($data);

        // PACKAGES
        if (isset($categoryMap['Package'])) {

            DB::table('products')->insert([
                [
                    'name' => 'Paket Hemat Ayam Geprek + Es Teh',
                    'category_id' => $categoryMap['Package'],
                    'price' => 25000,
                    'is_package' => true,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Paket Kenyang Bahagia 😋',
                    'category_id' => $categoryMap['Package'],
                    'price' => 30000,
                    'is_package' => true,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Paket Sultan 👑',
                    'category_id' => $categoryMap['Package'],
                    'price' => 40000,
                    'is_package' => true,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}