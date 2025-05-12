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
        Schema::table('penalty_settings', function (Blueprint $table) {
            $table->decimal('base_penalty_fee', 10, 2)->default(50)->after('id');
            $table->decimal('daily_penalty_fee', 10, 2)->default(5)->after('base_penalty_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penalty_settings', function (Blueprint $table) {
            $table->dropColumn(['base_penalty_fee', 'daily_penalty_fee']);
        });
    }
}; 