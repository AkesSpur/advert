<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
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
        'title', // Added
        'meta_description', // Added
        'h1_header', // Added
        'status'
    ];

    /**
     * The profiles that belong to the service.
     */
    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class);
    }

    /**
     * Get the service's hero section override.
     */
    public function heroSectionOverride(): MorphOne
    {
        return $this->morphOne(HeroSectionOverride::class, 'model');
    }
}
