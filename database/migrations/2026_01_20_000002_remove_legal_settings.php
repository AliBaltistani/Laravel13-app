<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove legal settings - these will be managed by Pages CMS
        $keysToRemove = [
            'legal.terms_content',
            'legal.privacy_content',
            'legal.terms_updated_at',
            'legal.privacy_updated_at',
            'legal.cookie_consent_enabled',
            'legal.cookie_consent_text',
        ];

        DB::table('settings')->whereIn('key', $keysToRemove)->delete();
    }

    public function down(): void
    {
        // Restore removed legal settings
        $settings = [
            ['key' => 'legal.terms_content', 'value' => '<h2>Terms and Conditions</h2><p>Welcome to our website. By accessing and using this website, you accept and agree to be bound by the terms and conditions set forth below.</p>', 'group' => 'legal', 'type' => 'richtext', 'label' => 'Terms & Conditions Content', 'description' => 'Full HTML content for the Terms & Conditions page'],
            ['key' => 'legal.privacy_content', 'value' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information.</p>', 'group' => 'legal', 'type' => 'richtext', 'label' => 'Privacy Policy Content', 'description' => 'Full HTML content for the Privacy Policy page'],
            ['key' => 'legal.terms_updated_at', 'value' => now()->toDateString(), 'group' => 'legal', 'type' => 'text', 'label' => 'Terms Last Updated', 'description' => 'Date when Terms & Conditions were last updated (YYYY-MM-DD)'],
            ['key' => 'legal.privacy_updated_at', 'value' => now()->toDateString(), 'group' => 'legal', 'type' => 'text', 'label' => 'Privacy Last Updated', 'description' => 'Date when Privacy Policy was last updated (YYYY-MM-DD)'],
            ['key' => 'legal.cookie_consent_enabled', 'value' => '1', 'group' => 'legal', 'type' => 'boolean', 'label' => 'Show Cookie Consent Banner', 'description' => 'Show a cookie consent banner to visitors'],
            ['key' => 'legal.cookie_consent_text', 'value' => 'We use cookies to enhance your browsing experience. By continuing to use our site, you agree to our use of cookies.', 'group' => 'legal', 'type' => 'textarea', 'label' => 'Cookie Consent Text', 'description' => 'Text displayed in the cookie consent banner'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
};
