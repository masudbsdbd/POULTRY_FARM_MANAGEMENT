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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('mobile', 20);
            $table->string('address');
            $table->string('email')->nullable();
            $table->string('nid', 40);
            $table->string('designation');
            $table->text('image')->nullable();
            $table->date('joining_date');
            $table->decimal('salary', 28, 8);
            $table->decimal('conveyance', 28, 8)->default(0);
            $table->string('status');
            $table->integer('entry_by');
            $table->tinyInteger('is_deleted')->default(0)->comment('1 = deleted, 0 = not deleted');
            $table->timestamp('entry_date')->nullable();
            $table->integer('update_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
