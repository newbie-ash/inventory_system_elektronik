<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'purchase_price',
        'selling_price',
        'price',
        'stock',
        'minimum_stock',
        'min_stock',
    ];

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
     * Get inventory valuation based on purchase cost.
     */
    public function getInventoryValueAttribute(): float
    {
        $cost = (float) ($this->purchase_price > 0 ? $this->purchase_price : $this->price);

        return (float) ($this->stock * $cost);
    }
}
