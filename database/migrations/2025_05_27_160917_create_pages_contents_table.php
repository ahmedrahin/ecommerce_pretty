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
        Schema::create('pages_contents', function (Blueprint $table) {
            $table->id();
            $table->longText('privacy_policy')->nullable();
            $table->longText('refund_policy')->nullable();
            $table->longText('terms')->nullable();
            $table->longText('warranty_text')->nullable();
            $table->longText('servicing_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages_contents');
    }
};
