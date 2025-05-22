<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class MetroStation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'title',
        'meta_description',
        'h1_header',
        'status',
        
    ];

    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class);
    }

    /**
     * Get the metro station's hero section override.
     */
    public function heroSectionOverride(): MorphOne
    {
        return $this->morphOne(HeroSectionOverride::class, 'overridable');
    }
}