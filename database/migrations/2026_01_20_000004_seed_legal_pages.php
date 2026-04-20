<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create About, Terms, and Privacy pages managed by CMS
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h2>About Our Company</h2><p>We are a team of passionate individuals dedicated to bringing you the best online shopping experience. With years of expertise in e-commerce, we pride ourselves on quality products, excellent customer service, and fast delivery.</p><h3>Our Mission</h3><p>To provide customers with a seamless, secure, and enjoyable shopping experience by offering a curated selection of high-quality products at competitive prices.</p><h3>Our Values</h3><p>Quality, Integrity, and Customer Satisfaction are at the heart of everything we do.</p>',
                'excerpt' => 'Learn more about Porto and our commitment to excellence.',
                'is_active' => true,
                'show_in_header' => true,
                'show_in_footer' => true,
                'header_label' => 'About Us',
                'footer_label' => 'About Us',
                'header_order' => 1,
                'footer_order' => 1,
                'meta_title' => 'About Us - Porto Shop',
                'meta_description' => 'Learn about Porto Shop, our mission, values, and commitment to excellence in e-commerce.',
                'sort_order' => 1,
            ],
            [
                'title' => 'Terms and Conditions',
                'slug' => 'terms',
                'content' => '<h2>Terms and Conditions</h2><p>Welcome to our website. By accessing and using this website, you accept and agree to be bound by the terms and conditions set forth below.</p><h3>1. Use of the Site</h3><p>You may use our site for lawful purposes only. You must not use our site in any way that causes, or may cause, damage to the site or impairment of the availability or accessibility of the site.</p><h3>2. Intellectual Property</h3><p>All content included on this site, such as text, graphics, logos, images, and software, is the property of our company and protected by copyright laws.</p><h3>3. Products and Services</h3><p>We reserve the right to modify or discontinue any product or service without notice. Prices are subject to change without notice.</p><h3>4. Orders and Payment</h3><p>By placing an order, you agree to provide accurate and complete information. We reserve the right to refuse or cancel any order.</p><h3>5. Shipping and Delivery</h3><p>Delivery times are estimates and not guaranteed. We are not responsible for delays caused by shipping carriers.</p><h3>6. Returns and Refunds</h3><p>Our return policy allows returns within 30 days of purchase. Items must be in original condition.</p><h3>7. Limitation of Liability</h3><p>We shall not be liable for any indirect, incidental, or consequential damages arising from your use of our site or products.</p><h3>8. Changes to Terms</h3><p>We reserve the right to update these terms at any time. Continued use of the site constitutes acceptance of modified terms.</p>',
                'excerpt' => 'Read our complete Terms and Conditions.',
                'is_active' => true,
                'show_in_header' => false,
                'show_in_footer' => true,
                'header_label' => null,
                'footer_label' => 'Terms & Conditions',
                'header_order' => 0,
                'footer_order' => 2,
                'meta_title' => 'Terms and Conditions - Porto Shop',
                'meta_description' => 'Review Porto Shop\'s complete Terms and Conditions.',
                'sort_order' => 2,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'content' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information.</p><h3>1. Information We Collect</h3><p>We collect information you provide directly, such as your name, email address, shipping address, and payment information when you create an account or place an order.</p><h3>2. How We Use Your Information</h3><p>We use your information to process orders, communicate with you, improve our services, and send promotional materials (with your consent).</p><h3>3. Information Sharing</h3><p>We do not sell your personal information. We may share information with trusted third parties who assist us in operating our website and conducting business.</p><h3>4. Data Security</h3><p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, or destruction.</p><h3>5. Cookies</h3><p>Our website uses cookies to enhance your browsing experience. You can choose to disable cookies through your browser settings.</p><h3>6. Your Rights</h3><p>You have the right to access, correct, or delete your personal information. Contact us to exercise these rights.</p><h3>7. Changes to This Policy</h3><p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page.</p><h3>8. Contact Us</h3><p>If you have any questions about this privacy policy, please contact us through our contact page.</p>',
                'excerpt' => 'Read our complete Privacy Policy.',
                'is_active' => true,
                'show_in_header' => false,
                'show_in_footer' => true,
                'header_label' => null,
                'footer_label' => 'Privacy Policy',
                'header_order' => 0,
                'footer_order' => 3,
                'meta_title' => 'Privacy Policy - Porto Shop',
                'meta_description' => 'Review Porto Shop\'s Privacy Policy and data protection practices.',
                'sort_order' => 3,
            ],
        ];

        foreach ($pages as $page) {
            DB::table('pages')->updateOrInsert(
                ['slug' => $page['slug']],
                array_merge($page, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }

    public function down(): void
    {
        // Delete the seeded pages
        DB::table('pages')->whereIn('slug', ['about-us', 'terms', 'privacy'])->delete();
    }
};
