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
        Schema::create('damages', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id');
            $table->integer('product_id');
            $table->integer('purchase_batch_id');
            $table->integer('supplier_id');
            $table->decimal('price', 28, 8);
            $table->decimal('total_damage_price', 28, 8);
            $table->integer('qty');
            $table->integer('total_qty');
            $table->longText('description')->nullable();
            $table->longText('conversation')->nullable();
            // $table->text('image')->nullable();
            $table->date('entry_date');
            $table->date('replacement_repair_date')->nullable();
            $table->date('last_update')->nullable();
            $table->integer('entry_by')->nullable();
            $table->integer('update_by')->nullable();
            $table->tinyInteger('is_deleted')->default(0)->comment('1 = deleted, 0 = not deleted');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('damage_status')
                ->default(0)
                ->comment('Indicates the status of the damage: 0 = Draft (not finalized), 1 = Confirmed Damaged (finalized)');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damages');
    }
};
