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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('barcode', 50)->unique();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('shelf_id')->constrained('shelves')->onDelete('cascade');
            $table->string('acquisition_source')->nullable();
            $table->decimal('acquisition_price', 10, 2)->nullable();
            $table->date('acquisition_date')->nullable();
            $table->enum('status', ['active', 'borrowed'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
