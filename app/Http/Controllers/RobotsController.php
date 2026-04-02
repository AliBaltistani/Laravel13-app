<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;

/**
 * RobotsController — Phase 9-D
 * Serves robots.txt from settings (editable from admin panel).
 */
class RobotsController extends Controller
{
    public function index(): Response
    {
        $content = Cache::remember('robots_txt', 60 * 60 * 24, function () {
            return Setting::get('seo.robots_txt', $this->defaultRobots());
        });

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Default robots.txt content.
     */
    private function defaultRobots(): string
    {
        $sitemapUrl = url('/sitemap.xml');

        return <<<ROBOTS
User-agent: *
Allow: /

Disallow: /admin/
Disallow: /account/
Disallow: /cart
Disallow: /checkout
Disallow: /login
Disallow: /register
Disallow: /forgot-password
Disallow: /reset-password

Sitemap: {$sitemapUrl}
ROBOTS;
    }
}
