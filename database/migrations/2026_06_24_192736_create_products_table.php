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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commerce_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained();
            $table->string('codigo');
            $table->string('description');
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['commerce_id', 'codigo']);
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
