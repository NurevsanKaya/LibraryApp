<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Foreign key ve sütunu kaldırma işlemlerini try-catch bloğu içinde yapıyoruz
        Schema::table('stocks', function (Blueprint $table) {
            try {
                // Foreign key constraint'i varsa kaldır
                $table->dropForeign(['acquisition_source_id']);
            } catch (\Exception $e) {
                // Foreign key constraint yoksa hata atmayı görmezden gel
            }
            
            // Mevcut sütunu kaldır
            $table->dropColumn('acquisition_source_id');
        });
        
        Schema::table('stocks', function (Blueprint $table) {
            // Yeni Integer sütunu ekle
            $table->unsignedInteger('acquisition_source_id')->nullable()->after('shelf_id');
            
            // Foreign key constraint ekle
            $table->foreign('acquisition_source_id')
                  ->references('id')
                  ->on('acquisition_source')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            try {
                // Foreign key constraint'i kaldır
                $table->dropForeign(['acquisition_source_id']);
            } catch (\Exception $e) {
                // Hata olursa görmezden gel
            }
            
            // Sütunu kaldır
            $table->dropColumn('acquisition_source_id');
            
            // Eski string sütunu geri ekle
            $table->string('acquisition_source_id')->nullable()->after('shelf_id');
        });
    }
};
