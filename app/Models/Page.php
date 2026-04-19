<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model
{
    use HasSlug;

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'image',
        'banner_image', 'video_url', 'video_file',
        'meta_title', 'meta_description', 'is_active', 'sort_order', 'template',
        'custom_css', 'custom_js', 'layout', 'show_sidebar', 'sidebar_content',
        'show_in_header', 'show_in_footer',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'show_sidebar' => 'boolean',
            'show_in_header' => 'boolean',
            'show_in_footer' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeShowInHeader($query)
    {
        return $query->where('show_in_header', true);
    }

    public function scopeShowInFooter($query)
    {
        return $query->where('show_in_footer', true);
    }

    /**
     * Get the correct frontend URL for this page.
     */
    public function getFrontendUrlAttribute(): string
    {
        return match ($this->slug) {
            'about-us' => url('/about'),
            'contact' => url('/contact'),
            'terms-conditions' => url('/terms'),
            'privacy-policy' => url('/privacy'),
            default => route('page.show', $this->slug),
        };
    }

    /**
     * Gallery images for this page.
     */
    public function images(): HasMany
    {
        return $this->hasMany(PageImage::class)->orderBy('sort_order');
    }

    /**
     * Content sections for this page.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    /**
     * Active content sections.
     */
    public function activeSections(): HasMany
    {
        return $this->hasMany(PageSection::class)->where('is_active', true)->orderBy('sort_order');
    }
}
