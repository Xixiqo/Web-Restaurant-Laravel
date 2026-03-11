<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations — create products table for the restaurant menu.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Product/pizza name
            $table->text('description');           // Product description
            $table->string('image')->nullable();   // Image filename or URL
            $table->decimal('price', 8, 2);        // Price in dollars
            $table->decimal('rating', 2, 1)->default(0.0); // Rating out of 5.0
            $table->timestamps();                  // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
