<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Slider;
use App\Services\SeoService;

class HomeController extends Controller
{
    public function index()
    {
        // SEO
        $seo = app(SeoService::class);
        $seo->setTitle(Setting::get('general.site_name', config('app.name')), false)
            ->setDescription(Setting::get('seo.default_meta_description', 'Welcome to our online store'))
            ->setJsonLd(SeoService::organizationSchema());

        // Load all homepage sections in order with their assigned products
        $sections = HomepageSection::ordered()->get();

        // Track used product IDs to prevent duplicates across sections
        $usedProductIds = [];
        $sectionData = [];

        foreach ($sections as $section) {
            if (!$section->is_active) continue;

            $settings = $section->settings ?? [];

            switch ($section->type) {
                case 'slider':
                    $position = $settings['slider_position'] ?? 'hero';
                    $sectionData[$section->key] = [
                        'slider' => Slider::with('activeSlides')->where('position', $position)->active()->first(),
                    ];
                    break;

                case 'banners':
                    $position = $settings['banner_position'] ?? 'home-mid';
                    $max = $settings['max_banners'] ?? 3;
                    $sectionData[$section->key] = [
                        'banners' => Banner::active()->position($position)->orderBy('sort_order')->take($max)->get(),
                    ];
                    break;

                case 'products':
                    $limit = (int)($settings['limit'] ?? 8);
                    $source = $settings['product_source'] ?? 'auto';

                    if ($source === 'manual' && $section->products()->count() > 0) {
                        // MANUAL: Admin hand-picked products
                        $products = $section->products()
                            ->where('is_active', true)
                            ->with(['images', 'category', 'flashSaleProducts.flashSale'])
                            ->take($limit)
                            ->get();
                    } else {
                        // AUTO: Query based on product_type filter
                        $type = $settings['product_type'] ?? 'featured';
                        $query = Product::active()->with(['images', 'category', 'flashSaleProducts.flashSale']);

                        if (!empty($usedProductIds)) {
                            $query->whereNotIn('id', $usedProductIds);
                        }

                        if ($type === 'featured') {
                            $query->featured();
                        } elseif ($type === 'new_arrivals') {
                            $query->isNew();
                        } elseif ($type === 'best_selling') {
                            $query->orderByDesc('sold_count');
                        }

                        $products = $query->take($limit)->get();
                    }

                    $usedProductIds = array_merge($usedProductIds, $products->pluck('id')->toArray());
                    $sectionData[$section->key] = ['products' => $products];
                    break;

                case 'sale_banner':
                    $sectionData[$section->key] = ['settings' => $settings];
                    break;

                case 'widgets':
                    $widgetLimit = (int)($settings['widget_limit'] ?? 3);
                    $source = $settings['product_source'] ?? 'auto';
                    $data = [];

                    if ($source === 'manual' && $section->products()->count() > 0) {
                        // MANUAL: Split admin-picked products evenly across the 3 widget columns
                        $manual = $section->products()
                            ->where('is_active', true)
                            ->with(['images', 'category'])
                            ->get();

                        $usedProductIds = array_merge($usedProductIds, $manual->pluck('id')->toArray());

                        // Split into chunks for each enabled column
                        $chunks = $manual->chunk($widgetLimit);
                        $i = 0;

                        if ($settings['show_top_rated'] ?? true) {
                            $data['topRated'] = $chunks[$i] ?? collect();
                            $i++;
                        }
                        if ($settings['show_best_selling'] ?? true) {
                            $data['bestSelling'] = $chunks[$i] ?? collect();
                            $i++;
                        }
                        if ($settings['show_latest'] ?? true) {
                            $data['latestProducts'] = $chunks[$i] ?? collect();
                        }
                    } else {
                        // AUTO: Query based on ratings/sales/date
                        if ($settings['show_top_rated'] ?? true) {
                            $q = Product::active()->with(['images', 'category'])
                                ->withAvg('approvedReviews', 'rating')
                                ->orderByDesc('approved_reviews_avg_rating');
                            if (!empty($usedProductIds)) $q->whereNotIn('id', $usedProductIds);
                            $topRated = $q->take($widgetLimit)->get();
                            $usedProductIds = array_merge($usedProductIds, $topRated->pluck('id')->toArray());
                            $data['topRated'] = $topRated;
                        }

                        if ($settings['show_best_selling'] ?? true) {
                            $q = Product::active()->with(['images', 'category'])->orderByDesc('sold_count');
                            if (!empty($usedProductIds)) $q->whereNotIn('id', $usedProductIds);
                            $bestSelling = $q->take($widgetLimit)->get();
                            $usedProductIds = array_merge($usedProductIds, $bestSelling->pluck('id')->toArray());
                            $data['bestSelling'] = $bestSelling;
                        }

                        if ($settings['show_latest'] ?? true) {
                            $q = Product::active()->with(['images', 'category'])->latest();
                            if (!empty($usedProductIds)) $q->whereNotIn('id', $usedProductIds);
                            $latest = $q->take($widgetLimit)->get();
                            $usedProductIds = array_merge($usedProductIds, $latest->pluck('id')->toArray());
                            $data['latestProducts'] = $latest;
                        }
                    }

                    $sectionData[$section->key] = $data;
                    break;

                case 'brands':
                    $sectionData[$section->key] = [
                        'brands' => Brand::active()->featured()->ordered()->get(),
                    ];
                    break;

                case 'instagram':
                    $position = $settings['banner_position'] ?? 'home-instagram';
                    $sectionData[$section->key] = [
                        'images' => Banner::active()->position($position)->orderBy('sort_order')->get(),
                    ];
                    break;

                case 'custom_html':
                    $sectionData[$section->key] = [
                        'html' => $settings['custom_html'] ?? '',
                    ];
                    break;
            }
        }

        return view('home', compact('sections', 'sectionData'));
    }
}
