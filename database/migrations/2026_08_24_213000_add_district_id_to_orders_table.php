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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'district_id')) {
                $table->integer('district_id')->nullable()->after('city');
            }
            if (!Schema::hasColumn('orders', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('delivery_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'district_id')) {
                $table->dropColumn('district_id');
            }
        });
    }
};
