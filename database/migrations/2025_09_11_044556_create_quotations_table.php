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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('project_name', 255)->nullable();
            $table->string('floor_info', 255)->nullable();
            $table->string('diagram_image')->nullable();
            $table->string('quotation_number', 50)->unique();
            $table->integer('customer_id');
            $table->date('quotation_date');
            $table->date('expiry_date')->nullable();
            $table->decimal('total_amount', 28, 8)->default(0);
            $table->decimal('used_product_amount', 28, 8)->default(0);
            $table->decimal('invoiced_amount', 28, 8)->default(0);
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 2=Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
