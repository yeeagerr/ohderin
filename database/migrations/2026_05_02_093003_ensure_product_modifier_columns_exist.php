<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_modifier')) {
            return;
        }

        $missingProductId = !Schema::hasColumn('product_modifier', 'product_id');
        $missingModifierId = !Schema::hasColumn('product_modifier', 'modifier_id');

        if ($missingProductId || $missingModifierId) {
            Schema::table('product_modifier', function (Blueprint $table) use ($missingProductId, $missingModifierId) {
                if ($missingProductId) {
                    $table->foreignId('product_id')->after('id')->constrained('products')->cascadeOnDelete();
                }

                if ($missingModifierId) {
                    $table->foreignId('modifier_id')->after('product_id')->constrained('modifiers')->cascadeOnDelete();
                }

                $table->unique(['product_id', 'modifier_id']);
            });
        }
    }

    public function down(): void
    {
    }
};
