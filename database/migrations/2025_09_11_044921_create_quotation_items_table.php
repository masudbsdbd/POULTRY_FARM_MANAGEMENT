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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->integer('quotation_id');
            $table->integer('product_id');
            $table->text('description')->nullable();
            $table->integer('qty');
            $table->integer('used_qty')->default(0);
            $table->integer('invoiced_qty')->default(0);
            $table->float('approved_qty')->default(0);
            $table->decimal('unit_price', 28, 8);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
