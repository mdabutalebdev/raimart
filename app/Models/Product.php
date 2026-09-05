<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'main_image',
        'short_description',
        'description',
        'price',
        'old_price',
        'stock',
        'sku',
        'weight',
        'rating',
        'reviews_count',
        'is_featured',
        'is_best_seller',
        'is_new_arrival',
        'is_top_selling',
        'top_selling_order',
        'is_active',
        'attributes',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'rating' => 'decimal:1',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_top_selling' => 'boolean',
            'is_active' => 'boolean',
            'attributes' => 'array',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function refreshReviewStats(): void
    {
        $approved = $this->reviews()->approved();
        $this->update([
            'rating' => round((float) $approved->avg('rating'), 1),
            'reviews_count' => (int) $approved->count(),
        ]);
    }

    public function getMainImageAttribute($value)
    {
        return $value ?? $this->images->first()?->image;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->old_price || $this->old_price <= $this->price) {
            return null;
        }

        return (int) round((($this->old_price - $this->price) / $this->old_price) * 100);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestSeller($query)
    {
        return $query->where('is_best_seller', true);
    }

    public function scopeNewArrival($query)
    {
        return $query->where('is_new_arrival', true);
    }

    public function scopeTopSelling($query)
    {
        return $query->where('is_top_selling', true);
    }
}
