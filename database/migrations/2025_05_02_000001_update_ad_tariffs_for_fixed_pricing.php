<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add fixed_price column to ad_tariffs table
        Schema::table('ad_tariffs', function (Blueprint $table) {
            $table->decimal('fixed_price', 10, 2)->nullable()->after('base_price');
            $table->decimal('weekly_price', 10, 2)->nullable()->after('fixed_price');
            $table->decimal('monthly_price', 10, 2)->nullable()->after('weekly_price');
        });

        // // Update existing VIP tariff to use fixed pricing
        // DB::table('ad_tariffs')
        //     ->where('slug', 'vip')
        //     ->update([
        //         'fixed_price' => DB::raw('base_price'),
        //         'weekly_price' => DB::raw('base_price * 7 * 0.9'), // 10% discount for week
        //         'monthly_price' => DB::raw('base_price * 30 * 0.8'), // 20% discount for month
        //         'billing_period' => 'fixed'
        //     ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove fixed pricing columns
        Schema::table('ad_tariffs', function (Blueprint $table) {
            $table->dropColumn(['fixed_price', 'weekly_price', 'monthly_price']);
        });

        // Revert VIP tariff billing period
        DB::table('ad_tariffs')
            ->where('slug', 'vip')
            ->update(['billing_period' => 'day']);
    }
};