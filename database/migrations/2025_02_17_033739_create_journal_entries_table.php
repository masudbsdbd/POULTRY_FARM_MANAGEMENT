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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('code')->nullable();
            $table->string('debit_or_credit');
            $table->text('description')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->decimal('amount', 28, 8)->default(0);
            $table->integer('entry_by')->nullable();
            $table->integer('update_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
