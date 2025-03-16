<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('acquisition_source', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    // Verileri ekleyelim
    DB::table('acquisition_source')->insert([
        ['name' => 'Belediye'],
        ['name' => 'Kültür Bakanlığı'],
        ['name' => 'Milli Eğitim Bakanlığı'],
        ['name' => 'Üniversite'],
        ['name' => 'Vakıf'],
        ['name' => 'Dernek'],
        ['name' => 'Satın Alma'],
        ['name' => 'Bağış'],
        ['name' => 'Diğer'],
    ]);
}

public function down()
{
    Schema::dropIfExists('acquisition_source');
}
};
