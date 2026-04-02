<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail implements ShouldQueue
{
    public function handle(UserRegistered $event): void
    {
        // TODO: Send welcome email notification
        Log::info('Welcome email queued for: ' . $event->user->email);
    }
}
