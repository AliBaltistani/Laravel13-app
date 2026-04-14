<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Remove duplicate logo/favicon (general.logo & general.favicon are duplicated by appearance.logo & appearance.favicon)
        DB::table('settings')->whereIn('key', [
            'general.logo',
            'general.favicon',
        ])->delete();

        // 2. Remove unused google_oauth_enabled
        DB::table('settings')->where('key', 'google_oauth_enabled')->delete();

        // 3. Rename mismatched SEO keys to match what the code actually reads
        DB::table('settings')->where('key', 'seo.meta_title')
            ->update(['key' => 'seo.default_meta_title']);
        DB::table('settings')->where('key', 'seo.meta_description')
            ->update(['key' => 'seo.default_meta_description']);

        // 4. Add seo.default_og_image if not exists
        if (!DB::table('settings')->where('key', 'seo.default_og_image')->exists()) {
            DB::table('settings')->insert([
                'key' => 'seo.default_og_image',
                'value' => null,
                'group' => 'seo',
                'type' => 'image',
                'label' => 'Default OG Image',
                'description' => 'Fallback image for social media sharing when no specific image is available.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 5. Remove all obsolete home.* settings (homepage is now managed by HomepageSection model)
        DB::table('settings')->where('key', 'like', 'home.%')->delete();

        // 6. Remove unused promo link fields and duplicate promo bar color
        DB::table('settings')->whereIn('key', [
            'promo.bar_bg_color',
            'promo.bar_link1_label',
            'promo.bar_link1_url',
            'promo.bar_link2_label',
            'promo.bar_link2_url',
        ])->delete();

        // 7. Add promo.bar_note if not exists (used by top-notice.blade.php)
        if (!DB::table('settings')->where('key', 'promo.bar_note')->exists()) {
            DB::table('settings')->insert([
                'key' => 'promo.bar_note',
                'value' => '',
                'group' => 'promo',
                'type' => 'text',
                'label' => 'Promo Bar Note',
                'description' => 'Optional smaller text below the main promo text.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 8. Remove unused footer.service_links
        DB::table('settings')->where('key', 'footer.service_links')->delete();

        // 9. Add missing payment settings that the code references
        $missingPayment = [
            ['key' => 'payment.stripe_mode', 'value' => 'test', 'group' => 'payment', 'type' => 'select', 'label' => 'Stripe Mode'],
            ['key' => 'payment.stripe_secret_key', 'value' => '', 'group' => 'payment', 'type' => 'password', 'label' => 'Stripe Secret Key'],
            ['key' => 'payment.stripe_webhook_secret', 'value' => '', 'group' => 'payment', 'type' => 'password', 'label' => 'Stripe Webhook Secret'],
            ['key' => 'payment.paypal_client_id', 'value' => '', 'group' => 'payment', 'type' => 'text', 'label' => 'PayPal Client ID'],
            ['key' => 'payment.paypal_secret', 'value' => '', 'group' => 'payment', 'type' => 'password', 'label' => 'PayPal Secret Key'],
        ];

        foreach ($missingPayment as $row) {
            if (!DB::table('settings')->where('key', $row['key'])->exists()) {
                DB::table('settings')->insert(array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // Clear settings cache
        \Illuminate\Support\Facades\Cache::forget('app_settings');
    }

    public function down(): void
    {
        // This cleanup migration is not reversible in a meaningful way
        // The seeder can be re-run after a fresh migration to restore defaults
    }
};
