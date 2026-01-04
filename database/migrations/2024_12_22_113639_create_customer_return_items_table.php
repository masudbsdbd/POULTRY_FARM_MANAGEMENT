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
        Schema::create('customer_return_items', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_return_id');
            $table->integer('purchase_batch_id');
            $table->integer('purchase_id');
            $table->integer('sell_id');
            $table->integer('product_id');
            $table->integer('return_qty');
            $table->decimal('avg_sell_price', 28, 8);
            $table->decimal('retun_sell_price', 28, 8);
            $table->decimal('retun_total_sell_price', 28, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_return_items');
    }
};
