<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSectionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text_content',
        'image',
    ];

    /**
     * Get the hero section override for the setting.
     */
    public function heroSectionOverride()
    {
        return $this->morphOne(HeroSectionOverride::class, 'overridable');
    }
    // public function heroSectionOverride()
    // {
    //     return $this->morphOne(HeroSectionOverride::class, 'overridable');
    // }
}