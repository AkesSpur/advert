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
            // Add hair color field
            $table->string('hair_color')->nullable();
            
            // Add tattoo field
            $table->string('tattoo')->nullable();
            
            // Add contact methods
            $table->string('telegram')->nullable();
            $table->string('viber')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            
            // Add payment methods
            $table->boolean('payment_wmz')->default(false);
            $table->boolean('payment_card')->default(false);
            $table->boolean('payment_sbp')->default(false);
            
            // Add pricing fields
            $table->json('pricing')->nullable();
            
            // Add profile type field
            $table->enum('profile_type', ['individual', 'salon'])->default('individual');

            // Add vyezd and appartamenti boolean fields
            $table->boolean('vyezd')->default(false);
            $table->boolean('appartamenti')->default(false);

            // Add vyezd pricing fields
            $table->decimal('vyezd_1hour', 10, 2)->nullable();
            $table->decimal('vyezd_2hours', 10, 2)->nullable();
            $table->decimal('vyezd_night', 10, 2)->nullable();

            // Add appartamenti pricing fields
            $table->decimal('appartamenti_1hour', 10, 2)->nullable();
            $table->decimal('appartamenti_2hours', 10, 2)->nullable();
            $table->decimal('appartamenti_night', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Remove all added columns
            $table->dropColumn([
                'hair_color',
                'tattoo',
                'telegram',
                'viber',
                'whatsapp',
                'email',
                'payment_wmz',
                'payment_card',
                'payment_sbp',
                'pricing',
                'profile_type',
                'vyezd',
                'appartamenti',
                'vyezd_1hour',
                'vyezd_2hours',
                'vyezd_night',
                'appartamenti_1hour',
                'appartamenti_2hours',
                'appartamenti_night'
            ]);
        });
    }
};
