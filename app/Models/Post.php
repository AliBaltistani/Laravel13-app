<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasSlug, SoftDeletes;

    protected $fillable = [
        'user_id', 'post_category_id', 'title', 'slug', 'excerpt', 'content',
        'image', 'is_published', 'published_at', 'meta_title', 'meta_description',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'views_count' => 'integer',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(PostComment::class)->where('is_approved', true);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('published_at', '<=', now());
    }

    // Alias for eager loading compatibility
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function postCategory(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    /**
     * Generate Article JSON-LD structured data (Phase 9-B).
     */
    public function jsonLd(): array
    {
        $siteName = Setting::get('general.site_name', config('app.name'));

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $this->title,
            'description' => $this->excerpt ?? strip_tags(substr($this->content ?? '', 0, 160)),
            'url' => route('blog.show', $this->slug),
            'datePublished' => $this->published_at?->toIso8601String(),
            'dateModified' => $this->updated_at?->toIso8601String(),
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
                'logo' => Setting::get('general.logo') ? asset('storage/' . Setting::get('general.logo')) : '',
            ],
        ];

        if ($this->user) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => $this->user->name,
            ];
        }

        if ($this->image) {
            $schema['image'] = asset('storage/' . $this->image);
        }

        return $schema;
    }
}
