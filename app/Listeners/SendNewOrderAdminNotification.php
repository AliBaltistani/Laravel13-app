<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\NewOrderAdminMail;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendNewOrderAdminNotification implements ShouldQueue
{
    public $queue = 'notifications';

    public function handle(OrderPlaced $event): void
    {
        $adminEmail = Setting::get('contact.email', config('mail.from.address'));

        if ($adminEmail) {
            Mail::to($adminEmail)->send(new NewOrderAdminMail($event->order));
        }
    }
}
