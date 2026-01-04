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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->string('account_no');
            $table->string('bank_name');
            $table->string('branch_name');
            $table->decimal('balance', 28, 8)->default(0);
            $table->integer('entry_by');
            $table->dateTime('entry_date');
            $table->integer('update_by')->nullable();
            $table->dateTime('last_update')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('is_deleted')->default(0)->comment('1 = deleted, 0 = Not Deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
