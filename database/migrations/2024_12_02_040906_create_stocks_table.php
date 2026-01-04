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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id');
            $table->integer('purchase_batch_id');
            $table->integer('product_id');
            $table->string('product_name');
            $table->decimal('avg_purchase_price', 28, 8);
            $table->integer('purchase_qty');
            $table->decimal('total_purchase_price', 28, 8);
            $table->integer('stock');
            $table->integer('warehouse_id')->comment("warehouses.id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
