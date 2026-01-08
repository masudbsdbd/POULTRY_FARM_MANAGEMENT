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
        Schema::create('poultry_expense_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')
                ->constrained('poultry_expenses') // এখানে 'expenses' না, 'poultry_expenses'
                ->onDelete('cascade');
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poultry_expense_payments');
    }
};
