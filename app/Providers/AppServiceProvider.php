<?php

namespace App\Providers;

use App\Services\SettingService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingService::class, function () {
            return new SettingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(195);

        // Register Setting as a global alias for Blade templates
        if (!class_exists('Setting')) {
            class_alias(\App\Models\Setting::class, 'Setting');
        }

        // Register @setting('key') Blade directive
        Blade::directive('setting', function ($expression) {
            return "<?php echo e(app(\App\Services\SettingService::class)->get({$expression})); ?>";
        });
    }
}
