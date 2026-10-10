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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commerce_id')->constrained('commerces')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('opening_balance', 10, 2);
            $table->decimal('closing_balance', 10, 2)->nullable();
            $table->decimal('expected_balance', 10, 2)->nullable();
            $table->decimal('difference', 10, 2)->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function(Blueprint $table){
            $table->dropForeign(['cash_register_id']);
            $table->dropColumn('cash_register_id');
        });

        Schema::table('purchases', function(Blueprint $table){
            $table->dropForeign(['cash_register_id']);
            $table->dropColumn('cash_register_id');
        });

        Schema::dropIfExists('cash_registers');
    }
};
