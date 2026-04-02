<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Product;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $slider = Slider::with('activeSlides')->where('position', 'hero')->active()->first();

        $featuredProducts = Product::active()->featured()
            ->with(['images', 'category', 'flashSaleProducts.flashSale'])
            ->take((int) \App\Models\Setting::get('home.featured_products_limit', 8))
            ->get();

        $newArrivals = Product::active()->isNew()
            ->with(['images', 'category', 'flashSaleProducts.flashSale'])
            ->take((int) \App\Models\Setting::get('home.new_arrivals_limit', 8))
            ->get();

        $flashSale = FlashSale::active()->with('products.product.images')->first();

        $brands = Brand::active()->featured()->ordered()->get();

        $banners = Banner::active()->position('home-mid')->orderBy('sort_order')->get();

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

        return view('home', compact(
            'slider', 'featuredProducts', 'newArrivals', 'flashSale',
            'brands', 'banners', 'topRated', 'bestSelling', 'latestProducts'
        ));
    }
}
