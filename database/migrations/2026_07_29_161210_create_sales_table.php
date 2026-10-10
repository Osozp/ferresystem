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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commerce_id')->constrained()->cascadeOnDelete(); // Aislamiento multi-tenant
            $table->foreignId('user_id')->constrained(); // Vendedor (foreign key real)
            $table->foreignId('cash_register_id')->nullable()->constrained('cash_registers')->nullOnDelete();
            $table->decimal('total', 10, 2)->default(0.00); // Cambio de string a decimal para cálculos
            $table->date('sale_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
