<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RawMaterial;

class RawMaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            ['name' => 'Beras', 'unit' => 'kg', 'cost' => 12000, 'minimal_stock' => 50],
            ['name' => 'Telur', 'unit' => 'kg', 'cost' => 25000, 'minimal_stock' => 10],
            ['name' => 'Daging Ayam', 'unit' => 'kg', 'cost' => 35000, 'minimal_stock' => 20],
            ['name' => 'Daging Sapi', 'unit' => 'kg', 'cost' => 120000, 'minimal_stock' => 10],
            ['name' => 'Minyak Goreng', 'unit' => 'liter', 'cost' => 15000, 'minimal_stock' => 20],
            ['name' => 'Gula Pasir', 'unit' => 'kg', 'cost' => 14000, 'minimal_stock' => 15],
            ['name' => 'Tepung Terigu', 'unit' => 'kg', 'cost' => 10000, 'minimal_stock' => 10],
            ['name' => 'Bawang Merah', 'unit' => 'kg', 'cost' => 30000, 'minimal_stock' => 5],
            ['name' => 'Bawang Putih', 'unit' => 'kg', 'cost' => 25000, 'minimal_stock' => 5],
            ['name' => 'Cabe Merah', 'unit' => 'kg', 'cost' => 40000, 'minimal_stock' => 5],
            ['name' => 'Cabe Rawit', 'unit' => 'kg', 'cost' => 50000, 'minimal_stock' => 5],
            ['name' => 'Kecap Manis', 'unit' => 'liter', 'cost' => 20000, 'minimal_stock' => 10],
            ['name' => 'Saus Tomat', 'unit' => 'kg', 'cost' => 15000, 'minimal_stock' => 10],
            ['name' => 'Saus Sambal', 'unit' => 'kg', 'cost' => 15000, 'minimal_stock' => 10],
            ['name' => 'Garam', 'unit' => 'kg', 'cost' => 5000, 'minimal_stock' => 10],
            ['name' => 'Merica', 'unit' => 'kg', 'cost' => 80000, 'minimal_stock' => 1],
            ['name' => 'Susu Cair', 'unit' => 'liter', 'cost' => 18000, 'minimal_stock' => 10],
            ['name' => 'Kopi Bubuk', 'unit' => 'kg', 'cost' => 60000, 'minimal_stock' => 5],
            ['name' => 'Teh Celup', 'unit' => 'box', 'cost' => 5000, 'minimal_stock' => 20],
            ['name' => 'Air Mineral Galon', 'unit' => 'galon', 'cost' => 18000, 'minimal_stock' => 5],
        ];

        foreach ($materials as $material) {
            RawMaterial::create($material);
        }
    }
}
