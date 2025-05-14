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
        Schema::create('hair_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value')->unique(); // Fixed value for backend logic
            $table->boolean('status')->default(true);
            $table->string('title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('h1_header')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('hair_colors')->insert([
            ['name' => 'Блондинки', 'value' => 'blondes', 'sort_order' => 1],
            ['name' => 'Брюнетки', 'value' => 'brunettes', 'sort_order' => 2],
            ['name' => 'Рыжие', 'value' => 'redheads', 'sort_order' => 3],
            ['name' => 'Русые', 'value' => 'Wolverines', 'sort_order' => 4],
            ['name' => 'Шатенки', 'value' => 'brown-haired', 'sort_order' => 5],
        ]);

        Schema::create('heights', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value')->unique();
            $table->boolean('status')->default(true);
            $table->string('title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('h1_header')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('heights')->insert([
            ['name' => 'Маленькие (до 150 см)', 'value' => 'height-under-150', 'sort_order' => 1],
            ['name' => 'Низкие (до 165 см)', 'value' => 'height-under-165', 'sort_order' => 2],
            ['name' => '165-180 см', 'value' => 'height-165-180', 'sort_order' => 3],
            ['name' => 'Высокие (180+ см)', 'value' => 'height-over-180', 'sort_order' => 4],
        ]);

        Schema::create('weights', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value')->unique();
            $table->boolean('status')->default(true);
            $table->string('title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('h1_header')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('weights')->insert([
            ['name' => 'Тощие (до 45 кг)', 'value' => 'weight-under-45', 'sort_order' => 1],
            ['name' => 'Худые (до 50 кг)', 'value' => 'weight-under-50', 'sort_order' => 2],
            ['name' => 'Пышные (50-65 кг)', 'value' => 'weight-50-65', 'sort_order' => 3],
            ['name' => 'Толстые (65+ кг)', 'value' => 'weight-over-65', 'sort_order' => 4],
        ]);

        Schema::create('sizes', function (Blueprint $table) { // Breast sizes
            $table->id();
            $table->string('name');
            $table->string('value')->unique();
            $table->boolean('status')->default(true);
            $table->string('title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('h1_header')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('sizes')->insert([
            ['name' => 'Маленькая (до 1)', 'value' => 'size-under-1', 'sort_order' => 1],
            ['name' => 'Грудь 1-2', 'value' => 'size-1-2', 'sort_order' => 2],
            ['name' => 'Грудь 2-3', 'value' => 'size-2-3', 'sort_order' => 3],
            ['name' => 'Большая (3+)', 'value' => 'size-over-3', 'sort_order' => 4],
        ]);

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value')->unique();
            $table->boolean('status')->default(true);
            $table->string('title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('h1_header')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('prices')->insert([
            ['name' => 'Дешевые (до 2000 рублей в час)', 'value' => 'price-under-2000', 'sort_order' => 1],
            ['name' => 'За 1500 руб.', 'value' => 'price-1500', 'sort_order' => 2],
            ['name' => 'До 2500 руб.', 'value' => 'price-under-2500', 'sort_order' => 3],
            ['name' => 'До 5000 руб.', 'value' => 'price-under-5000', 'sort_order' => 4],
            ['name' => 'До 8000 руб.', 'value' => 'price-under-8000', 'sort_order' => 5],
            ['name' => 'Дорогие (5000+ рублей в час)', 'value' => 'price-over-5000', 'sort_order' => 6],
            ['name' => 'Элитные (10000+ рублей в час)', 'value' => 'price-over-10000', 'sort_order' => 7],
            ['name' => '8000+ руб.', 'value' => 'price-over-8000', 'sort_order' => 8],
        ]);

        Schema::create('ages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value')->unique();
            $table->boolean('status')->default(true);
            $table->string('title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('h1_header')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('ages')->insert([
            ['name' => 'Молодые (до 22 лет)', 'value' => 'age-under-22', 'sort_order' => 1],
            ['name' => '18 лет', 'value' => 'age-18', 'sort_order' => 2],
            ['name' => 'до 20 лет', 'value' => 'age-under-20', 'sort_order' => 3],
            ['name' => 'до 25 лет', 'value' => 'age-under-25', 'sort_order' => 4],
            ['name' => 'до 30 лет', 'value' => 'age-under-30', 'sort_order' => 5],
            ['name' => '30-35 лет', 'value' => 'age-30-35', 'sort_order' => 6],
            ['name' => '35-40 лет', 'value' => 'age-35-40', 'sort_order' => 7],
            ['name' => 'Зрелые (28-40 лет)', 'value' => 'age-28-40', 'sort_order' => 8],
            ['name' => 'Старые (40+ лет)', 'value' => 'age-over-40', 'sort_order' => 9],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hair_colors');
        Schema::dropIfExists('heights');
        Schema::dropIfExists('weights');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('prices');
        Schema::dropIfExists('ages');
    }
};