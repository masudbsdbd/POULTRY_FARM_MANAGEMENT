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
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->timestamp('sell_date');
            $table->decimal('total_price', 28, 8);
            $table->decimal('profit', 28, 8);
            $table->integer('total_qty');
            $table->decimal('payment_received', 28, 8)->nullable();
            $table->decimal('due_to_company', 28, 8);
            $table->decimal('advance', 28, 8)->nullable();
            $table->decimal('sell_revision_advance', 28, 8)->nullable();
            $table->string('invoice_no', 40)->nullable();
            $table->decimal('discount', 28, 8)->default(0);
            $table->integer('transport_cost')->default(0);
            $table->integer('labor_cost')->default(0);
            $table->string('labour_name')->nullable();
            $table->date('entry_date');
            $table->date('last_update')->nullable();
            $table->tinyInteger('delivery_status')->comment('0 = not delivered, 1 = delivered')->default(0);
            $table->timestamp('delivery_date')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('sells');
    }
};
