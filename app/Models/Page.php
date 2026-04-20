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
        'show_in_header', 'show_in_footer', 'header_label', 'footer_label', 'header_order', 'footer_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'show_sidebar' => 'boolean',
            'show_in_header' => 'boolean',
            'show_in_footer' => 'boolean',
            'sort_order' => 'integer',
            'header_order' => 'integer',
            'footer_order' => 'integer',
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

    public function scopeHeaderMenu($query)
    {
        return $query->where('show_in_header', true)->orderBy('header_order');
    }

    public function scopeFooterMenu($query)
    {
        return $query->where('show_in_footer', true)->orderBy('footer_order');
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
