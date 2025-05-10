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
            $table->foreignId('borrowing_id')->nullable()->constrained('borrowings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penalty_payments', function (Blueprint $table) {
            $table->dropForeign(['borrowing_id']);
            $table->dropColumn('borrowing_id');
        });
    }
}; 