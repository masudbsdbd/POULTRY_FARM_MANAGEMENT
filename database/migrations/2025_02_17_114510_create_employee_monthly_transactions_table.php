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
        Schema::create('employee_monthly_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('month', 7); 
            $table->decimal('net_salary', 28, 8);
            $table->decimal('salary_amount', 28, 8);
            $table->decimal('punishment', 28, 8);
            $table->decimal('total_paid', 28, 8)->default(0);
            $table->decimal('due', 28, 8)->default(0);
            $table->decimal('advance', 28, 8)->default(0);
            $table->tinyInteger('bs_status')->default(0)->comment('1 = calculated, 0 = not calculated');
            $table->tinyInteger('is_deleted')->default(0)->comment('1 = deleted, 0 = not deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_monthly_transactions');
    }
};
