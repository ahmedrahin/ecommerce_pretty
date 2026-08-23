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
        if (Schema::hasColumn('orders', 'printful_order_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('printful_order_id');
            });
        }

        if (Schema::hasColumn('product_stocks', 'printful_variant_id')) {
            Schema::table('product_stocks', function (Blueprint $table) {
                $table->dropColumn('printful_variant_id');
            });
        }

        if (Schema::hasColumn('products', 'p_logo')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('p_logo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('printful_order_id')->nullable();
        });

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->string('printful_variant_id')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('p_logo')->nullable();
        });
    }
};
