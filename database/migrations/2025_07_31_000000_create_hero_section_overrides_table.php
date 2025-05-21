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
        Schema::create('hero_section_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // e.g., App\Models\Service, App\Models\CustomCategory
            $table->unsignedBigInteger('model_id');
            $table->string('title')->nullable();
            $table->text('text_content')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->comment('Stores custom hero section content for specific models');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_section_overrides');
    }
};