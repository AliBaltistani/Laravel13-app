<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Page;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        // Get existing content from settings
        $termsContent = Setting::get('legal.terms_content', '<p>Terms & Conditions content has not been configured yet.</p>');
        $privacyContent = Setting::get('legal.privacy_content', '<p>Privacy Policy content has not been configured yet.</p>');
        $cookieText = Setting::get('legal.cookie_consent_text', 'We use cookies to enhance your experience. By continuing to browse this site, you agree to our use of cookies.');

        // Create Terms & Conditions page
        Page::firstOrCreate(
            ['slug' => 'terms-conditions'],
            [
                'title' => 'Terms & Conditions',
                'content' => $termsContent,
                'excerpt' => 'Read our terms and conditions before using our website and services.',
                'meta_title' => 'Terms & Conditions',
                'meta_description' => 'Read our terms and conditions before using our website and services.',
                'is_active' => true,
                'show_in_footer' => true,
                'sort_order' => 1,
                'template' => 'legal',
            ]
        );

        // Create Privacy Policy page
        Page::firstOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title' => 'Privacy Policy',
                'content' => $privacyContent,
                'excerpt' => 'Read our privacy policy to understand how we collect, use, and protect your personal data.',
                'meta_title' => 'Privacy Policy',
                'meta_description' => 'Read our privacy policy to understand how we collect, use, and protect your personal data.',
                'is_active' => true,
                'show_in_footer' => true,
                'sort_order' => 2,
                'template' => 'legal',
            ]
        );

        // Create Cookie Consent page (optional, for reference)
        Page::firstOrCreate(
            ['slug' => 'cookie-consent'],
            [
                'title' => 'Cookie Consent',
                'content' => '<p>' . $cookieText . '</p>',
                'excerpt' => 'Learn about our cookie policy and consent management.',
                'meta_title' => 'Cookie Consent',
                'meta_description' => 'Learn about our cookie policy and consent management.',
                'is_active' => true,
                'show_in_footer' => false,
                'sort_order' => 0,
                'template' => 'legal',
            ]
        );

        // Create Contact page with CMS fields (will be managed through admin)
        Page::firstOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact Us',
                'content' => '<p>Get in touch with us. We\'d love to hear from you.</p>',
                'excerpt' => 'Contact our team for any questions or support.',
                'meta_title' => 'Contact Us',
                'meta_description' => 'Get in touch with us for questions, support, or feedback',
                'is_active' => true,
                'show_in_footer' => false,
                'sort_order' => 0,
                'template' => 'contact',
            ]
        );
    }

    public function down(): void
    {
        // Remove pages created by this migration
        Page::whereIn('slug', ['terms-conditions', 'privacy-policy', 'cookie-consent', 'contact'])->delete();
    }
};
