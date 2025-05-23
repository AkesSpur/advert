<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdTariff extends Model
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
        'description',
        'base_price',
        'billing_period',
        'is_active',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Get the profile tariffs for this ad tariff
     */
    public function profileTariffs(): HasMany
    {
        return $this->hasMany(ProfileAdTariff::class);
    }

    /**
     * Get the daily price for a specific priority level
     */
    public function getPriceForPriority(int $priorityLevel): float
    {
        // For Priority tariff, add the priority level to the base price
        if ($this->slug == 'priority') {
            return $this->base_price + $priorityLevel;
        }
        
        return $this->base_price;
    }

}