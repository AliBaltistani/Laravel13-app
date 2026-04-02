<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Mail\OrderStatusUpdatedMail;
use App\Mail\OrderShippedMail;
use App\Notifications\OrderStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusEmail implements ShouldQueue
{
    public $queue = 'emails';

    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;
        $user = $order->user;

        // Always send in-app notification if user exists
        if ($user) {
            $statusLabel = ucfirst($order->status);
            $user->notify(new OrderStatusNotification(
                $order,
                "Your order #{$order->order_number} status changed to {$statusLabel}."
            ));
        }

        // Only send email if notifyCustomer is true
        if (!$event->notifyCustomer || !$user?->email) {
            return;
        }

        $customerEmail = $user->email;

        // If shipped, send shipping-specific email
        if ($order->status === 'shipped') {
            $shipment = $order->shipments()->latest()->first();
            Mail::to($customerEmail)->send(new OrderShippedMail(
                $order,
                $shipment?->tracking_number,
                $shipment?->carrier,
                $shipment?->tracking_url
            ));
            return;
        }

        // Generic status update
        Mail::to($customerEmail)->send(new OrderStatusUpdatedMail(
            $order,
            $event->oldStatus,
            $event->comment
        ));
    }
}
