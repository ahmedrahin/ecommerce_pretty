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
        Schema::create('product_stock_manages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('stock')->comment('stock_in, out_of_stock');
            $table->integer('quantity')->nullable();
            $table->integer('product_price')->nullable();
            $table->integer('wholesale_price')->nullable();
            $table->integer('total_amount')->nullable();
            $table->timestamp('stocked_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_manages');
    }
};
