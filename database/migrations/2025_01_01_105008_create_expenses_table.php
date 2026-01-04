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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('expense_head_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('type')->default(0);
            $table->date('entry_date');
            $table->string('debit_or_credit')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount', 28, 8)->default(0);
            $table->decimal('effective_amount', 28, 8)->default(0);
            $table->decimal('paid_amount', 28, 8)->default(0);
            $table->tinyInteger('pending_status')->default(0);
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
        Schema::dropIfExists('expenses');
    }
};
