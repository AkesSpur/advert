<?php

namespace Database\Seeders;

use Illuminate\Support\Str;

use App\Models\MetroStation;
use App\Models\Neighborhood;
use App\Models\PaidService;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationsAndServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed neighborhoods
        $neighborhoods = [
            ['name' => 'Адмиралтейский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Василеостровский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Выборгский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Калининский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Кировский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Колпинский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Красногвардейский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Красносельский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Кронштадтский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Курортный', 'city' => 'Санкт-Петербург'],
            ['name' => 'Московский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Невский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Петроградский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Петродворцовый', 'city' => 'Санкт-Петербург'],
            ['name' => 'Приморский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Пушкинский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Фрунзенский', 'city' => 'Санкт-Петербург'],
            ['name' => 'Центральный', 'city' => 'Санкт-Петербург'],
        ];

        foreach ($neighborhoods as $neighborhood) {
            $neighborhood['slug'] = Str::slug($neighborhood['name']);
            Neighborhood::create($neighborhood);
        }

        // Seed metro stations
        $metroStations = [
            ['name' => 'Площадь Ленина', 'line' => 'Красная линия', 'city' => 'Санкт-Петербург'],
            ['name' => 'Горьковская', 'line' => 'Зеленая линия', 'city' => 'Санкт-Петербург'],
            ['name' => 'Невский проспект', 'line' => 'Зеленая линия', 'city' => 'Санкт-Петербург'],
            ['name' => 'Сенная площадь', 'line' => 'Синяя линия', 'city' => 'Санкт-Петербург'],
            ['name' => 'Маяковская', 'line' => 'Зеленая линия', 'city' => 'Санкт-Петербург'],
            ['name' => 'Технологический институт', 'line' => 'Красная линия', 'city' => 'Санкт-Петербург'],
        ];

        foreach ($metroStations as $station) {
            $station['slug'] = Str::slug($station['name']);
            MetroStation::create($station);
        }

        // Seed paid services based on the provided images
        $paidServices = [
            ['name' => 'Классика', 'description' => 'Классический сервис', 'default_price' => null],
            ['name' => 'Эротический массаж', 'description' => 'Эротический массаж', 'default_price' => null],
            ['name' => 'Страпон', 'description' => 'Страпон', 'default_price' => null],
            ['name' => 'Массаж тайский', 'description' => 'Массаж тайский', 'default_price' => null],
            ['name' => 'Бандаж', 'description' => 'Бандаж', 'default_price' => null],
            ['name' => 'Копро (выдача)', 'description' => 'Копро (выдача)', 'default_price' => null],
            ['name' => 'Фистинг', 'description' => 'Фистинг', 'default_price' => null],
            ['name' => 'Окончание на лицо', 'description' => 'Окончание на лицо', 'default_price' => null],
            ['name' => 'Лесби шоу', 'description' => 'Лесби шоу', 'default_price' => null],
            ['name' => 'Минет с резинкой', 'description' => 'Минет с резинкой', 'default_price' => null],
            ['name' => 'Фото/видео съемка', 'description' => 'Фото/видео съемка', 'default_price' => null],
            ['name' => 'Ролевые игры', 'description' => 'Ролевые игры', 'default_price' => null],
            ['name' => 'Проф. массаж', 'description' => 'Профессиональный массаж', 'default_price' => null],
            ['name' => 'Минет без резинки', 'description' => 'Минет без резинки', 'default_price' => null],
            ['name' => 'Римминг', 'description' => 'Римминг', 'default_price' => null],
            ['name' => 'Фетиш', 'description' => 'Фетиш', 'default_price' => null],
            ['name' => 'Трамплинг', 'description' => 'Трамплинг', 'default_price' => null],
            ['name' => 'Госпожа', 'description' => 'Госпожа', 'default_price' => null],
            ['name' => 'Анал', 'description' => 'Анал', 'default_price' => null],
            ['name' => 'Фэйс-ситтинг', 'description' => 'Фэйс-ситтинг', 'default_price' => null],
            ['name' => 'Игрушки', 'description' => 'Игрушки', 'default_price' => null],
            ['name' => 'Массаж урологический', 'description' => 'Массаж урологический', 'default_price' => null],
            ['name' => 'Групповуха', 'description' => 'Групповуха', 'default_price' => null],
            ['name' => 'Трансы', 'description' => 'Трансы', 'default_price' => null],
            ['name' => 'Золотой дождь', 'description' => 'Золотой дождь', 'default_price' => null],
            ['name' => 'Стриптиз', 'description' => 'Стриптиз', 'default_price' => null],
            ['name' => 'Минет в автомобиле', 'description' => 'Минет в автомобиле', 'default_price' => null],
            ['name' => 'Анилингус', 'description' => 'Анилингус', 'default_price' => null],
        ];
        
        // Note: default_price is set to null as the price will be set by users in the pivot table

        foreach ($paidServices as $service) {
            $service['slug'] = Str::slug($service['name']);
            PaidService::create($service);
        }

                // Seed services based on the provided images
                $services = [
                    ['name' => 'Классика', 'description' => 'Классический сервис'],
                    ['name' => 'Эротический массаж', 'description' => 'Эротический массаж'],
                    ['name' => 'Страпон', 'description' => 'Страпон'],
                    ['name' => 'Массаж тайский', 'description' => 'Массаж тайский'],
                    ['name' => 'Бандаж', 'description' => 'Бандаж'],
                    ['name' => 'Копро (выдача)', 'description' => 'Копро (выдача)'],
                    ['name' => 'Фистинг', 'description' => 'Фистинг'],
                    ['name' => 'Окончание на лицо', 'description' => 'Окончание на лицо'],
                    ['name' => 'Лесби шоу', 'description' => 'Лесби шоу'],
                    ['name' => 'Минет с резинкой', 'description' => 'Минет с резинкой'],
                    ['name' => 'Фото/видео съемка', 'description' => 'Фото/видео съемка'],
                    ['name' => 'Ролевые игры', 'description' => 'Ролевые игры'],
                    ['name' => 'Проф. массаж', 'description' => 'Профессиональный массаж'],
                    ['name' => 'Минет без резинки', 'description' => 'Минет без резинки'],
                    ['name' => 'Римминг', 'description' => 'Римминг'],
                    ['name' => 'Фетиш', 'description' => 'Фетиш'],
                    ['name' => 'Трамплинг', 'description' => 'Трамплинг'],
                    ['name' => 'Госпожа', 'description' => 'Госпожа'],
                    ['name' => 'Анал', 'description' => 'Анал'],
                    ['name' => 'Фэйс-ситтинг', 'description' => 'Фэйс-ситтинг'],
                    ['name' => 'Игрушки', 'description' => 'Игрушки'],
                    ['name' => 'Массаж урологический', 'description' => 'Массаж урологический'],
                    ['name' => 'Групповуха', 'description' => 'Групповуха'],
                    ['name' => 'Трансы', 'description' => 'Трансы'],
                    ['name' => 'Золотой дождь', 'description' => 'Золотой дождь'],
                    ['name' => 'Стриптиз', 'description' => 'Стриптиз'],
                    ['name' => 'Минет в автомобиле', 'description' => 'Минет в автомобиле'],
                    ['name' => 'Анилингус', 'description' => 'Анилингус'],
                ];

                foreach ($services as $service) {
                    $service['slug'] = Str::slug($service['name']);
                    Service::create($service);
                }
    }
}
