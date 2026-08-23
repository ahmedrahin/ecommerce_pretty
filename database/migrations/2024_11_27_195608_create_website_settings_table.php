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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('product_count_enabled')->default(1);
            $table->integer('item_per_page')->nullable();
            $table->boolean('show_review')->default(1);
            $table->boolean('allow_review')->default(1);
            $table->boolean('allow_guest_review')->default(1);
            $table->boolean('show_wishlist')->default(1);
            $table->boolean('show_expire_date')->default(1);
            $table->boolean('guest_checkout')->default(1);
            $table->integer('login_attemp')->default(1);
            $table->integer('login_session')->default(1);
            $table->integer('cart_session')->default(1);
            $table->boolean('show_order_count')->default(1);
            $table->boolean('show_size_chart')->default(1);
            $table->boolean('ask_qustion')->default(1);
            $table->boolean('share')->default(1);
            $table->boolean('show_expire')->default(1);
            $table->boolean('product_info')->default(1);
            $table->boolean('buy_now_button')->default(1);

            $table->text('marquee_text')->nullable();
            $table->text('new_arrivale_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
