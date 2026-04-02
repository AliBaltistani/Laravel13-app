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

class OrderConfirmedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 60, 300];

    public function __construct(public Order $order)
    {
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        $siteName = Setting::get('general.site_name', config('app.name'));
        return new Envelope(
            subject: "Order Confirmed - #{$this->order->order_number} - {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmed',
            with: $this->emailData(),
        );
    }

    private function emailData(): array
    {
        $this->order->load('items');
        return [
            'order' => $this->order,
            'greeting' => 'Thank you for your order!',
            'siteName' => Setting::get('general.site_name', config('app.name')),
            'logoUrl' => Setting::get('general.logo') ? asset('storage/' . Setting::get('general.logo')) : null,
            'primaryColor' => Setting::get('appearance.primary_color', '#08c'),
            'address' => Setting::get('contact.address', ''),
            'actionUrl' => auth()->check() ? route('account.orders.show', $this->order->order_number) : null,
            'actionText' => 'View Order',
        ];
    }
}
