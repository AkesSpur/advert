<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Neighborhood extends Model
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
        'city_id',
        'is_active',
        'title',
        'meta_description',
        'h1_header',
        'sort_order',
    ];

    
    /**
     * The profiles that belong to the neighborhood.
     */
    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class);
    }
}