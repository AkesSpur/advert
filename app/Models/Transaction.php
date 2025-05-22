<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'original_payment_amount',
        'original_payment_currency',
        'type', // Could be 'deposit', 'withdrawal', 'payment', 'refund', etc.
        'status', // e.g., 'pending', 'completed', 'failed', 'cancelled'
        'reference_id', // For internal reference, if any
        'description',
        'payment_id', // External payment gateway's transaction ID before payment (e.g. LMI_PAYMENT_NO)
        'currency', // e.g., 'RUB', 'USD'
        'payment_method', // e.g., 'WebMoney', 'Card', 'Crypto'
        'payment_system_trans_id', // External payment gateway's transaction ID after payment (e.g. LMI_SYS_TRANS_NO)
        'paid_at', // Timestamp when the payment was confirmed
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'original_payment_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the user that owns the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
