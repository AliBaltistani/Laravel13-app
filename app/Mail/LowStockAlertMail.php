<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(public $lowStockProducts)
    {
        $this->onQueue('notifications');
    }

    public function envelope(): Envelope
    {
        $count = count($this->lowStockProducts);
        return new Envelope(
            subject: "Low Stock Alert - {$count} Products Below Threshold",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.low-stock-alert',
            with: [
                'products' => $this->lowStockProducts,
                'greeting' => 'Low Stock Alert',
                'siteName' => Setting::get('general.site_name', config('app.name')),
                'logoUrl' => Setting::get('general.logo') ? asset('storage/' . Setting::get('general.logo')) : null,
                'primaryColor' => Setting::get('appearance.primary_color', '#08c'),
                'address' => Setting::get('contact.address', ''),
                'actionUrl' => route('admin.reports.inventory'),
                'actionText' => 'View Inventory',
            ],
        );
    }
}
