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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->string('outlet')->nullable();
            $table->text('descrip')->nullable();
            $table->longText('details')->nullable();
            $table->tinyInteger("status")->comment('0=Not active; 1=Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
