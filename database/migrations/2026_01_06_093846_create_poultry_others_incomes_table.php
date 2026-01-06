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
        Schema::create('poultry_others_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('poultry_batches')->onDelete('cascade');
            $table->string('title');
            $table->decimal('amount', 8, 2);
            $table->text('note');
            $table->date('income_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poultry_others_incomes');
    }
};
