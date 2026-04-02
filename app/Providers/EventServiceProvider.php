<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Events\UserRegistered;
use App\Listeners\SendNewOrderAdminNotification;
use App\Listeners\SendOrderConfirmationEmail;
use App\Listeners\SendOrderStatusEmail;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     * Phase 10: All event→listener→mail dispatch wiring.
     */
    protected $listen = [
        UserRegistered::class => [
            SendWelcomeEmail::class,
        ],
        OrderPlaced::class => [
            SendOrderConfirmationEmail::class,
            SendNewOrderAdminNotification::class,
        ],
        OrderStatusChanged::class => [
            SendOrderStatusEmail::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
