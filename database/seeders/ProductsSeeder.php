<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Food', 'Beverage', 'Snack', 'Dessert', 'Coffee', 'Juice', 'Tea', 'Special'];
        $batchSize = 1000;
        $totalProducts = 120000;
        $now = Carbon::now();

        for ($i = 0; $i < $totalProducts; $i += $batchSize) {
            $data = [];
            for ($j = 0; $j < $batchSize; $j++) {
                $currentIndex = $i + $j + 1;
                $category = $categories[array_rand($categories)];
                $price = rand(10, 200) * 500; // 5000 to 100000, multiple of 500

                $data[] = [
                    'name' => "Product Item #{$currentIndex} - {$category}",
                    'category' => $category,
                    'price' => $price,
                    'is_package' => false,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('products')->insert($data);

        // Optional: output progress to console if needed locally, but we'll skip for automated seeder
        }

        // Add some specific package products manually for testing
        DB::table('products')->insert([
            'name' => 'Paket Hemat 1',
            'category' => 'Package',
            'price' => 25000,
            'is_package' => true,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
