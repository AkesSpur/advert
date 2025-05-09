<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationPhoto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_id',
        'photo_path',
        'status', // pending, approved, rejected
    ];

    /**
     * Get the profile that owns the verification photo.
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}