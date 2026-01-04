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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('trn_number')->nullable();
            $table->string('trn_date')->nullable();
            $table->string('company')->nullable();
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->text('comments')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('status')->comment('0 = Inactive, 1 = Active');
            $table->string('type')->nullable();
            $table->integer('entry_by')->nullable();
            $table->timestamp('entry_date')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
