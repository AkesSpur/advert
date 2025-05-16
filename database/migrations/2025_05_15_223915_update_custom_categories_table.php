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
        Schema::table('custom_categories', function (Blueprint $table) {
            $table->boolean('filter_is_vip')->default(false);
            $table->boolean('filter_is_new')->default(false);
            $table->boolean('filter_is_verified')->default(false);
            $table->boolean('filter_has_video')->default(false);
            $table->boolean('filter_is_cheapest')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_categories', function (Blueprint $table) {
            $table->dropColumn('filter_is_vip');
            $table->dropColumn('filter_is_new');
            $table->dropColumn('filter_is_verified');
            $table->dropColumn('filter_has_video');
            $table->dropColumn('filter_is_cheapest');
        });
    }
};
