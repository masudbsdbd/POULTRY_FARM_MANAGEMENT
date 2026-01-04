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
        Schema::create('manage_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id')->nullable();
            $table->integer('total_qty');
            $table->decimal('grand_total', 28, 8);
            $table->text('description')->nullable();
            $table->integer('stock_status')->default(0)->comment('1 = Damage, 2 = Lost, 3 = Found, 4 = Expiry, 5 = Theft, 6 = Manual Increase, 7 = Manual Decrease');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage_stocks');
    }
};
