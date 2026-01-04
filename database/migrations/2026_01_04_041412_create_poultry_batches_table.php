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
        Schema::create('poultry_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('batch_name');
            $table->string('batch_number')->nullable();
            $table->string('chicken_type');
            $table->integer('total_chickens');
            $table->decimal('price_per_chicken', 8, 2);
            $table->enum('chicken_grade', ['A', 'B', 'C', 'D'])->nullable();
            $table->string('hatchery_name');
            $table->string('shed_number')->nullable();
            $table->decimal('target_feed_qty', 10, 2)->nullable();
            $table->enum('terget_feed_unit', ['bag', 'kg'])->nullable();
            $table->text('batch_description')->nullable();
            $table->date('batch_start_date');
            $table->date('batch_close_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poultry_batches');
    }
};
