<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

/**
 * ProductObserver — Phase 9-F
 * Clears caches when products are modified.
 */
class ProductObserver
{
    public function saved(Product $product): void
    {
        $this->clearCaches($product);
    }

    public function deleted(Product $product): void
    {
        $this->clearCaches($product);
    }

    private function clearCaches(Product $product): void
    {
        Cache::forget('sitemap_xml');
        Cache::forget('home_featured_products');
        Cache::forget('home_new_arrivals');
        Cache::forget('home_flash_sale');

        // Clear category-specific cache if applicable
        if ($product->category_id) {
            Cache::forget("category_products_{$product->category_id}");
        }
    }
}
