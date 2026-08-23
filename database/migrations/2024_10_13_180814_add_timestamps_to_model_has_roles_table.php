<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            // Add timestamps columns only if they don't already exist
            if (!Schema::hasColumn('model_has_roles', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (!Schema::hasColumn('model_has_roles', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            // Remove the timestamps columns if they exist
            if (Schema::hasColumn('model_has_roles', 'created_at')) {
                $table->dropColumn('created_at');
            }

            if (Schema::hasColumn('model_has_roles', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
};
