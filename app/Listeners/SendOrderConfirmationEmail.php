<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\NewOrderAdminMail;
use App\Mail\OrderConfirmedMail;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    public $queue = 'emails';

    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;

        // Send to customer
        $customerEmail = $order->user?->email ?? $order->billing_email ?? null;
        if ($customerEmail) {
            Mail::to($customerEmail)->send(new OrderConfirmedMail($order));
        }
    }
}
