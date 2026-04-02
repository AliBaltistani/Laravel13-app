<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class AttributeGroup extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug', 'display_type', 'sort_order'];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class)->orderBy('sort_order');
    }
}
