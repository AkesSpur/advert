<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_id',
        'name',
        'content',
    ];

    /**
     * Get the profile that owns the comment.
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}