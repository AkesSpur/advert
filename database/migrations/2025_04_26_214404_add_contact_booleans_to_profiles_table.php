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
            $table->boolean('has_telegram')->default(false);
            $table->boolean('has_viber')->default(false);
            $table->boolean('has_whatsapp')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('has_telegram');
            $table->dropColumn('has_viber');
            $table->dropColumn('has_whatsapp');
        });
    }
};
