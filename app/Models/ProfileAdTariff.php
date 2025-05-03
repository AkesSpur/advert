<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class ProfileAdTariff extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_id',
        'ad_tariff_id',
        'starts_at',
        'expires_at',
        'is_active',
        'is_paused',
        'priority_level',
        'queue_position',
        'daily_charge',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_paused' => 'boolean',
        'priority_level' => 'integer',
        'queue_position' => 'integer',
        'daily_charge' => 'decimal:2',
    ];

    /**
     * Get the profile that owns this tariff
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Get the ad tariff for this profile tariff
     */
    public function adTariff(): BelongsTo
    {
        return $this->belongsTo(AdTariff::class);
    }

    /**
     * Get the charges for this profile tariff
     */
    public function charges(): HasMany
    {
        return $this->hasMany(AdTariffCharge::class);
    }

    /**
     * Check if the tariff is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Check if the tariff is a VIP tariff
     */
    public function isVip(): bool
    {
        return $this->adTariff->slug === 'vip';
    }

    /**
     * Check if the tariff is a Priority tariff
     */
    public function isPriority(): bool
    {
        return $this->adTariff->slug === 'priority';
    }

    /**
     * Check if the tariff is a Basic tariff
     */
    public function isBasic(): bool
    {
        return $this->adTariff->slug === 'basic';
    }

    /**
     * Pause the tariff
     */
    public function pause(): void
    {
        // VIP tariffs cannot be paused
        if ($this->isVip()) {
            return;
        }

        $this->is_paused = true;
        $this->save();
        
        // Update the profile's is_active status
        $this->profile->update(['is_active' => false]);
    }

    /**
     * Resume the tariff
     */
    public function resume(): void
    {
        $this->is_paused = false;
        $this->save();
        
        // Update the profile's is_active status
        $this->profile->update(['is_active' => true]);
    }

    /**
     * Deactivate the tariff
     */
    public function deactivate(): void
    {
        $this->is_active = false;
        $this->save();
        
        // Check if the profile has any other active tariffs before setting is_active to false
        $hasOtherActiveTariffs = $this->profile->activeAds()
            ->where('id', '!=', $this->id)
            ->where('is_active', true)
            ->where('is_paused', false)
            ->exists();
            
        if (!$hasOtherActiveTariffs) {
            // Only update the profile's is_active status if there are no other active tariffs
            $this->profile->update(['is_active' => false]);
        } else {
            // Ensure profile is_active is true if it has other active tariffs
            $this->profile->update(['is_active' => true]);
        }
    }

    public function deactivateBasic(): void
    {
        $this->is_active = false;
        $this->save();
    }


    /**
     * Get the remaining days for this tariff
     */
    public function getRemainingDays(): int
    {
        if (!$this->expires_at) {
            return 0;
        }

        return max(0, Carbon::now()->diffInDays($this->expires_at, false));
    }
}