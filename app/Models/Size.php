<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $table = 'sizes';

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
     * Get the size's hero section override.
     */
    public function heroSectionOverride(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(HeroSectionOverride::class, 'overridable');
    }
}