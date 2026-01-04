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
        Schema::create('bs_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('journal_entry_id');
            $table->integer('investment_id')->nullable();
            $table->integer('purchase_id')->nullable();
            $table->integer('sell_id')->nullable();
            $table->integer('expense_id')->nullable();
            $table->integer('purchase_return_id')->nullable();
            $table->integer('sell_return_id')->nullable();
            $table->string('account_type'); 
            $table->string('account_sub_type'); 
            $table->text('description')->nullable();
            $table->decimal('debit', 28, 8)->default(0); 
            $table->decimal('credit', 28, 8)->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bs_accounts');
    }
};
