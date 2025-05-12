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
        Schema::table('penalty_payments', function (Blueprint $table) {
            $table->decimal('base_amount', 10, 2)->default(50);
            $table->decimal('daily_rate', 10, 2)->default(5);
            $table->unsignedInteger('days_late')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penalty_payments', function (Blueprint $table) {
            $table->dropColumn(['base_amount', 'daily_rate', 'days_late']);
        });
    }
};
