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

class OrderShippedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(
        public Order $order,
        public ?string $trackingNumber = null,
        public ?string $carrier = null,
        public ?string $trackingUrl = null,
    ) {
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your Order #{$this->order->order_number} Has Been Shipped!",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-shipped',
            with: [
                'order' => $this->order,
                'trackingNumber' => $this->trackingNumber,
                'carrier' => $this->carrier,
                'trackingUrl' => $this->trackingUrl,
                'greeting' => 'Your order is on its way!',
                'siteName' => Setting::get('general.site_name', config('app.name')),
                'logoUrl' => Setting::get('general.logo') ? asset('storage/' . Setting::get('general.logo')) : null,
                'primaryColor' => Setting::get('appearance.primary_color', '#08c'),
                'address' => Setting::get('contact.address', ''),
                'actionUrl' => $this->trackingUrl,
                'actionText' => 'Track Shipment',
            ],
        );
    }
}
