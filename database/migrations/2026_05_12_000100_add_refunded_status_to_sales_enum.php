<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to modify the enum column
        // Using raw SQL to modify enum values
        if (DB::getDriverName() === 'mysql') {
            Schema::table('sales', function (Blueprint $table) {
                DB::statement("ALTER TABLE sales MODIFY status ENUM('completed', 'draft', 'refunded') DEFAULT 'completed'");
            });
        } else {
            // For other databases, use the normal change method
            Schema::table('sales', function (Blueprint $table) {
                $table->enum('status', ['completed', 'draft', 'refunded'])->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum
        if (DB::getDriverName() === 'mysql') {
            Schema::table('sales', function (Blueprint $table) {
                DB::statement("ALTER TABLE sales MODIFY status ENUM('completed', 'draft') DEFAULT 'completed'");
            });
        } else {
            Schema::table('sales', function (Blueprint $table) {
                $table->enum('status', ['completed', 'draft'])->change();
            });
        }
    }
};
