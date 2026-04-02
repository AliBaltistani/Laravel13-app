<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected const CACHE_KEY = 'app_settings';

    /**
     * Get a setting value by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->loadAll();
        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value by key.
     */
    public function set(string $key, mixed $value): void
    {
        $setting = Setting::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => is_array($value) ? json_encode($value) : $value]);
        } else {
            Setting::create([
                'key' => $key,
                'value' => is_array($value) ? json_encode($value) : $value,
            ]);
        }

        $this->clearCache();
    }

    /**
     * Load all settings from cache or database.
     */
    protected function loadAll(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear the settings cache.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
