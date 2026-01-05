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
        Schema::create('poultry_expenses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('batch_id');
            $table->string('expense_title');
            $table->string('invoice_number');
            $table->enum('category', ['feed', 'medicine', 'transportation', 'bedding', 'labor', 'utilities', 'death_loss', 'miscellaneous', 'bad_debt', 'bio_security', 'chickens', 'other']);
            $table->string('feed_name')->nullable();
            $table->enum('feed_type', ['pre_starter', 'starter', 'grower', 'finisher', 'pre_layer', 'layer', 'other'])->nullable();
            $table->string('medicine_name')->nullable();
            $table->enum('transaction_type', ['due', 'cash']);
            $table->float('quantity');
            $table->enum('unit', ['bag', 'piece', 'kg', 'litre', 'gram', 'bottle', 'pack', 'other']);
            $table->decimal('price', 8, 2);
            $table->decimal('total_amount', 8, 2);
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poultry_expenses');
    }
};
