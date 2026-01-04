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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->integer('bank_id');
            $table->string('check_no')->nullable();
            $table->string('description')->nullable();
            $table->decimal('credit', 28, 8)->default(0);
            $table->decimal('debit', 28, 8)->default(0);
            $table->decimal('balance', 28, 8)->default(0);
            $table->string('withdrawer_name')->nullable();
            $table->string('depositor_name')->nullable();
            $table->integer('entry_by')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->integer('expense_id')->nullable();
            $table->tinyInteger('status')->comment('debit = 1, credit = 2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
