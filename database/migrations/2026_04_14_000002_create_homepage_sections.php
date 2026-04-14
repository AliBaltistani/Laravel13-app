<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title');
            $table->string('type'); // slider, banners, products, sale_banner, widgets, brands, instagram, custom_html
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('settings')->nullable(); // JSON: column classes, limits, titles, custom HTML, etc.
            $table->timestamps();
        });

        // Seed default sections matching current homepage
        $sections = [
            [
                'key' => 'hero_slider',
                'title' => 'Hero Slider',
                'type' => 'slider',
                'is_active' => true,
                'sort_order' => 1,
                'settings' => json_encode([
                    'container_class' => 'container',
                    'slider_position' => 'hero',
                ]),
            ],
            [
                'key' => 'category_banners',
                'title' => 'Category Banners',
                'type' => 'banners',
                'is_active' => true,
                'sort_order' => 2,
                'settings' => json_encode([
                    'container_class' => 'container',
                    'banner_position' => 'home-mid',
                    'max_banners' => 3,
                    'col_class' => 'col-md-4 col-sm-6',
                    'show_title' => true,
                ]),
            ],
            [
                'key' => 'featured_products',
                'title' => 'Featured Products',
                'type' => 'products',
                'is_active' => true,
                'sort_order' => 3,
                'settings' => json_encode([
                    'container_class' => 'container',
                    'section_title' => 'Featured Products',
                    'product_type' => 'featured',
                    'limit' => 8,
                    'col_class' => 'col-6 col-sm-4 col-md-3',
                ]),
            ],
            [
                'key' => 'sale_banner',
                'title' => 'Sale Banner',
                'type' => 'sale_banner',
                'is_active' => true,
                'sort_order' => 4,
                'settings' => json_encode([
                    'title' => 'Furniture & Garden',
                    'subtitle' => 'Huge Sale',
                    'discount' => '50',
                    'button_text' => 'Shop Now!',
                    'button_url' => '/shop',
                    'bg_class' => 'bg-secondary',
                ]),
            ],
            [
                'key' => 'product_widgets',
                'title' => 'Product Widget Columns',
                'type' => 'widgets',
                'is_active' => true,
                'sort_order' => 5,
                'settings' => json_encode([
                    'container_class' => 'container',
                    'col_class' => 'col-md-4 col-sm-6',
                    'widget_limit' => 3,
                    'show_top_rated' => true,
                    'top_rated_title' => 'Top Rated Products',
                    'show_best_selling' => true,
                    'best_selling_title' => 'Best Selling Products',
                    'show_latest' => true,
                    'latest_title' => 'Latest Products',
                ]),
            ],
            [
                'key' => 'brands_slider',
                'title' => 'Brands Slider',
                'type' => 'brands',
                'is_active' => true,
                'sort_order' => 6,
                'settings' => json_encode([
                    'container_class' => 'container',
                ]),
            ],
            [
                'key' => 'instagram_feed',
                'title' => 'Instagram Feed',
                'type' => 'instagram',
                'is_active' => true,
                'sort_order' => 7,
                'settings' => json_encode([
                    'section_title' => 'Follow On Instagram',
                    'banner_position' => 'home-instagram',
                ]),
            ],
        ];

        foreach ($sections as $section) {
            $section['created_at'] = now();
            $section['updated_at'] = now();
            DB::table('homepage_sections')->insert($section);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
