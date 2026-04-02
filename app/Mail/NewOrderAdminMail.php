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

class NewOrderAdminMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(public Order $order)
    {
        $this->onQueue('notifications');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Order #{$this->order->order_number} - $" . number_format($this->order->total, 2),
        );
    }

    public function content(): Content
    {
        $this->order->load('items');
        return new Content(
            view: 'emails.new-order-admin',
            with: [
                'order' => $this->order,
                'greeting' => 'New Order Received!',
                'siteName' => Setting::get('general.site_name', config('app.name')),
                'logoUrl' => Setting::get('general.logo') ? asset('storage/' . Setting::get('general.logo')) : null,
                'primaryColor' => Setting::get('appearance.primary_color', '#08c'),
                'address' => Setting::get('contact.address', ''),
                'actionUrl' => route('admin.orders.show', $this->order->id),
                'actionText' => 'View in Admin',
            ],
        );
    }
}
