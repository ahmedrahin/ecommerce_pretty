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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->text('fav_icon')->nullable();
            $table->text('logo')->nullable();
            $table->text('website_logo')->nullable();
            $table->text('website_footer_logo')->nullable();
            $table->text('dark_logo')->nullable();
            $table->text('footer_logo')->nullable();
            $table->text('site_title')->nullable();
            $table->string('number1')->nullable();
            $table->string('number2')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('whatsapp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
