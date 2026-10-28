<?php

namespace App\Models;

use App\Notifications\LowStockNotification;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'image',
        'purchase_price',
        'selling_price',
        'price',
        'stock',
        'minimum_stock',
        'min_stock',
    ];

    /**
     * Get accessible photo URL or null (prefers transparent PNG if available).
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }

            $activeDisk = config('filesystems.default');
            if (in_array($activeDisk, ['s3', 'r2', 'public']) && Storage::disk($activeDisk)->exists($this->image)) {
                return Storage::disk($activeDisk)->url($this->image);
            }

            $pngCandidate = preg_replace('/\.jpe?g$/i', '.png', $this->image);
            if (file_exists(public_path($pngCandidate))) {
                return asset($pngCandidate);
            }
            if (file_exists(public_path($this->image))) {
                return asset($this->image);
            }
        }

        if ($this->sku) {
            $skuAsset = 'images/products/'.strtolower($this->sku).'.png';
            if (file_exists(public_path($skuAsset))) {
                return asset($skuAsset);
            }
        }

        if ($this->name) {
            $slugAsset = 'images/products/'.Str::slug($this->name).'.png';
            if (file_exists(public_path($slugAsset))) {
                return asset($slugAsset);
            }
        }

        return null;
    }

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'stock' => 'integer',
            'minimum_stock' => 'integer',
        ];
    }

    /**
     * Backward-compatibility accessor for legacy 'price'.
     */
    public function getPriceAttribute(): float
    {
        if (isset($this->attributes['selling_price']) && (float) $this->attributes['selling_price'] > 0) {
            return (float) $this->attributes['selling_price'];
        }

        return (float) ($this->attributes['price'] ?? 0);
    }

    /**
     * Backward-compatibility mutator for legacy 'price'.
     */
    public function setPriceAttribute($value): void
    {
        $this->attributes['price'] = $value;
        if (! isset($this->attributes['selling_price']) || (float) $this->attributes['selling_price'] === 0.0) {
            $this->attributes['selling_price'] = $value;
        }
    }

    /**
     * Backward-compatibility accessor for legacy 'min_stock'.
     */
    public function getMinStockAttribute(): int
    {
        if (isset($this->attributes['minimum_stock'])) {
            return (int) $this->attributes['minimum_stock'];
        }

        return (int) ($this->attributes['min_stock'] ?? 5);
    }

    /**
     * Backward-compatibility mutator for legacy 'min_stock'.
     */
    public function setMinStockAttribute($value): void
    {
        $this->attributes['min_stock'] = $value;
        $this->attributes['minimum_stock'] = $value;
    }

    /**
     * Category relation (One-to-Many).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Suppliers relation (Many-to-Many).
     */
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
            ->withPivot('supplier_price')
            ->withTimestamps();
    }

    /**
     * Purchase items relation (One-to-Many).
     */
    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Sale items relation (One-to-Many).
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Stock movements history (One-to-Many).
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Scope for low stock products.
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereColumn('stock', '<=', 'minimum_stock')
                ->orWhereColumn('stock', '<=', 'min_stock');
        });
    }

    /**
     * Scope for buyer catalog (in-stock only).
     */
    public function scopeAvailableForBuyer(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Check if product stock is at or below minimum threshold.
     */
    public function isLowStock(): bool
    {
        $min = $this->minimum_stock ?? $this->min_stock ?? 5;

        return $this->stock <= $min;
    }

    /**
     * Dispatch low stock alert notification to Admin & Staff if below threshold.
     */
    public function notifyIfLowStock(): bool
    {
        if (! $this->isLowStock()) {
            return false;
        }

        $recipients = User::whereIn('role', ['admin', 'staff'])->get();
        if ($recipients->isEmpty()) {
            return false;
        }

        $notified = false;
        foreach ($recipients as $recipient) {
            $hasUnread = $recipient->unreadNotifications()
                ->where('type', LowStockNotification::class)
                ->where('data->product_id', $this->id)
                ->exists();

            if (! $hasUnread) {
                $recipient->notify(new LowStockNotification($this));
                $notified = true;
            }
        }

        return $notified;
    }

    /**
     * Get inventory valuation based on purchase cost.
     */
    public function getInventoryValueAttribute(): float
    {
        $cost = (float) ($this->purchase_price > 0 ? $this->purchase_price : $this->price);

        return (float) ($this->stock * $cost);
    }

    /**
     * Customer reviews and ratings.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * Average rating out of 5.
     */
    public function averageRating(): float
    {
        return round((float) ($this->reviews()->avg('rating') ?? 0.0), 1);
    }

    /**
     * Total review count.
     */
    public function reviewsCount(): int
    {
        return $this->reviews()->count();
    }
}
