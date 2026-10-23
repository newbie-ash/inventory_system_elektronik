<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buyer_id',
        'sale_number',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'snap_token',
        'notes',
        'sold_at',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'sold_at' => 'datetime',
        ];
    }

    /**
     * User who created the transaction (staff/admin or buyer self-checkout).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Customer account linked to this sale (nullable).
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Detailed item rows for this sale.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Check if payment is completed.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
