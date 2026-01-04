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
        Schema::create('employee_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->integer('monthly_transaction_id');
            $table->string('salary_date')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->decimal('salary_amount', 28, 8)->default(0);
            $table->decimal('received_amount', 28, 8)->default(0);
            $table->decimal('punishment', 28, 8)->default(0);
            $table->text('description')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('entry_by')->nullable();
            $table->integer('update_by')->nullable();
            $table->tinyInteger('is_deleted')->default(0)->comment('1 = deleted, 0 = not deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_transactions');
    }
};
