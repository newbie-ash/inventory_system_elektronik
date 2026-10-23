<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'user_id',
        'purchase_number',
        'total',
        'status',
        'notes',
        'purchased_at',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'purchased_at' => 'datetime',
        ];
    }

    /**
     * Supplier who supplied the goods (Belongs-to).
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * User/Staff who recorded the purchase (Belongs-to).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Detailed item rows for this purchase (One-to-Many).
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
