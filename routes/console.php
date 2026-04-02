<?php

use App\Mail\LowStockAlertMail;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\FlashSale;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes & Scheduled Tasks — Phase 12-B
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Hourly: Expire flash sales that have passed expires_at
Schedule::call(function () {
    FlashSale::where('is_active', true)
        ->where('expires_at', '<', now())
        ->update(['is_active' => false]);
})->hourly()->name('expire-flash-sales')->withoutOverlapping();

// Hourly: Expire coupons that have passed expires_at
Schedule::call(function () {
    Coupon::where('is_active', true)
        ->whereNotNull('expires_at')
        ->where('expires_at', '<', now())
        ->update(['is_active' => false]);
})->hourly()->name('expire-coupons')->withoutOverlapping();

// Daily at midnight: Prune Telescope entries older than 7 days
Schedule::command('telescope:prune --hours=168')
    ->daily()
    ->name('prune-telescope')
    ->withoutOverlapping()
    ->when(function () {
        return class_exists(\Laravel\Telescope\TelescopeServiceProvider::class);
    });

// Daily at 6am: Send low stock report to admin
Schedule::call(function () {
    $lowStockProducts = Product::where('manage_stock', true)
        ->where('is_active', true)
        ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
        ->get();

    if ($lowStockProducts->isNotEmpty()) {
        $adminEmail = Setting::get('contact.email', config('mail.from.address'));
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new LowStockAlertMail($lowStockProducts));
        }
    }
})->dailyAt('06:00')->name('low-stock-report')->withoutOverlapping();

// Weekly on Sunday: Prune read contact messages older than 90 days
Schedule::call(function () {
    ContactMessage::where('is_read', true)
        ->where('created_at', '<', now()->subDays(90))
        ->delete();
})->weekly()->sundays()->at('02:00')->name('prune-contact-messages')->withoutOverlapping();
