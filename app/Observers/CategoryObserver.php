<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

/**
 * CategoryObserver — Phase 9-F
 * Clears navigation and sitemap caches when categories change.
 */
class CategoryObserver
{
    public function saved(Category $category): void
    {
        $this->clearCaches();
    }

    public function deleted(Category $category): void
    {
        $this->clearCaches();
    }

    private function clearCaches(): void
    {
        Cache::forget('navigation_categories');
        Cache::forget('sitemap_xml');
        Cache::forget('home_category_icons');
    }
}
