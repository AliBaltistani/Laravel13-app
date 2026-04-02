<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\SeoService;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with([
                'images',
                'variants.attributes.attributeGroup',
                'attributes.attributeGroup',
                'brand',
                'category',
                'tags',
                'flashSaleProducts.flashSale',
                'approvedReviews.user',
            ])
            ->firstOrFail();

        // SEO — Phase 9-A/9-B: Product-specific meta + JSON-LD
        $seo = app(SeoService::class);
        $mainImage = $product->mainImage;
        $seo->setTitle($product->meta_title ?: $product->name)
            ->setDescription($product->meta_description ?: strip_tags($product->short_description ?? ''))
            ->setCanonical(route('product.show', $product->slug))
            ->setJsonLd($product->jsonLd());

        if ($mainImage) {
            $seo->setImage(asset('storage/' . $mainImage->image_path));
        }

        // Increment view count
        $product->increment('view_count');

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $product->approvedReviews->where('rating', $i)->count();
        }
        $totalReviews = $product->approvedReviews->count();
        $avgRating = $totalReviews > 0 ? round($product->approvedReviews->avg('rating'), 1) : 0;

        // Related products
        $relatedProducts = $product->relatedProducts()->active()->with(['images', 'category'])->take(6)->get();
        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::active()
                ->where('id', '!=', $product->id)
                ->where('category_id', $product->category_id)
                ->with(['images', 'category'])
                ->take(6)
                ->get();
        }

        // Recently viewed - store in session
        $recentlyViewed = session('recently_viewed', []);
        $recentlyViewed = array_diff($recentlyViewed, [$product->id]);
        array_unshift($recentlyViewed, $product->id);
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);
        session(['recently_viewed' => $recentlyViewed]);

        // Load recently viewed products (excluding current)
        $recentProducts = collect();
        $recentIds = array_slice($recentlyViewed, 1, 4);
        if (!empty($recentIds)) {
            $recentProducts = Product::active()->whereIn('id', $recentIds)->with(['images', 'category'])->get();
        }

        return view('pages.shop.product', compact(
            'product', 'ratingDistribution', 'totalReviews', 'avgRating',
            'relatedProducts', 'recentProducts'
        ));
    }
}
