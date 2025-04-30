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
            $table->string('receipt_path')->nullable()->after('status');
            $table->enum('status', ['ödeme bekleniyor', 'bekliyor', 'onaylandı', 'reddedildi'])
                ->default('ödeme bekleniyor')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penalty_payments', function (Blueprint $table) {
            //
        });
    }
};
