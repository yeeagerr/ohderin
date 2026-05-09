<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('register_id')->nullable()->after('user_id')->constrained('registers')->nullOnDelete();
            $table->foreignId('register_session_id')->nullable()->after('register_id')->constrained('register_sessions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['register_id']);
            $table->dropForeign(['register_session_id']);
            $table->dropColumn(['register_id', 'register_session_id']);
        });
    }
};
