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
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('status')->default(true);
            $table->string('title')->nullable()->after('name');
            $table->text('meta_description')->nullable()->after('title');
            $table->string('h1_header')->nullable()->after('meta_description');
        });

        Schema::table('neighborhoods', function (Blueprint $table) {
            $table->boolean('status')->default(true);
            $table->string('title')->nullable()->after('name');
            $table->text('meta_description')->nullable()->after('title');
            $table->string('h1_header')->nullable()->after('meta_description');
        });

        Schema::table('metro_stations', function (Blueprint $table) {
            $table->boolean('status')->default(true);
            $table->string('title')->nullable()->after('name');
            $table->text('meta_description')->nullable()->after('title');
            $table->string('h1_header')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['title', 'meta_description', 'h1_header', 'status']);
        });

        Schema::table('neighborhoods', function (Blueprint $table) {
            $table->dropColumn(['title', 'meta_description', 'h1_header', 'status']);
        });

        Schema::table('metro_stations', function (Blueprint $table) {
            $table->dropColumn(['title', 'meta_description', 'h1_header', 'status']);
        });
    }
};