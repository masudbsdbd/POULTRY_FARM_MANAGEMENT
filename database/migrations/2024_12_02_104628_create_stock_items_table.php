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
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('product_name');
            $table->integer('total_purchase_qty');
            $table->integer('total_sell_qty')->default(0);
            $table->integer('purchase_return_qty')->default(0);
            $table->integer('sell_return_qty')->default(0);
            $table->integer('total_damage_qty')->default(0);
            $table->integer('total_delivered')->default(0);
            $table->integer('stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
