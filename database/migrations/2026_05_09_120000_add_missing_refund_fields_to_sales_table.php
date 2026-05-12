<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sales', 'refund_amount')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->decimal('refund_amount', 14, 2)->default(0)->after('change_amount');
            });
        }

        if (!Schema::hasColumn('sales', 'refund_reason')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->text('refund_reason')->nullable()->after('refund_amount');
            });
        }

        if (!Schema::hasColumn('sales', 'refunded_at')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->timestamp('refunded_at')->nullable()->after('refund_reason');
            });
        }

        if (!Schema::hasColumn('sales', 'refunded_by')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->foreignId('refunded_by')->nullable()->after('refunded_at')->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'refunded_by')) {
                $table->dropConstrainedForeignId('refunded_by');
            }

            foreach (['refunded_at', 'refund_reason', 'refund_amount'] as $column) {
                if (Schema::hasColumn('sales', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
