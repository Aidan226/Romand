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
        Schema::create('lipsticks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Product Name + Shade Name (ဥပမာ - Juicy Lasting Tint - Honey Dew Melon)
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(15);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lipsticks');
    }
};
