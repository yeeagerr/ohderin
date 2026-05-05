<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_material_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_material_id')->constrained('raw_materials')->cascadeOnDelete();
            $table->string('name', 50);
            $table->decimal('ratio', 12, 4)->default(1);
            $table->timestamps();

            $table->unique(['raw_material_id', 'name']);
        });

        DB::table('raw_materials')
            ->select('id', 'unit')
            ->orderBy('id')
            ->get()
            ->each(function ($material) {
                DB::table('raw_material_units')->insert([
                    'raw_material_id' => $material->id,
                    'name' => $material->unit,
                    'ratio' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_material_units');
    }
};
