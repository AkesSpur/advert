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
        Schema::create('top_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('all_accounts')->default(false);
            $table->boolean('has_video')->default(false);
            $table->boolean('verified')->default(false);
            $table->boolean('vip')->default(false);
            $table->boolean('cheapest')->default(false);
            $table->boolean('new')->default(false);
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_menus');
    }
};
