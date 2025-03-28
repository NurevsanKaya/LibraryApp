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
        Schema::table('acquisition_source', function (Blueprint $table) {
            if (!Schema::hasColumn('acquisition_source', 'name')) {
                $table->string('name')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acquisition_source', function (Blueprint $table) {
            if (Schema::hasColumn('acquisition_source', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
