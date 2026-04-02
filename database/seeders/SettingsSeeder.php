<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'general.site_name', 'value' => 'Porto Shop', 'group' => 'general', 'type' => 'text', 'label' => 'Site Name'],
            ['key' => 'general.site_tagline', 'value' => 'Online Shopping Store', 'group' => 'general', 'type' => 'text', 'label' => 'Site Tagline'],
            ['key' => 'general.logo', 'value' => null, 'group' => 'general', 'type' => 'image', 'label' => 'Site Logo'],
            ['key' => 'general.favicon', 'value' => null, 'group' => 'general', 'type' => 'image', 'label' => 'Favicon'],
            ['key' => 'general.footer_logo', 'value' => null, 'group' => 'general', 'type' => 'image', 'label' => 'Footer Logo'],
            ['key' => 'general.footer_about', 'value' => 'Porto is an optimized eCommerce theme built with quality and care.', 'group' => 'general', 'type' => 'textarea', 'label' => 'Footer About Text'],
            ['key' => 'general.copyright', 'value' => '© Porto eCommerce. All Rights Reserved.', 'group' => 'general', 'type' => 'text', 'label' => 'Copyright Text'],
            ['key' => 'general.admin_email', 'value' => 'admin@porto.com', 'group' => 'general', 'type' => 'text', 'label' => 'Admin Email'],

            // Contact
            ['key' => 'contact.address', 'value' => '123 Street Name, City, Country', 'group' => 'contact', 'type' => 'textarea', 'label' => 'Address'],
            ['key' => 'contact.phone', 'value' => '+1 234 567 890', 'group' => 'contact', 'type' => 'text', 'label' => 'Phone Number'],
            ['key' => 'contact.email', 'value' => 'contact@porto.com', 'group' => 'contact', 'type' => 'text', 'label' => 'Contact Email'],
            ['key' => 'contact.working_hours', 'value' => 'Mon - Fri: 9AM - 5PM', 'group' => 'contact', 'type' => 'text', 'label' => 'Working Hours'],
            ['key' => 'contact.map_url', 'value' => '', 'group' => 'contact', 'type' => 'text', 'label' => 'Google Maps Embed URL'],
            ['key' => 'contact.subjects', 'value' => '["General Inquiry","Order Support","Returns","Partnership"]', 'group' => 'contact', 'type' => 'json', 'label' => 'Contact Form Subjects'],

            // SEO
            ['key' => 'seo.meta_title', 'value' => 'Porto - Online Shopping Store', 'group' => 'seo', 'type' => 'text', 'label' => 'Default Meta Title'],
            ['key' => 'seo.meta_description', 'value' => 'Discover amazing products at Porto. Shop fashion, electronics, and more with fast delivery.', 'group' => 'seo', 'type' => 'textarea', 'label' => 'Default Meta Description'],
            ['key' => 'seo.google_analytics_id', 'value' => '', 'group' => 'seo', 'type' => 'text', 'label' => 'Google Analytics ID'],
            ['key' => 'seo.google_verification', 'value' => '', 'group' => 'seo', 'type' => 'text', 'label' => 'Google Verification Tag'],
            ['key' => 'seo.robots_txt', 'value' => "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /account/\nDisallow: /cart\nDisallow: /checkout\nSitemap: /sitemap.xml", 'group' => 'seo', 'type' => 'textarea', 'label' => 'Robots.txt Content'],

            // Social
            ['key' => 'social.facebook', 'value' => 'https://facebook.com', 'group' => 'social', 'type' => 'text', 'label' => 'Facebook URL'],
            ['key' => 'social.twitter', 'value' => 'https://twitter.com', 'group' => 'social', 'type' => 'text', 'label' => 'Twitter/X URL'],
            ['key' => 'social.instagram', 'value' => 'https://instagram.com', 'group' => 'social', 'type' => 'text', 'label' => 'Instagram URL'],
            ['key' => 'social.linkedin', 'value' => '', 'group' => 'social', 'type' => 'text', 'label' => 'LinkedIn URL'],
            ['key' => 'social.youtube', 'value' => '', 'group' => 'social', 'type' => 'text', 'label' => 'YouTube URL'],

            // Payment
            ['key' => 'payment.stripe_enabled', 'value' => '0', 'group' => 'payment', 'type' => 'boolean', 'label' => 'Enable Stripe'],
            ['key' => 'payment.stripe_publishable_key', 'value' => '', 'group' => 'payment', 'type' => 'text', 'label' => 'Stripe Publishable Key'],
            ['key' => 'payment.paypal_enabled', 'value' => '0', 'group' => 'payment', 'type' => 'boolean', 'label' => 'Enable PayPal'],
            ['key' => 'payment.paypal_mode', 'value' => 'sandbox', 'group' => 'payment', 'type' => 'select', 'label' => 'PayPal Mode'],
            ['key' => 'payment.cod_enabled', 'value' => '1', 'group' => 'payment', 'type' => 'boolean', 'label' => 'Enable Cash on Delivery'],
            ['key' => 'payment.cod_instructions', 'value' => 'Pay with cash upon delivery.', 'group' => 'payment', 'type' => 'textarea', 'label' => 'COD Instructions'],
            ['key' => 'payment.bank_transfer_enabled', 'value' => '0', 'group' => 'payment', 'type' => 'boolean', 'label' => 'Enable Bank Transfer'],
            ['key' => 'payment.bank_transfer_details', 'value' => 'Bank: Example Bank\nAccount: 1234567890\nIBAN: XX00 0000 0000 0000', 'group' => 'payment', 'type' => 'textarea', 'label' => 'Bank Transfer Details'],

            // Shipping
            ['key' => 'shipping.free_threshold', 'value' => '100', 'group' => 'shipping', 'type' => 'number', 'label' => 'Free Shipping Threshold'],
            ['key' => 'shipping.free_threshold_label', 'value' => 'Free shipping on orders over $100!', 'group' => 'shipping', 'type' => 'text', 'label' => 'Free Shipping Label'],
            ['key' => 'shipping.default_weight_unit', 'value' => 'kg', 'group' => 'shipping', 'type' => 'select', 'label' => 'Default Weight Unit'],

            // Mail
            ['key' => 'mail.driver', 'value' => 'log', 'group' => 'mail', 'type' => 'select', 'label' => 'Mail Driver'],
            ['key' => 'mail.host', 'value' => '', 'group' => 'mail', 'type' => 'text', 'label' => 'SMTP Host'],
            ['key' => 'mail.port', 'value' => '587', 'group' => 'mail', 'type' => 'number', 'label' => 'SMTP Port'],
            ['key' => 'mail.username', 'value' => '', 'group' => 'mail', 'type' => 'text', 'label' => 'SMTP Username'],
            ['key' => 'mail.password', 'value' => '', 'group' => 'mail', 'type' => 'text', 'label' => 'SMTP Password'],
            ['key' => 'mail.encryption', 'value' => 'tls', 'group' => 'mail', 'type' => 'select', 'label' => 'SMTP Encryption'],
            ['key' => 'mail.sender_name', 'value' => 'Porto Shop', 'group' => 'mail', 'type' => 'text', 'label' => 'Sender Name'],
            ['key' => 'mail.sender_email', 'value' => 'noreply@porto.com', 'group' => 'mail', 'type' => 'text', 'label' => 'Sender Email'],

            // Appearance
            ['key' => 'appearance.primary_color', 'value' => '#08C', 'group' => 'appearance', 'type' => 'color', 'label' => 'Primary Color'],

            // Promo
            ['key' => 'promo.bar_enabled', 'value' => '1', 'group' => 'promo', 'type' => 'boolean', 'label' => 'Enable Promo Bar'],
            ['key' => 'promo.bar_text', 'value' => 'GET YOUR $50 COUPON NOW', 'group' => 'promo', 'type' => 'text', 'label' => 'Promo Bar Text'],
            ['key' => 'promo.bar_bg_color', 'value' => '#0088cc', 'group' => 'promo', 'type' => 'color', 'label' => 'Promo Bar Background Color'],
            ['key' => 'promo.bar_link1_label', 'value' => "Women's", 'group' => 'promo', 'type' => 'text', 'label' => 'Promo Link 1 Label'],
            ['key' => 'promo.bar_link1_url', 'value' => '/shop/category/women', 'group' => 'promo', 'type' => 'text', 'label' => 'Promo Link 1 URL'],
            ['key' => 'promo.bar_link2_label', 'value' => "Men's", 'group' => 'promo', 'type' => 'text', 'label' => 'Promo Link 2 Label'],
            ['key' => 'promo.bar_link2_url', 'value' => '/shop/category/men', 'group' => 'promo', 'type' => 'text', 'label' => 'Promo Link 2 URL'],

            // Home sections
            ['key' => 'home.show_featured_products', 'value' => '1', 'group' => 'promo', 'type' => 'boolean', 'label' => 'Show Featured Products'],
            ['key' => 'home.show_new_arrivals', 'value' => '1', 'group' => 'promo', 'type' => 'boolean', 'label' => 'Show New Arrivals'],
            ['key' => 'home.show_flash_sale', 'value' => '1', 'group' => 'promo', 'type' => 'boolean', 'label' => 'Show Flash Sale'],
            ['key' => 'home.show_brands', 'value' => '1', 'group' => 'promo', 'type' => 'boolean', 'label' => 'Show Brands'],
            ['key' => 'home.show_testimonials', 'value' => '1', 'group' => 'promo', 'type' => 'boolean', 'label' => 'Show Testimonials'],
            ['key' => 'home.featured_products_limit', 'value' => '8', 'group' => 'promo', 'type' => 'number', 'label' => 'Featured Products Limit'],
            ['key' => 'home.new_arrivals_limit', 'value' => '8', 'group' => 'promo', 'type' => 'number', 'label' => 'New Arrivals Limit'],
            ['key' => 'home.featured_title', 'value' => 'Featured Products', 'group' => 'promo', 'type' => 'text', 'label' => 'Featured Section Title'],
            ['key' => 'home.new_arrivals_title', 'value' => 'New Arrivals', 'group' => 'promo', 'type' => 'text', 'label' => 'New Arrivals Section Title'],

            // Footer
            ['key' => 'footer.service_links', 'value' => '[{"label":"Help & FAQs","url":"/page/faq"},{"label":"Order Tracking","url":"/account/orders"},{"label":"Shipping & Delivery","url":"/page/shipping"},{"label":"Returns","url":"/page/returns"}]', 'group' => 'footer', 'type' => 'json', 'label' => 'Footer Service Links'],

            // Product tabs
            ['key' => 'product.tab_shipping_content', 'value' => '<p>We deliver to over 100 countries around the world. Standard shipping takes 5-10 business days.</p>', 'group' => 'general', 'type' => 'textarea', 'label' => 'Shipping Tab Content'],
            ['key' => 'product.tab_return_content', 'value' => '<p>You may return most new, unopened items within 30 days of delivery for a full refund.</p>', 'group' => 'general', 'type' => 'textarea', 'label' => 'Returns Tab Content'],

            // About page
            ['key' => 'about.heading', 'value' => 'About Us', 'group' => 'general', 'type' => 'text', 'label' => 'About Heading'],
            ['key' => 'about.description', 'value' => 'We are a team of passionate individuals dedicated to bringing you the best online shopping experience.', 'group' => 'general', 'type' => 'textarea', 'label' => 'About Description'],

            // Blog
            ['key' => 'blog.auto_approve_comments', 'value' => '0', 'group' => 'general', 'type' => 'boolean', 'label' => 'Auto Approve Comments'],

            // Google OAuth
            ['key' => 'google_oauth_enabled', 'value' => '0', 'group' => 'general', 'type' => 'boolean', 'label' => 'Enable Google OAuth'],

            // Newsletter
            ['key' => 'footer.newsletter_title', 'value' => 'Subscribe Newsletter', 'group' => 'footer', 'type' => 'text', 'label' => 'Newsletter Title'],
            ['key' => 'footer.newsletter_description', 'value' => 'Get all the latest information on events, sales and offers. Sign up for newsletter today.', 'group' => 'footer', 'type' => 'textarea', 'label' => 'Newsletter Description'],

            // Contact auto-reply
            ['key' => 'contact.auto_reply_subject', 'value' => 'Thank you for contacting us', 'group' => 'contact', 'type' => 'text', 'label' => 'Auto Reply Subject'],
            ['key' => 'contact.auto_reply_body', 'value' => 'Thank you for reaching out. We have received your message and will get back to you within 24-48 hours.', 'group' => 'contact', 'type' => 'textarea', 'label' => 'Auto Reply Body'],

            // Asset versioning
            ['key' => 'appearance.asset_version', 'value' => '1.0', 'group' => 'appearance', 'type' => 'text', 'label' => 'Asset Version'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
