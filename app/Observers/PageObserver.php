<?php

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;

/**
 * PageObserver — Phase 9-F
 * Clears sitemap cache when CMS pages change.
 */
class PageObserver
{
    public function saved(Page $page): void
    {
        Cache::forget('sitemap_xml');
    }

    public function deleted(Page $page): void
    {
        Cache::forget('sitemap_xml');
    }
}
