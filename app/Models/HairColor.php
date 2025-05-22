<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HairColor extends Model
{
    use HasFactory;

    protected $table = 'hair_colors';

    protected $fillable = [
        'name',
        'value',
        'title',
        'meta_description',
        'h1_header',
        'sort_order',
        'status'
    ];

    /**
     * Get the hair color's hero section override.
     */
    public function heroSectionOverride(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(HeroSectionOverride::class, 'overridable');
    }
}