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
        Schema::create('product_warranties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warenty_id');
            $table->foreign('warenty_id')->references('id')->on('warenties')->onDelete('cascade');
            $table->string('product_name')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('remarks')->nullable();
            $table->string('model')->nullable();
            $table->text('problem')->nullable();
            $table->text('change')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_warranties');
    }
};
