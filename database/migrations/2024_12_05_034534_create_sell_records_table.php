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
        Schema::create('sell_records', function (Blueprint $table) {
            $table->id();
            $table->integer('sell_id');
            $table->integer('purchase_batch_id');
            $table->integer('product_id');
            $table->string('product_name');
            $table->decimal('discount', 28, 8)->default(0);
            $table->decimal('avg_purchase_price', 28, 8);
            $table->integer('qty');
            $table->integer('sell_qty');
            $table->decimal('sell_price', 28, 8);
            $table->decimal('profit', 28, 8);
            $table->decimal('avg_sell_price', 28, 8);
            $table->decimal('total_amount', 28, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_records');
    }
};
