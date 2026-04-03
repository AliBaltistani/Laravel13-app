<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Seed missing frontend settings for admin panel control.
     */
    public function up(): void
    {
        $settings = [
            // Homepage info boxes
            ['key' => 'home.show_info_boxes', 'value' => '1', 'group' => 'home', 'type' => 'boolean', 'label' => 'Show Info Boxes', 'description' => 'Show the info boxes row on homepage'],
            ['key' => 'home.info_box1_icon', 'value' => 'icon-shipping', 'group' => 'home', 'type' => 'text', 'label' => 'Info Box 1 Icon', 'description' => 'CSS icon class for info box 1'],
            ['key' => 'home.info_box1_title', 'value' => 'FREE SHIPPING & RETURN', 'group' => 'home', 'type' => 'text', 'label' => 'Info Box 1 Title', 'description' => 'Title for the first info box'],
            ['key' => 'home.info_box1_text', 'value' => 'Free shipping on all orders over $99', 'group' => 'home', 'type' => 'text', 'label' => 'Info Box 1 Text', 'description' => 'Description text for the first info box'],
            ['key' => 'home.info_box2_icon', 'value' => 'icon-money', 'group' => 'home', 'type' => 'text', 'label' => 'Info Box 2 Icon', 'description' => 'CSS icon class for info box 2'],
            ['key' => 'home.info_box2_title', 'value' => 'MONEY BACK GUARANTEE', 'group' => 'home', 'type' => 'text', 'label' => 'Info Box 2 Title', 'description' => 'Title for the second info box'],
            ['key' => 'home.info_box2_text', 'value' => '100% money back guarantee', 'group' => 'home', 'type' => 'text', 'label' => 'Info Box 2 Text', 'description' => 'Description text for the second info box'],
            ['key' => 'home.info_box3_icon', 'value' => 'icon-support', 'group' => 'home', 'type' => 'text', 'label' => 'Info Box 3 Icon', 'description' => 'CSS icon class for info box 3'],
            ['key' => 'home.info_box3_title', 'value' => 'ONLINE SUPPORT 24/7', 'group' => 'home', 'type' => 'text', 'label' => 'Info Box 3 Title', 'description' => 'Title for the third info box'],
            ['key' => 'home.info_box3_text', 'value' => 'Get support any time you need', 'group' => 'home', 'type' => 'text', 'label' => 'Info Box 3 Text', 'description' => 'Description text for the third info box'],
            ['key' => 'home.show_promo_banners', 'value' => '1', 'group' => 'home', 'type' => 'boolean', 'label' => 'Show Promo Banners', 'description' => 'Show promotional banners section on homepage'],

            // Header settings
            ['key' => 'header.top_message', 'value' => 'FREE Returns. Standard Shipping Orders $99+', 'group' => 'appearance', 'type' => 'text', 'label' => 'Header Top Message', 'description' => 'Message displayed in the top bar of the header'],
            ['key' => 'header.special_offer_text', 'value' => 'Special Offer!', 'group' => 'appearance', 'type' => 'text', 'label' => 'Special Offer Text', 'description' => 'Text for the special offer link in navigation'],
            ['key' => 'header.special_offer_url', 'value' => '/shop', 'group' => 'appearance', 'type' => 'text', 'label' => 'Special Offer URL', 'description' => 'URL for the special offer link'],
            ['key' => 'header.show_special_offer', 'value' => '1', 'group' => 'appearance', 'type' => 'boolean', 'label' => 'Show Special Offer Link', 'description' => 'Toggle the special offer link in navigation'],

            // Currency settings
            ['key' => 'general.currency_symbol', 'value' => '$', 'group' => 'general', 'type' => 'text', 'label' => 'Currency Symbol', 'description' => 'The currency symbol (e.g. $, €, £)'],
            ['key' => 'general.currency_code', 'value' => 'USD', 'group' => 'general', 'type' => 'text', 'label' => 'Currency Code', 'description' => 'ISO 4217 currency code (e.g. USD, EUR, GBP)'],
            ['key' => 'general.currency_position', 'value' => 'before', 'group' => 'general', 'type' => 'text', 'label' => 'Currency Position', 'description' => 'Position of currency symbol: "before" or "after" the amount'],

            // Appearance - Logo
            ['key' => 'appearance.logo', 'value' => '', 'group' => 'appearance', 'type' => 'image', 'label' => 'Site Logo', 'description' => 'Upload your site logo (recommended: 111×44px)'],
            ['key' => 'appearance.favicon', 'value' => '', 'group' => 'appearance', 'type' => 'image', 'label' => 'Favicon', 'description' => 'Upload your site favicon'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        $keys = [
            'home.show_info_boxes', 'home.info_box1_icon', 'home.info_box1_title', 'home.info_box1_text',
            'home.info_box2_icon', 'home.info_box2_title', 'home.info_box2_text',
            'home.info_box3_icon', 'home.info_box3_title', 'home.info_box3_text',
            'home.show_promo_banners',
            'header.top_message', 'header.special_offer_text', 'header.special_offer_url', 'header.show_special_offer',
            'general.currency_symbol', 'general.currency_code', 'general.currency_position',
            'appearance.logo', 'appearance.favicon',
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
