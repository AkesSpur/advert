<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_type',
        'title_template',
        'meta_description_template',
        'h1_template',
        'city_override', // Add city_override here
    ];

    /**
     * Replace placeholders in the template string with actual data.
     *
     * @param string|null $template
     * @param Profile $profile
     * @return string|null
     */
    public function replacePlaceholders(?string $template, Profile $profile): ?string
    {
        if (is_null($template)) {
            return null;
        }

        // Basic profile attributes
        $replacements = [
            '{имя}' => $profile->name,
            '{возраст}' => $profile->age,
            '{цвет_волос}' => $profile->hair_color,
            '{город}' => $this->city_override ?: 'St. Petersburg', // Use city_override or default
            '{тип_профиля}' => $profile->profile_type == 'individual' ? 'Индивидуалка' : 'Интим-салон',
            '{описание}' => $profile->description,
            '{телефон}' => formatNumber($profile->phone),
            '{вес}' => $profile->weight,
            '{высота}' => $profile->height,
            '{грудь}' => $profile->size,
        ];

        // Metro stations (comma-separated string)
        $metroStations = $profile->metroStations->pluck('name')->implode(', ');
        $replacements['{метро}'] = $metroStations ?: 'N/A';

        // Neighborhoods (comma-separated string)
        $neighborhoods = $profile->neighborhoods->pluck('name')->implode(', ');
        $replacements['{район}'] = $neighborhoods ?: 'N/A'; // Using {district} for neighborhoods as per example

        // Services (comma-separated string)
        $services = $profile->services->pluck('name')->implode(', ');
        $replacements['{услуги}'] = $services ?: 'N/A';
        
        // Prices - example for one price, can be expanded
        $replacements['{цена_1_часа_апартаментов}'] = round($profile->appartamenti_1hour) ?: 'N/A';
        $replacements['{цена_1_час_отъезда}'] = round($profile->vyezd_1hour) ?: 'N/A';


        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
}