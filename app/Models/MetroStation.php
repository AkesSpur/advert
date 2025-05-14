<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'city_id',
        'is_active',
        'title',
        'meta_description',
        'h1_header',
        'sort_order',
    ];

    public function city()
    {
        return $this->belongsToMany(Profile::class);
    }
}