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
        Schema::table('profiles', function (Blueprint $table) {
            // Remove the services JSON field as we're using a proper many-to-many relationship
            $table->dropColumn('services');
            
            // Remove the photo_path field as we're using the profile_images table
            $table->dropColumn('photo_path');
            
            // Ensure is_active field exists (it might already exist in some environments)
            if (!Schema::hasColumn('profiles', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Add back the columns we removed
            $table->json('services')->nullable();
            $table->string('photo_path')->nullable();
            
            // We don't remove is_active as it might have existed before
        });
    }
};