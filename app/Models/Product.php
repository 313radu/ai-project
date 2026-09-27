<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'external_id',
        'slug',
        'name',
        'merchant',
        'network',
        'brand',
        'price',
        'old_price',
        'currency',
        'category',
        'subcategory',
        'image_url',
        'product_url',
        'affiliate_url',
        'description',
        'ean',
        'is_active',
        'source',
        'last_synced_at',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'old_price'      => 'decimal:2',
        'is_active'      => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    // Auto-generate slug from name if not set
    public static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . uniqid();
            }
        });
    }

    // Discount percentage helper
    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return (int) round((($this->old_price - $this->price) / $this->old_price) * 100);
        }
        return null;
    }
}
