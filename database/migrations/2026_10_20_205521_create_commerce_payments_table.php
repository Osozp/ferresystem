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
        Schema::create('commerce_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commerce_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2); // Monto pagado
            $table->integer('days_added'); // Días que se añadieron a la suscripción
            $table->string('reference')->nullable(); // Número de comprobante o transacción
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commerce_payments');
    }
};
