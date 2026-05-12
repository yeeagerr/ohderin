<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->enum('order_type', ['dine_in', 'take_away']);
            $table->decimal('total', 14, 2);
            $table->decimal('paid_amount', 14, 2)->nullable();
            $table->decimal('change_amount', 14, 2)->nullable();
            $table->enum('payment_method', ['cash', 'qris', 'debit', 'credit']);
            $table->enum('status', ['completed', 'draft', 'refunded'])->change();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
