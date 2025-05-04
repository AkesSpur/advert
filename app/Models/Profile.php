<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Add these to the $fillable array
    protected $fillable = [
        'user_id',
        'phone',
        'description',
        'name',
        'age',
        'weight',
        'height',
        'size',
        'bio',
        'is_active',
        'hair_color',
        'tattoo',
        'telegram',
        'viber',
        'whatsapp',
        'email',
        'payment_wmz',
        'payment_card',
        'payment_sbp',
        'profile_type',
        'has_telegram',
        'has_viber',
        'has_whatsapp',
        'vyezd',
        'appartamenti',
        'vyezd_1hour',
        'vyezd_2hours',
        'vyezd_night',
        'appartamenti_1hour',
        'appartamenti_2hours',
        'appartamenti_night',
        'view_count',
        'click_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'age' => 'integer',
        'weight' => 'integer',
        'height' => 'integer',
        'payment_wmz' => 'boolean',
        'payment_card' => 'boolean',
        'payment_sbp' => 'boolean',
        'has_telegram' => 'boolean',
        'has_viber' => 'boolean',
        'has_whatsapp' => 'boolean',
        'vyezd' => 'boolean',
        'appartamenti' => 'boolean',
        'vyezd_1hour' => 'decimal:2',
        'vyezd_2hours' => 'decimal:2',
        'vyezd_night' => 'decimal:2',
        'appartamenti_1hour' => 'decimal:2',
        'appartamenti_2hours' => 'decimal:2',
        'appartamenti_night' => 'decimal:2',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The services that belong to the profile.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }
    
    /**
     * The neighborhoods that belong to the profile.
     */
    public function neighborhoods(): BelongsToMany
    {
        return $this->belongsToMany(Neighborhood::class);
    }
    
    /**
     * The metro stations that belong to the profile.
     */
    public function metroStations(): BelongsToMany
    {
        return $this->belongsToMany(MetroStation::class);
    }
    
    /**
     * The paid services that belong to the profile.
     */
    public function paidServices(): BelongsToMany
    {
        return $this->belongsToMany(PaidService::class)
            ->withPivot('price')
            ->withTimestamps();
    }

    /**
     * Get the reviews for the profile.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the comments for the profile.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the average rating of the profile.
     */
    public function getAverageRatingAttribute()
    {
        if ($this->reviews->count() === 0) {
            return null;
        }
        
        return $this->reviews->avg('rating');
    }
    
    /**
     * Get the images for the profile.
     */
    public function images()
    {
        return $this->hasMany(ProfileImage::class)->orderBy('sort_order');
    }
    
    /**
     * Get the primary image for the profile.
     */
    public function primaryImage()
    {
        return $this->hasOne(ProfileImage::class)->where('is_primary', true);
    }
    
    /**
     * Get the video for the profile.
     */
    public function video()
    {
        return $this->hasOne(ProfileVideo::class);
    }
    
    /**
     * Get the active advertisement tariffs for this profile
     */
    public function activeAds()
    {
        return $this->hasMany(ProfileAdTariff::class)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Increment the view count for this profile
     */
    public function incrementViewCount()
    {
        $this->increment('views_count');
    }

    /**
     * Increment the click count for this profile
     */
    public function incrementClickCount()
    {
        $this->increment('clicks_count');
    }

    /**
     * Check if profile has an active VIP tariff
     */
    public function hasActiveVipTariff()
    {
        return $this->activeTariffs()
            ->where('ad_tariff_id', function ($query) {
                $query->select('id')
                    ->from('ad_tariffs')
                    ->where('name', 'VIP');
            })
            ->exists();
    }

    // Add a scope to check if profile is VIP
    public function scopeIsVip($query)
    {
        return $query->where('is_vip', true);
    }

    // Add a scope to check if profile has video
    public function scopeHasVideo($query)
    {
        return $query->whereHas('video', function ($q) {
            $q->whereNotNull('path');
        });
    }
    

    // Add a scope to get new profiles (created in the last 7 days)
    public function scopeIsNew($query)
    {
        return $query->where('profiles.created_at', '>=', now()->subDays(7));
    }

    // Add a scope to get cheap profiles (price below a certain threshold)
    public function scopeIsCheap($query, $threshold = 5000)
    {
        return $query->where('vyezd_1hour', '<=', $threshold);
    }

    // Add a scope to get verified profiles
    public function scopeIsVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
