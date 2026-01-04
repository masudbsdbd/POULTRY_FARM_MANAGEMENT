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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('company');
            $table->string('mobile');
            $table->string('address');
            $table->string('email')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->integer('entry_by')->nullable();
            $table->timestamp('entry_date')->nullable();
            $table->integer('update_by')->nullable();
            $table->decimal('advance', 28, 8)->default(0);
            $table->decimal('due', 28, 8)->default(0);
            $table->tinyInteger('is_deleted')->default(0)->comment('1 = deleted, 0 = not deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
