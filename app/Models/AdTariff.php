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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
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
        if ($this->slug === 'priority') {
            return $this->base_price + $priorityLevel;
        }
        
        return $this->base_price;
    }

    /**
     * Get the price for a specific duration (for VIP tariff)
     */
    public function getPriceForDuration(string $duration): float
    {
        if ($this->slug !== 'vip') {
            return $this->base_price;
        }

        // Calculate price based on duration
        return match ($duration) {
            '1_day' => $this->base_price,
            '1_week' => $this->base_price * 7 * 0.9, // 10% discount for week
            '1_month' => $this->base_price * 30 * 0.8, // 20% discount for month
            default => $this->base_price,
        };
    }
}