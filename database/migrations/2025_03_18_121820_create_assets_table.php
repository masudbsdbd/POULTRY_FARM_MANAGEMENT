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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('asset_head_id');
            $table->text('description')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('purchase_date'); 
            // $table->decimal('depreciation_rate', 5, 2)->default(0.0);
            // $table->decimal('current_value', 10, 2)->nullable(); 
            // $table->string('supplier')->nullable();
            $table->integer('type')->default(0);
            $table->string('debit_or_credit')->nullable();
            $table->decimal('effective_amount', 28, 8)->default(0);
            $table->integer('entry_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
