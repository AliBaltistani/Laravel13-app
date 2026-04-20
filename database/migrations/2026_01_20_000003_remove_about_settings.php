<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove About-related settings - these will be managed by Pages CMS
        $keysToRemove = [
            'about.heading',
            'about.description',
        ];

        DB::table('settings')->whereIn('key', $keysToRemove)->delete();
    }

    public function down(): void
    {
        // Restore removed About settings
        $settings = [
            ['key' => 'about.heading', 'value' => 'About Us', 'group' => 'general', 'type' => 'text', 'label' => 'About Heading'],
            ['key' => 'about.description', 'value' => 'We are a team of passionate individuals dedicated to bringing you the best online shopping experience.', 'group' => 'general', 'type' => 'textarea', 'label' => 'About Description'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
};
