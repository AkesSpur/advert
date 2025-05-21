<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_templates', function (Blueprint $table) {
            $table->id();
            $table->string('page_type')->default('profile'); // e.g., 'profile', 'category'
            $table->string('title_template')->nullable();
            $table->text('meta_description_template')->nullable();
            $table->string('h1_template')->nullable();
            $table->timestamps();
        });

        // Seed with default profile template
        DB::table('seo_templates')->insert([
            'page_type' => 'profile',
            'title_template' => 'Меня зовут {имя}, мне {возраст}, у меня {цвет_волос}, я нахожусь рядом с метро {метро}, это {район} район Санкт-Петербурга.',
            'meta_description_template' => 'Подробная информация о {имя}: {возраст} лет, {цвет волос} волос, находится возле станции метро {метро} в {район} районе. Услуги включают: {услуги}.',
            'h1_template' => '{имя}, {возраст} - {тип_профиля} in {город}',
            'city_override' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seo_templates');
    }
};