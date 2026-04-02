<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(
        public Order $order,
        public string $oldStatus,
        public string $comment = ''
    ) {
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order #{$this->order->order_number} - Status Updated to " . ucfirst($this->order->status),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-updated',
            with: [
                'order' => $this->order,
                'oldStatus' => $this->oldStatus,
                'comment' => $this->comment,
                'greeting' => 'Order Status Update',
                'siteName' => Setting::get('general.site_name', config('app.name')),
                'logoUrl' => Setting::get('general.logo') ? asset('storage/' . Setting::get('general.logo')) : null,
                'primaryColor' => Setting::get('appearance.primary_color', '#08c'),
                'address' => Setting::get('contact.address', ''),
                'actionUrl' => route('account.orders.show', $this->order->order_number),
                'actionText' => 'View Order',
            ],
        );
    }
}
