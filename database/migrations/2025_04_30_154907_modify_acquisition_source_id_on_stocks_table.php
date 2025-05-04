<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Sütunun türünü integer'a çevir (foreign key olabilmesi için)
        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('acquisition_source_id')->change();
        });

        // Foreign key constraint'i ekle
        Schema::table('stocks', function (Blueprint $table) {
            $table->foreign('acquisition_source_id')
                ->references('id')
                ->on('acquisition_source')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Geri alırken foreign key’i kaldır ve tekrar string'e çevir
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['acquisition_source_id']);
            $table->string('acquisition_source_id')->change();
        });
    }
};

