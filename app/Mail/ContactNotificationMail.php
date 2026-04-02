<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(public ContactMessage $contactMessage)
    {
        $this->onQueue('notifications');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Contact: {$this->contactMessage->subject} - from {$this->contactMessage->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-notification',
            with: [
                'contactMessage' => $this->contactMessage,
                'greeting' => 'New Contact Message',
                'siteName' => Setting::get('general.site_name', config('app.name')),
                'logoUrl' => Setting::get('general.logo') ? asset('storage/' . Setting::get('general.logo')) : null,
                'primaryColor' => Setting::get('appearance.primary_color', '#08c'),
                'address' => Setting::get('contact.address', ''),
            ],
        );
    }
}
