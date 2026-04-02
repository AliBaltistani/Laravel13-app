<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * OrderStatusNotification — Phase 10-D
 * Stored in the database notifications table for in-app display.
 */
class OrderStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $message,
        public string $type = 'order_status'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'message' => $this->message,
            'url' => route('account.orders.show', $this->order->order_number),
        ];
    }
}
