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
        if (!Schema::hasTable('general_settings')) {
            Schema::create('general_settings', function (Blueprint $table) {
                $table->id();
                $table->string('site_name')->nullable();
                $table->integer('cheap_threshold')->nullable();
                $table->integer('profiles_per_page')->nullable();
                $table->string('default_h1_heading')->nullable();
                $table->string('default_seo_title')->nullable();
                $table->text('default_seo_description')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('general_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('general_settings', 'site_name')) {
                    $table->string('site_name')->nullable()->after('id');
                }
                if (!Schema::hasColumn('general_settings', 'cheap_threshold')) {
                    $table->integer('cheap_threshold')->nullable();
                }
                if (!Schema::hasColumn('general_settings', 'profiles_per_page')) {
                    $table->integer('profiles_per_page')->nullable();
                }
                if (!Schema::hasColumn('general_settings', 'default_h1_heading')) {
                    $table->string('default_h1_heading')->nullable();
                }
                if (!Schema::hasColumn('general_settings', 'default_seo_title')) {
                    $table->string('default_seo_title')->nullable();
                }
                if (!Schema::hasColumn('general_settings', 'default_seo_description')) {
                    $table->text('default_seo_description')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            // To make it truly reversible, we'd need to know the original state.
            // For now, we'll just drop the columns we're confident we added or modified for this task.
            // If the table was created by this migration, it will be dropped by the framework if 'create' was used.
            // However, since we use `if (!Schema::hasTable())`, a simple drop might be too aggressive if the table existed with other columns.
            $columnsToDrop = ['cheap_threshold', 'profiles_per_page', 'default_h1_heading', 'default_seo_title', 'default_seo_description'];
            // We won't drop site_name as it's a common field that might have existed.
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('general_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};