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
        Schema::create('custom_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('title')->nullable()->comment('SEO title');
            $table->text('meta_description')->nullable()->comment('SEO meta description');
            $table->string('h1')->nullable()->comment('H1 header for the category page');
            $table->json('age_filters')->nullable()->comment('Array of age values');
            $table->json('weight_filters')->nullable()->comment('Array of weight values');
            $table->json('size_filters')->nullable()->comment('Array of size values');
            $table->json('hair_color_filters')->nullable()->comment('Array of hair color values');
            $table->json('price_filters')->nullable()->comment('Array of price range values');
            $table->json('height_filters')->nullable()->comment('Array of height values');
            $table->json('service_ids')->nullable()->comment('Array of service IDs');
            $table->json('metro_station_ids')->nullable()->comment('Array of metro station IDs');
            $table->json('neighborhood_ids')->nullable()->comment('Array of neighborhood IDs');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_categories');
    }
};