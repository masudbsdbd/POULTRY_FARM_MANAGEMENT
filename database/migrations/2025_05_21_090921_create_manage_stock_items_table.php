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
        Schema::create('manage_stock_items', function (Blueprint $table) {
            $table->id();
            $table->integer('manage_stock_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('batch_id')->nullable();
            $table->integer('qty');
            $table->decimal('avg_purchase_price', 28, 8)->nullable();
            $table->decimal('total_amount', 28, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage_stock_items');
    }
};
