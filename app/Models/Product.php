<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasSlug, SoftDeletes;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'sku',
        'short_description', 'description', 'price', 'compare_price', 'cost_price',
        'weight', 'type', 'is_active', 'is_featured', 'is_new',
        'manage_stock', 'stock_quantity', 'low_stock_threshold', 'allow_backorder',
        'sold_count', 'view_count', 'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'manage_stock' => 'boolean',
            'allow_backorder' => 'boolean',
            'stock_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
            'sold_count' => 'integer',
            'view_count' => 'integer',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute');
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'related_products', 'product_id', 'related_product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function flashSaleProducts(): HasMany
    {
        return $this->hasMany(FlashSaleProduct::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeIsNew($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('manage_stock', false)
              ->orWhere('stock_quantity', '>', 0)
              ->orWhere('allow_backorder', true);
        });
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('compare_price')
            ->whereColumn('compare_price', '>', 'price');
    }

    // Helpers
    public function getMainImageAttribute()
    {
        return $this->images->where('is_primary', true)->first()
            ?? $this->images->first();
    }

    public function effectivePrice(): float
    {
        // Check for active flash sale
        $flashSaleProduct = $this->flashSaleProducts()
            ->whereHas('flashSale', function ($q) {
                $q->where('is_active', true)
                  ->where('starts_at', '<=', now())
                  ->where('expires_at', '>=', now());
            })
            ->first();

        if ($flashSaleProduct) {
            return (float) $flashSaleProduct->sale_price;
        }

        return (float) $this->price;
    }

    public function activeFlashSale()
    {
        return $this->flashSaleProducts()
            ->whereHas('flashSale', function ($q) {
                $q->where('is_active', true)
                  ->where('starts_at', '<=', now())
                  ->where('expires_at', '>=', now());
            })
            ->first();
    }

    public function isInStock(): bool
    {
        if (!$this->manage_stock) {
            return true;
        }
        return $this->stock_quantity > 0 || $this->allow_backorder;
    }

    public function averageRating(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    public function reviewCount(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Generate Product JSON-LD structured data (Phase 9-B).
     */
    public function jsonLd(): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $this->name,
            'description' => strip_tags($this->short_description ?? $this->description ?? ''),
            'sku' => $this->sku,
            'url' => route('product.show', $this->slug),
            'offers' => [
                '@type' => 'Offer',
                'price' => number_format($this->effectivePrice(), 2, '.', ''),
                'priceCurrency' => 'USD',
                'availability' => $this->isInStock()
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'url' => route('product.show', $this->slug),
            ],
        ];

        // Brand
        if ($this->brand) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name' => $this->brand->name,
            ];
        }

        // Image
        $mainImage = $this->mainImage;
        if ($mainImage) {
            $schema['image'] = asset('storage/' . $mainImage->image_path);
        }

        // Aggregate rating
        $reviewCount = $this->reviewCount();
        if ($reviewCount > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $this->averageRating(),
                'reviewCount' => $reviewCount,
                'bestRating' => 5,
                'worstRating' => 1,
            ];
        }

        return $schema;
    }
}
