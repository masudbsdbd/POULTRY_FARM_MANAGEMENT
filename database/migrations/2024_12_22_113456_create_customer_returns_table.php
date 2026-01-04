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
        Schema::create('customer_returns', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('base_product_id');
            $table->integer('total_qty');
            $table->decimal('total_return_price', 28, 8);
            $table->date('entry_date');
            $table->date('last_update')->nullable();
            $table->integer('entry_by')->nullable();
            $table->integer('update_by')->nullable();
            $table->tinyInteger('is_deleted')->default(0)->comment('1 = deleted, 0 = not deleted');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_returns');
    }
};
