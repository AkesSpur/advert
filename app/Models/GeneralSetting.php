<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'cheap_threshold',
        'profiles_per_page',
        'default_h1_heading',
        'default_seo_title',
        'default_seo_description',
        // Add any other pre-existing fields from the old general_settings table here if needed
        // For example: 'contact_email', 'contact_phone', 'contact_address', 'currency_name', 'currency_icon'
    ];

    // If you have specific casts for these new fields, define them here
    // protected $casts = [
    //     'cheap_threshold' => 'integer',
    //     'profiles_per_page' => 'integer',
    // ];
}