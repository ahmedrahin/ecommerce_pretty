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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('model')->nullable();

            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subcategory_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subsubcategory_id')->nullable()->constrained()->onDelete('set null');

            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->decimal('base_price', 8, 0)->default(0);
            $table->unsignedInteger('discount_option')->nullable()->comment('1=No Discount, 2=Percentage %, 3=Fixed Price');
            $table->decimal('discount_percentage_or_flat_amount', 5, 2)->nullable();
            $table->decimal('discount_amount', 8, 0)->nullable();
            $table->decimal('offer_price', 8, 0)->default(0);
            $table->integer('wholesale_price')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->text('sku_code')->nullable();
            $table->text('video_link')->nullable();
            $table->unsignedInteger('status')->default(1)->comment('0=Inactive, 1=Active, 2=Draft, 3=Scheduled');
            $table->timestamp('publish_at')->nullable()->comment('Scheduled publish date');
            $table->timestamp('expire_date')->nullable()->comment('Scheduled expire date');
            $table->text('thumb_image')->nullable();
            $table->text('back_image')->nullable();
            $table->enum('free_shipping', ['yes', 'no'])->default('no')->comment('yes or no');
            $table->boolean('is_new')->default(2)->comment('1=yes or 2=no');
            $table->boolean('is_featured')->default(2)->comment('1=Yes, 2=No');
            $table->boolean('pre_order')->default(2)->comment('1=Yes, 2=No');
            $table->string('badge')->nullable();
            $table->boolean('stock_out')->default(0)->comment('1=Yes, 0=No');
            $table->longText('key_features')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
