<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_active',
        // 'role',
        'balance',
        // 'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'balance' => 'decimal:2',
   ];

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role == 'admin';
    }

    /**
     * Update the last_active timestamp for the user.
     *
     * @return void
     */
    public function updateLastActive(): void
    {
        $this->last_active = Carbon::now();
        $this->save();
    }

    /**
     * Get the profile associated with the user
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }

    /**
     * Get the messages sent by the user
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    /**
     * Get the messages received by the user
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'recipient_id');
    }
    
    /**
     * Get the conversations that the user participates in as a regular user
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_id');
    }
    
    /**
     * Get the conversations that the user participates in as an admin
     */
    public function adminConversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'admin_id');
    }

    /**
     * Get the transactions associated with the user
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the advertisements associated with the user
     */
    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    /**
     * Get the reviews created by the user
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function likedProfiles()
    {
        return $this->belongsToMany(Profile::class, 'likes')->withTimestamps();
    }

}
