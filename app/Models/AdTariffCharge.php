<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdTariffCharge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_ad_tariff_id',
        'user_id',
        'amount',
        'charged_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'charged_at' => 'datetime',
    ];

    /**
     * Get the profile tariff that owns this charge
     */
    public function profileTariff(): BelongsTo
    {
        return $this->belongsTo(ProfileAdTariff::class, 'profile_ad_tariff_id');
    }

    /**
     * Get the user that owns this charge
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}