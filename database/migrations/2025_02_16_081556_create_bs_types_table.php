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
        Schema::create('bs_types', function (Blueprint $table) {
            $table->id();
            $table->integer('main_type')->nullable();
            $table->integer('sub_type')->nullable();
            $table->integer('type')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bs_types');
    }
};
