<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sale_item_modifiers', 'modifier_id')) {
            Schema::table('sale_item_modifiers', function (Blueprint $table) {
                $table->foreignId('modifier_id')->nullable()->after('sale_item_id')->constrained('modifiers')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sale_item_modifiers', 'modifier_id')) {
            Schema::table('sale_item_modifiers', function (Blueprint $table) {
                $table->dropConstrainedForeignId('modifier_id');
            });
        }
    }
};
