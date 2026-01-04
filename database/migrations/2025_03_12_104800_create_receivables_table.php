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
        Schema::create('receivables', function (Blueprint $table) {
            $table->id();
            $table->integer('sell_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('receivable_head_id');
            $table->string('invoice_no')->nullable();
            // $table->decimal('supplier_advance_amount', 28, 8)->nullable();
            // $table->decimal('employee_advance_amount', 28, 8)->nullable();
            // $table->decimal('due_amount', 28, 8)->nullable();
            $table->integer('entry_type')->default(0);
            $table->string('debit_or_credit')->nullable();
            $table->decimal('effective_amount', 28, 8)->default(0);
            $table->decimal('receivable_amount', 28, 8)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};
