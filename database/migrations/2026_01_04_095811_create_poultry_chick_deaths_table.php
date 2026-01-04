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
        Schema::create('poultry_chick_deaths', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('batch_id');
            $table->string('cause_of_death');
            $table->date('date_of_death');
            $table->integer('total_deaths');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poultry_chick_deaths');
    }
};
