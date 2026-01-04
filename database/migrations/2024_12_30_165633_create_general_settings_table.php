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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('user_image')->nullable();
            $table->string('favicon')->nullable();
            $table->string('company_name')->nullable();
            $table->string('trn_number')->nullable();
            $table->string('primary_contact_number')->nullable();
            $table->string('alternate_contact_number')->nullable();
            $table->string('primary_email_address')->nullable();
            $table->string('alternate_email_address')->nullable();
            $table->string('website_url')->nullable();
            $table->integer('pagination');
            $table->boolean('subcategory_module')->comment('1 = activated, 0 = deactivated');
            $table->boolean('brand_module')->comment('1 = activated, 0 = deactivated');
            $table->boolean('barcode')->comment('1 = activated, 0 = deactivated');
            $table->boolean('bs_module')->comment('1 = activated, 0 = deactivated');
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
