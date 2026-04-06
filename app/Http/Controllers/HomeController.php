<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Slider;
use App\Services\SeoService;

class HomeController extends Controller
{
    public function index()
    {
        // SEO — Phase 9-A: Homepage gets Organization schema
        $seo = app(SeoService::class);
        $seo->setTitle(Setting::get('general.site_name', config('app.name')), false)
            ->setDescription(Setting::get('seo.default_meta_description', 'Welcome to our online store'))
            ->setJsonLd(SeoService::organizationSchema());

        $slider = Slider::with('activeSlides')->where('position', 'hero')->active()->first();

        $featuredProducts = Product::active()->featured()
            ->with(['images', 'category', 'flashSaleProducts.flashSale'])
            ->take((int) Setting::get('home.featured_products_limit', 8))
            ->get();

        $newArrivals = Product::active()->isNew()
            ->with(['images', 'category', 'flashSaleProducts.flashSale'])
            ->take((int) Setting::get('home.new_arrivals_limit', 8))
            ->get();

        $flashSale = FlashSale::active()->with('products.product.images')->first();

        $brands = Brand::active()->featured()->ordered()->get();

        $banners = Banner::active()->position('home-mid')->orderBy('sort_order')->get();

        $sidebarBanners = Banner::active()->position('home-sidebar')->orderBy('sort_order')->get();

        $topRated = Product::active()
            ->with(['images', 'category'])
            ->withAvg('approvedReviews', 'rating')
            ->orderByDesc('approved_reviews_avg_rating')
            ->take(3)->get();

        $bestSelling = Product::active()
            ->with(['images', 'category'])
            ->orderByDesc('sold_count')
            ->take(3)->get();

        $latestProducts = Product::active()
            ->with(['images', 'category'])
            ->latest()
            ->take(3)->get();

        // Instagram feed images (Demo8) — admin-managed banners at position 'home-instagram'
        $instagramImages = Banner::active()->position('home-instagram')->orderBy('sort_order')->get();

        return view('home', compact(
            'slider', 'featuredProducts', 'newArrivals', 'flashSale',
            'brands', 'banners', 'sidebarBanners', 'topRated', 'bestSelling',
            'latestProducts', 'instagramImages'
        ));
    }
}
