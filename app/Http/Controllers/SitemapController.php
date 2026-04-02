<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;

/**
 * SitemapController — Phase 9-C
 * Generates an XML sitemap at /sitemap.xml.
 * Cached for 24 hours, cleared via model observers.
 */
class SitemapController extends Controller
{
    public function index(): Response
    {
        $content = Cache::remember('sitemap_xml', 60 * 60 * 24, function () {
            $products = Product::where('is_active', true)
                ->select('slug', 'updated_at')
                ->get();

            $categories = Category::where('is_active', true)
                ->select('slug', 'updated_at')
                ->get();

            $posts = Post::where('is_published', true)
                ->whereNotNull('published_at')
                ->select('slug', 'updated_at')
                ->get();

            $pages = Page::where('is_active', true)
                ->select('slug', 'updated_at')
                ->get();

            return view('sitemap.index', compact('products', 'categories', 'posts', 'pages'))
                ->render();
        });

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
