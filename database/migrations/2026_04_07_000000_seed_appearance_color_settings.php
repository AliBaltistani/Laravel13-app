<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            // Frontend Colors
            ['key' => 'appearance.secondary_color', 'value' => '#e7e7e7', 'group' => 'appearance', 'type' => 'color', 'label' => 'Secondary Color', 'description' => 'Used for secondary buttons, borders, and subtle accents'],
            ['key' => 'appearance.body_bg_color', 'value' => '#ffffff', 'group' => 'appearance', 'type' => 'color', 'label' => 'Body Background', 'description' => 'Main page background color'],
            ['key' => 'appearance.body_text_color', 'value' => '#777777', 'group' => 'appearance', 'type' => 'color', 'label' => 'Body Text Color', 'description' => 'Default text color for paragraphs and content'],
            ['key' => 'appearance.heading_color', 'value' => '#313131', 'group' => 'appearance', 'type' => 'color', 'label' => 'Heading Color', 'description' => 'Color for H1–H6 headings'],
            ['key' => 'appearance.link_color', 'value' => '#08C', 'group' => 'appearance', 'type' => 'color', 'label' => 'Link Color', 'description' => 'Color for links and anchor tags'],

            // Header Colors
            ['key' => 'appearance.header_top_bg', 'value' => '#f4f4f4', 'group' => 'appearance', 'type' => 'color', 'label' => 'Header Top Bar BG', 'description' => 'Background color of the top bar (currency, account links)'],
            ['key' => 'appearance.header_top_text', 'value' => '#777777', 'group' => 'appearance', 'type' => 'color', 'label' => 'Header Top Bar Text', 'description' => 'Text color in the top header bar'],
            ['key' => 'appearance.header_bg', 'value' => '#ffffff', 'group' => 'appearance', 'type' => 'color', 'label' => 'Header Background', 'description' => 'Background for the main header/logo area'],
            ['key' => 'appearance.nav_bg', 'value' => '#08C', 'group' => 'appearance', 'type' => 'color', 'label' => 'Navigation Bar BG', 'description' => 'Background color for the bottom navigation bar'],
            ['key' => 'appearance.nav_text_color', 'value' => '#ffffff', 'group' => 'appearance', 'type' => 'color', 'label' => 'Navigation Text Color', 'description' => 'Text/link color in the main navigation bar'],
            ['key' => 'appearance.nav_hover_color', 'value' => '#ffffff', 'group' => 'appearance', 'type' => 'color', 'label' => 'Navigation Hover Color', 'description' => 'Text color on hover in the navigation bar'],

            // Footer Colors
            ['key' => 'appearance.footer_bg', 'value' => '#222529', 'group' => 'appearance', 'type' => 'color', 'label' => 'Footer Background', 'description' => 'Background color for the footer area'],
            ['key' => 'appearance.footer_text_color', 'value' => '#aaaaaa', 'group' => 'appearance', 'type' => 'color', 'label' => 'Footer Text Color', 'description' => 'Text color in the footer'],
            ['key' => 'appearance.footer_heading_color', 'value' => '#ffffff', 'group' => 'appearance', 'type' => 'color', 'label' => 'Footer Heading Color', 'description' => 'Color for footer widget titles'],
            ['key' => 'appearance.footer_link_color', 'value' => '#aaaaaa', 'group' => 'appearance', 'type' => 'color', 'label' => 'Footer Link Color', 'description' => 'Color for links in the footer'],
            ['key' => 'appearance.footer_bottom_bg', 'value' => '#1c1e22', 'group' => 'appearance', 'type' => 'color', 'label' => 'Footer Bottom BG', 'description' => 'Background for the copyright/payments bar'],

            // Promo / Top Notice Bar
            ['key' => 'appearance.promo_bar_bg', 'value' => '#08C', 'group' => 'appearance', 'type' => 'color', 'label' => 'Promo Bar BG', 'description' => 'Background color of the top promo/notice bar'],
            ['key' => 'appearance.promo_bar_text', 'value' => '#ffffff', 'group' => 'appearance', 'type' => 'color', 'label' => 'Promo Bar Text Color', 'description' => 'Text color in the promo bar'],

            // Buttons
            ['key' => 'appearance.btn_primary_bg', 'value' => '#08C', 'group' => 'appearance', 'type' => 'color', 'label' => 'Button Primary BG', 'description' => 'Background color for primary buttons'],
            ['key' => 'appearance.btn_primary_text', 'value' => '#ffffff', 'group' => 'appearance', 'type' => 'color', 'label' => 'Button Primary Text', 'description' => 'Text color for primary buttons'],

            // Sale / Price
            ['key' => 'appearance.sale_price_color', 'value' => '#e92e05', 'group' => 'appearance', 'type' => 'color', 'label' => 'Sale Price Color', 'description' => 'Color for sale/discounted prices'],
            ['key' => 'appearance.sale_badge_bg', 'value' => '#e92e05', 'group' => 'appearance', 'type' => 'color', 'label' => 'Sale Badge BG', 'description' => 'Background color for "Sale" badges on products'],

            // Admin Panel Colors
            ['key' => 'appearance.admin_primary', 'value' => '#0d6efd', 'group' => 'appearance', 'type' => 'color', 'label' => 'Admin Primary Color', 'description' => 'Accent color for the admin panel (links, active states)'],
            ['key' => 'appearance.admin_sidebar_bg', 'value' => '#1e2a3a', 'group' => 'appearance', 'type' => 'color', 'label' => 'Admin Sidebar BG', 'description' => 'Background color for the admin sidebar'],
            ['key' => 'appearance.admin_sidebar_text', 'value' => '#a8b6c7', 'group' => 'appearance', 'type' => 'color', 'label' => 'Admin Sidebar Text', 'description' => 'Text color for admin sidebar links'],
            ['key' => 'appearance.admin_topbar_bg', 'value' => '#ffffff', 'group' => 'appearance', 'type' => 'color', 'label' => 'Admin Topbar BG', 'description' => 'Background for the admin top navigation bar'],
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
            'appearance.secondary_color', 'appearance.body_bg_color', 'appearance.body_text_color',
            'appearance.heading_color', 'appearance.link_color',
            'appearance.header_top_bg', 'appearance.header_top_text', 'appearance.header_bg',
            'appearance.nav_bg', 'appearance.nav_text_color', 'appearance.nav_hover_color',
            'appearance.footer_bg', 'appearance.footer_text_color', 'appearance.footer_heading_color',
            'appearance.footer_link_color', 'appearance.footer_bottom_bg',
            'appearance.promo_bar_bg', 'appearance.promo_bar_text',
            'appearance.btn_primary_bg', 'appearance.btn_primary_text',
            'appearance.sale_price_color', 'appearance.sale_badge_bg',
            'appearance.admin_primary', 'appearance.admin_sidebar_bg',
            'appearance.admin_sidebar_text', 'appearance.admin_topbar_bg',
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
