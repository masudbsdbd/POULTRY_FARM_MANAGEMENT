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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id')->nullable();
            $table->integer('sell_id')->nullable();
            $table->integer('expense_id')->nullable();
            $table->integer('sell_return_id')->nullable();
            $table->integer('purchase_return_id')->nullable();
            $table->integer('damage_id')->nullable();
            $table->integer('income_id')->nullable();
            $table->integer('investment_id')->nullable();
            $table->integer('payable_id')->nullable();
            $table->integer('receivable_id')->nullable();
            $table->integer('asset_id')->nullable();
            $table->integer('type')->comment('Purchase = 1, Sell = 2, bank deposit = 3, bank withdraw = 4, Customer Advance = 5, Supplier Advance = 6, Customer Due = 7, Supplier Due = 8, Expense = 9, Sell Return = 10, Purchase Return = 11, Employee Salary = 12, Customer = 13, Supplier = 14, Income = 15, Investment = 16, Receivable = 17, Asset = 18, Damage = 19');
            $table->decimal('balance', 28, 8)->default(0);
            $table->decimal('credit', 28, 8)->default(0);
            $table->decimal('debit', 28, 8)->default(0);
            $table->decimal('amount', 28, 8)->default(0);
            $table->text('description')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('entry_type')->default(0);
            $table->tinyInteger('payment_method')->comment('unpaid = 0, cash = 1, bank = 2');
            $table->integer('entry_by');
            $table->tinyInteger('status')->comment('unpaid = 0, debit = 1, credit = 2');
            $table->date('entry_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
