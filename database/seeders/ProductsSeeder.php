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
        $categoryMap = DB::table('categories')->pluck('id', 'name')->toArray();
        $categories = array_keys($categoryMap);

        $batchSize = 1000;
        $totalProducts = 120000;
        $now = Carbon::now();

        for ($i = 0; $i < $totalProducts; $i += $batchSize) {
            $data = [];
            for ($j = 0; $j < $batchSize; $j++) {
                $currentIndex = $i + $j + 1;
                $categoryName = $categories[array_rand($categories)];
                $categoryId = $categoryMap[$categoryName];
                $price = rand(10, 200) * 500; // 5000 to 100000, multiple of 500

                $data[] = [
                    'name' => "Product Item #{$currentIndex} - {$categoryName}",
                    'category_id' => $categoryId,
                    'price' => $price,
                    'is_package' => false,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('products')->insert($data);
        }

        // Add some specific package products manually for testing
        $packageCategoryId = $categoryMap['Package'] ?? null;

        DB::table('products')->insert([
            'name' => 'Paket Hemat 1',
            'category_id' => $packageCategoryId,
            'price' => 25000,
            'is_package' => true,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
