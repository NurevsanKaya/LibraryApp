<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('stock', function (Blueprint $table) {
        // acquisition_source sütununu isim değiştirerek acquisition_source_id'ye çeviriyoruz
        $table->renameColumn('acquisition_source', 'acquisition_source_id');
    });

    // acquisition_source_id sütununu acquisition_source tablosu ile ilişkilendiriyoruz
    Schema::table('stock', function (Blueprint $table) {
        $table->unsignedBigInteger('acquisition_source_id')->nullable()->change();
        $table->foreign('acquisition_source_id')->references('id')->on('acquisition_source')->onDelete('set null');
    });
}

public function down()
{
    // Eski isimdeki acquisition_source_id'yi tekrar eski haline getiriyoruz
    Schema::table('stock', function (Blueprint $table) {
        $table->dropForeign(['acquisition_source_id']);
        $table->renameColumn('acquisition_source_id', 'acquisition_source');
    });
}
};
