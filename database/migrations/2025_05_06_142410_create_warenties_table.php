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
        Schema::create('warenties', function (Blueprint $table) {
            $table->id();
            $table->string('dealer')->nullable();
            $table->string('client_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('sale_date')->nullable();
            $table->string('provider')->nullable();
            $table->string('order_id')->nullable();
            $table->string('recive_by')->nullable();
            $table->string('status')->default('pending');
            $table->string('note')->nullable();
            $table->timestamp('date_of')->nullable();
            $table->integer('cost')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warenties');
    }
};
