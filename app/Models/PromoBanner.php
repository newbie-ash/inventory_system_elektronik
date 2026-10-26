<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag',
        'title',
        'description',
        'image',
        'button_text',
        'button_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get accessible banner image URL or null.
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return null;
    }

    /**
     * Get the currently active banner for display.
     */
    public static function getActiveBanner(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }
}
