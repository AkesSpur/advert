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
        Schema::table('seo_templates', function (Blueprint $table) {
            $table->string('city_override')->nullable()->comment('Override for the {город} placeholder');
        });

        // DB::table('seo_templates')->where('id' == 1 )->update([
        //     'city_override' => null,
        // ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_templates', function (Blueprint $table) {
           $table->dropColumn('city_override');
        });
    }
};
