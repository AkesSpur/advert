<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_categories', function (Blueprint $table) {
            $table->boolean('show_in_top_menu')->default(true)->after('status');
            $table->boolean('show_in_footer_menu')->default(true)->after('show_in_top_menu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_categories', function (Blueprint $table) {
            $table->dropColumn('show_in_top_menu');
            $table->dropColumn('show_in_footer_menu');
        });
    }
};