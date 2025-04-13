<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. adım: foreign key varsa kaldır
        Schema::table('stocks', function (Blueprint $table) {
            if (Schema::hasColumn('stocks', 'acquisition_source_id')) {
                try {
                    $table->dropForeign(['acquisition_source_id']);
                } catch (\Exception $e) {
                    // FK yoksa sessizce geç
                }
            }
        });

        // 2. adım: sütunu kaldır (eğer varsa)
        Schema::table('stocks', function (Blueprint $table) {
            if (Schema::hasColumn('stocks', 'acquisition_source_id')) {
                $table->dropColumn('acquisition_source_id');
            }
        });

        // 3. adım: sütunu tekrar oluştur ve FK ekle
        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedInteger('acquisition_source_id')->nullable()->after('shelf_id');

            $table->foreign('acquisition_source_id')
                ->references('id')
                ->on('acquisition_source')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Önce FK’yi kaldır, sonra sütunu
        Schema::table('stocks', function (Blueprint $table) {
            try {
                $table->dropForeign(['acquisition_source_id']);
            } catch (\Exception $e) {}

            if (Schema::hasColumn('stocks', 'acquisition_source_id')) {
                $table->dropColumn('acquisition_source_id');
            }

            // Geriye string olarak eklenecekse:
            $table->string('acquisition_source_id')->nullable()->after('shelf_id');
        });
    }
};
