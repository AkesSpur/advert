<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Age extends Model
{
    use HasFactory;

    protected $table = 'ages';

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
     * Get the age's hero section override.
     */
    public function heroSectionOverride(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(HeroSectionOverride::class, 'overridable');
    }
}