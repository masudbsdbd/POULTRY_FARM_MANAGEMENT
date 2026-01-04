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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_id');
            $table->timestamp('purchase_date');
            $table->integer('total_qty');
            $table->decimal('main_price', 28, 8)->nullable();
            $table->decimal('total_price', 28, 8)->nullable();
            $table->decimal('commission', 28, 8)->nullable();
            $table->decimal('discount', 28, 8)->nullable();
            $table->decimal('payment_received', 28, 8)->nullable();
            $table->decimal('due_to_company', 28, 8)->nullable();
            $table->decimal('advance', 28, 8)->nullable();
            $table->decimal('purchase_revision_advance', 28, 8)->nullable();
            $table->string('invoice_no', 40)->nullable();
            $table->integer('entry_by')->nullable();
            $table->date('entry_date');
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
        Schema::dropIfExists('purchases');
    }
};
