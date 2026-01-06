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
        Schema::create('poultry_sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('batch_id');
            $table->enum('sale_type', ['by_weight', 'by_piece'])->default('by_weight');
            $table->decimal('quantity', 8, 2);
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->decimal('rate', 8, 2);
            $table->decimal('total_amount', 8, 2);
            $table->decimal('paid_amount', 8, 2)->default(0);
            $table->enum('payment_status', ['due', 'partial', 'paid'])->default('due');
            $table->date('payment_date');
            $table->date('sale_date');
            $table->enum('sales_channel', ['wholesale', 'retail'])->default('wholesale');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poultry_sales');
    }
};
