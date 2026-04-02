<?php

namespace App\Models;

use App\Services\SettingService;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type', 'label', 'description'];

    /**
     * Get a setting value by key with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return app(SettingService::class)->get($key, $default);
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value): void
    {
        app(SettingService::class)->set($key, $value);
    }
}
