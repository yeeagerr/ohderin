<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Food', 'description' => 'Various food items'],
            ['name' => 'Beverage', 'description' => 'Various drink items'],
            ['name' => 'Snack', 'description' => 'Light snacks'],
            ['name' => 'Dessert', 'description' => 'Sweet desserts'],
            ['name' => 'Coffee', 'description' => 'Freshly brewed coffee'],
            ['name' => 'Juice', 'description' => 'Fresh fruit juices'],
            ['name' => 'Tea', 'description' => 'Various tea blends'],
            ['name' => 'Special', 'description' => 'Special menu items'],
            ['name' => 'Package', 'description' => 'Bundled package products'],
        ];

        $now = Carbon::now();
        foreach ($categories as &$category) {
            $category['created_at'] = $now;
            $category['updated_at'] = $now;
        }

        DB::table('categories')->insert($categories);
    }
}
