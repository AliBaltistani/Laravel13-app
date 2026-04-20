<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove duplicate and unused settings
        $keysToRemove = [
            // Footer links now managed by Pages CMS
            'footer.about_link1_label',
            'footer.about_link1_url',
            'footer.about_link2_label',
            'footer.about_link2_url',
            'footer.about_link3_label',
            'footer.about_link3_url',
            'footer.about_link4_label',
            'footer.about_link4_url',
            'footer.about_link5_label',
            'footer.about_link5_url',
            'footer.col1_title',
            'footer.col2_title',
            'footer.col3_title',
            
            // Unused settings
            'contact.map_url',
            'contact.subjects',
            'shipping.default_weight_unit',
            'blog.auto_approve_comments',
            'appearance.asset_version',
        ];

        DB::table('settings')->whereIn('key', $keysToRemove)->delete();
    }

    public function down(): void
    {
        // Restore removed settings
        $settings = [
            // Footer links
            ['key' => 'footer.about_link1_label', 'value' => 'About Us', 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Link 1 Label'],
            ['key' => 'footer.about_link1_url', 'value' => url('/about'), 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Link 1 URL'],
            ['key' => 'footer.about_link2_label', 'value' => 'Contact Us', 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Link 2 Label'],
            ['key' => 'footer.about_link2_url', 'value' => url('/contact'), 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Link 2 URL'],
            ['key' => 'footer.about_link3_label', 'value' => 'Our Story', 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Link 3 Label'],
            ['key' => 'footer.about_link3_url', 'value' => url('/about'), 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Link 3 URL'],
            ['key' => 'footer.about_link4_label', 'value' => 'Privacy Policy', 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Link 4 Label'],
            ['key' => 'footer.about_link4_url', 'value' => url('/page/privacy-policy'), 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Link 4 URL'],
            ['key' => 'footer.about_link5_label', 'value' => 'Terms of Service', 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Link 5 Label'],
            ['key' => 'footer.about_link5_url', 'value' => url('/page/terms-of-service'), 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Link 5 URL'],
            ['key' => 'footer.col1_title', 'value' => 'CONTACT INFO', 'group' => 'footer', 'type' => 'text', 'label' => 'Column 1 Title'],
            ['key' => 'footer.col2_title', 'value' => 'CUSTOMER SERVICE', 'group' => 'footer', 'type' => 'text', 'label' => 'Column 2 Title'],
            ['key' => 'footer.col3_title', 'value' => 'ABOUT US', 'group' => 'footer', 'type' => 'text', 'label' => 'Column 3 Title'],
            
            // Unused settings
            ['key' => 'contact.map_url', 'value' => '', 'group' => 'contact', 'type' => 'text', 'label' => 'Google Maps Embed URL'],
            ['key' => 'contact.subjects', 'value' => '["General Inquiry","Order Support","Returns","Partnership"]', 'group' => 'contact', 'type' => 'json', 'label' => 'Contact Form Subjects'],
            ['key' => 'shipping.default_weight_unit', 'value' => 'kg', 'group' => 'shipping', 'type' => 'select', 'label' => 'Default Weight Unit'],
            ['key' => 'blog.auto_approve_comments', 'value' => '0', 'group' => 'general', 'type' => 'boolean', 'label' => 'Auto Approve Comments'],
            ['key' => 'appearance.asset_version', 'value' => '1.0', 'group' => 'appearance', 'type' => 'text', 'label' => 'Asset Version'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
};
