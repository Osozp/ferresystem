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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commerce_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cash_register_id')->nullable()->constrained('cash_registers')->nullOnDelete();
            $table->string('status')->default('completed');
            $table->decimal('total', 10, 2);
            $table->dateTime('purchase_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
