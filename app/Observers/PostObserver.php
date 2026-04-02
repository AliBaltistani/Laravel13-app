<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

/**
 * PostObserver — Phase 9-F
 * Clears sitemap cache when blog posts change.
 */
class PostObserver
{
    public function saved(Post $post): void
    {
        Cache::forget('sitemap_xml');
    }

    public function deleted(Post $post): void
    {
        Cache::forget('sitemap_xml');
    }
}
