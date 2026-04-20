<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Delete orphan settings (unused in any view)
        Setting::whereIn('key', ['general.footer_about', 'general.footer_logo'])->delete();

        // 2. Improve labels for product tab settings in general group
        Setting::where('key', 'product.tab_shipping_content')
            ->update(['label' => 'Product: Shipping Tab Content', 'description' => 'Default content for the Shipping tab on product pages']);
        Setting::where('key', 'product.tab_return_content')
            ->update(['label' => 'Product: Returns Tab Content', 'description' => 'Default content for the Returns tab on product pages']);

        // 3. Create missing footer column title settings
        $footerSettings = [
            ['key' => 'footer.col1_title', 'value' => 'CONTACT INFO', 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Column 1 Title', 'description' => 'Title for the contact info column'],
            ['key' => 'footer.col2_title', 'value' => 'CUSTOMER SERVICE', 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Column 2 Title', 'description' => 'Title for the customer service links column'],
            ['key' => 'footer.col3_title', 'value' => 'INFORMATION', 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Column 3 Title', 'description' => 'Title for the information/CMS pages column'],
        ];

        foreach ($footerSettings as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }

        // 4. Clear settings cache
        app(\App\Services\SettingService::class)->clearCache();
    }

    public function down(): void
    {
        // Re-create orphan settings if needed
        Setting::firstOrCreate(['key' => 'general.footer_about'], [
            'key' => 'general.footer_about', 'value' => '', 'group' => 'general',
            'type' => 'textarea', 'label' => 'Footer About Text',
        ]);
        Setting::firstOrCreate(['key' => 'general.footer_logo'], [
            'key' => 'general.footer_logo', 'value' => '', 'group' => 'general',
            'type' => 'image', 'label' => 'Footer Logo',
        ]);

        // Remove added footer settings
        Setting::whereIn('key', ['footer.col1_title', 'footer.col2_title', 'footer.col3_title'])->delete();

        app(\App\Services\SettingService::class)->clearCache();
    }
};
