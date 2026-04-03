<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Observers\CategoryObserver;
use App\Observers\PageObserver;
use App\Observers\PostObserver;
use App\Observers\ProductObserver;
use App\Services\CartService;
use App\Services\SeoService;
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
        // Settings service — Phase 1-D
        $this->app->singleton(SettingService::class, function () {
            return new SettingService();
        });

        // Cart service — Phase 5-A
        $this->app->singleton(CartService::class, function () {
            return new CartService();
        });

        // SEO service — Phase 9-A (scoped per request, not singleton)
        $this->app->scoped(SeoService::class, function () {
            return new SeoService();
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

        // Register @price($amount) Blade directive — formats with admin currency
        Blade::directive('price', function ($expression) {
            return "<?php echo \App\Helpers\CurrencyHelper::format({$expression}); ?>";
        });

        // Register model observers — Phase 9-F
        Product::observe(ProductObserver::class);
        Category::observe(CategoryObserver::class);
        Post::observe(PostObserver::class);
        Page::observe(PageObserver::class);
    }
}
