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
        // Create neighborhoods table
        Schema::create('neighborhoods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('city')->nullable();
            $table->timestamps();
        });

        // Create metro stations table
        Schema::create('metro_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('line')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });

        // Create paid services table
        Schema::create('paid_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('default_price', 10, 2)->nullable();
            $table->timestamps();
        });

        // Create pivot table for profiles and neighborhoods
        Schema::create('neighborhood_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('neighborhood_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['profile_id', 'neighborhood_id']);
        });

        // Create pivot table for profiles and metro stations
        Schema::create('metro_station_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('metro_station_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['profile_id', 'metro_station_id']);
        });

        // Create pivot table for profiles and paid services with custom price
        Schema::create('paid_service_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('paid_service_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['profile_id', 'paid_service_id']);
        });

        // Update profiles table to remove single district and metro_station fields
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['district', 'metro_station']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the columns we removed
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('district')->nullable();
            $table->string('metro_station')->nullable();
        });

        Schema::dropIfExists('paid_service_profile');
        Schema::dropIfExists('metro_station_profile');
        Schema::dropIfExists('neighborhood_profile');
        Schema::dropIfExists('paid_services');
        Schema::dropIfExists('metro_stations');
        Schema::dropIfExists('neighborhoods');
    }
};