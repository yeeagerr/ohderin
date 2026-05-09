<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('register_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('register_id')->constrained('registers')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('opened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('opened_at');
            $table->decimal('opening_cash', 14, 2);
            $table->text('opening_note')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->decimal('closing_cash', 14, 2)->nullable();
            $table->integer('total_transactions')->default(0);
            $table->decimal('total_sales', 14, 2)->default(0);
            $table->decimal('cash_difference', 14, 2)->nullable();
            $table->json('session_summary')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('register_sessions');
    }
};
