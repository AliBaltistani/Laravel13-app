<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            // Auth group
            ['key' => 'auth.registration_enabled', 'value' => '1', 'group' => 'auth', 'type' => 'boolean', 'label' => 'Enable Registration', 'description' => 'Allow new users to register on the site'],
            ['key' => 'auth.terms_required', 'value' => '1', 'group' => 'auth', 'type' => 'boolean', 'label' => 'Require Terms Acceptance', 'description' => 'Require users to accept Terms & Conditions during registration'],
            ['key' => 'auth.password_min_length', 'value' => '8', 'group' => 'auth', 'type' => 'number', 'label' => 'Minimum Password Length', 'description' => 'Minimum number of characters for passwords'],
            ['key' => 'auth.otp_expiry_minutes', 'value' => '10', 'group' => 'auth', 'type' => 'number', 'label' => 'OTP Expiry (Minutes)', 'description' => 'How many minutes before a password reset OTP expires'],
            ['key' => 'auth.max_otp_attempts', 'value' => '5', 'group' => 'auth', 'type' => 'number', 'label' => 'Max OTP Attempts', 'description' => 'Maximum number of wrong OTP entries before code is invalidated'],
            ['key' => 'auth.login_max_attempts', 'value' => '5', 'group' => 'auth', 'type' => 'number', 'label' => 'Max Login Attempts', 'description' => 'Maximum failed login attempts per minute before lockout'],
            ['key' => 'auth.otp_cooldown_seconds', 'value' => '60', 'group' => 'auth', 'type' => 'number', 'label' => 'OTP Resend Cooldown (Seconds)', 'description' => 'Seconds to wait before allowing OTP resend'],

            // Legal group
            ['key' => 'legal.terms_content', 'value' => '<h2>Terms and Conditions</h2><p>Welcome to our website. By accessing and using this website, you accept and agree to be bound by the terms and conditions set forth below.</p><h3>1. Use of the Site</h3><p>You may use our site for lawful purposes only. You must not use our site in any way that causes, or may cause, damage to the site or impairment of the availability or accessibility of the site.</p><h3>2. Intellectual Property</h3><p>All content included on this site, such as text, graphics, logos, images, and software, is the property of our company and protected by copyright laws.</p><h3>3. Products and Services</h3><p>We reserve the right to modify or discontinue any product or service without notice. Prices are subject to change without notice.</p><h3>4. Orders and Payment</h3><p>By placing an order, you agree to provide accurate and complete information. We reserve the right to refuse or cancel any order.</p><h3>5. Shipping and Delivery</h3><p>Delivery times are estimates and not guaranteed. We are not responsible for delays caused by shipping carriers.</p><h3>6. Returns and Refunds</h3><p>Our return policy allows returns within 30 days of purchase. Items must be in original condition.</p><h3>7. Limitation of Liability</h3><p>We shall not be liable for any indirect, incidental, or consequential damages arising from your use of our site or products.</p><h3>8. Changes to Terms</h3><p>We reserve the right to update these terms at any time. Continued use of the site constitutes acceptance of modified terms.</p>', 'group' => 'legal', 'type' => 'richtext', 'label' => 'Terms & Conditions Content', 'description' => 'Full HTML content for the Terms & Conditions page'],
            ['key' => 'legal.privacy_content', 'value' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information.</p><h3>1. Information We Collect</h3><p>We collect information you provide directly, such as your name, email address, shipping address, and payment information when you create an account or place an order.</p><h3>2. How We Use Your Information</h3><p>We use your information to process orders, communicate with you, improve our services, and send promotional materials (with your consent).</p><h3>3. Information Sharing</h3><p>We do not sell your personal information. We may share information with trusted third parties who assist us in operating our website and conducting business.</p><h3>4. Data Security</h3><p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, or destruction.</p><h3>5. Cookies</h3><p>Our website uses cookies to enhance your browsing experience. You can choose to disable cookies through your browser settings.</p><h3>6. Your Rights</h3><p>You have the right to access, correct, or delete your personal information. Contact us to exercise these rights.</p><h3>7. Changes to This Policy</h3><p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page.</p><h3>8. Contact Us</h3><p>If you have any questions about this privacy policy, please contact us through our contact page.</p>', 'group' => 'legal', 'type' => 'richtext', 'label' => 'Privacy Policy Content', 'description' => 'Full HTML content for the Privacy Policy page'],
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

    public function down(): void
    {
        $keys = [
            'auth.registration_enabled', 'auth.terms_required', 'auth.password_min_length',
            'auth.otp_expiry_minutes', 'auth.max_otp_attempts', 'auth.login_max_attempts', 'auth.otp_cooldown_seconds',
            'legal.terms_content', 'legal.privacy_content', 'legal.terms_updated_at', 'legal.privacy_updated_at',
            'legal.cookie_consent_enabled', 'legal.cookie_consent_text',
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
