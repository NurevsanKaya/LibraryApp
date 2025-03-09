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

        Schema::table('bookshelves', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->after('bookshelf_number');
            $table->unsignedBigInteger('genre_id')->after('category_id');

            // Yabancı anahtarlar
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookshelves', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['genre_id']);
            $table->dropColumn(['category_id', 'genre_id']);
        });
    }
};
