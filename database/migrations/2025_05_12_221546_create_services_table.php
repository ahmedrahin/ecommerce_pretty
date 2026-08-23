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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('client_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('sale_date')->nullable();
            $table->string('order_date')->nullable();
            $table->string('order_id')->nullable();
            $table->json('product_name')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('media')->nullable();
            $table->string('model')->nullable();
            $table->string('branch')->nullable();
            $table->string('department')->nullable();
            $table->string('recive_by')->nullable();
            $table->string('status')->default('pending');
            $table->string('remarks')->nullable();
            $table->string('note')->nullable();
            $table->integer('service_cost')->nullable();
            $table->timestamp('date_of')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
