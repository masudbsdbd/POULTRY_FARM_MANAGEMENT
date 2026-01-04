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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('price', 28, 8);
            $table->decimal('avg_purchase_price', 28, 8)->default(0);
            $table->integer('qty');
            $table->integer('warehouse_id')->comment("warehouses.id");
            $table->string('supplier_name');
            $table->decimal('total_amount', 28, 8);
            $table->unsignedBigInteger('update_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
