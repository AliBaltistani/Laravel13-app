<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterBroadcastMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(
        public NewsletterSubscriber $subscriber,
        public string $emailSubject,
        public string $emailBody
    ) {
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->emailSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter-broadcast',
            with: [
                'subscriber' => $this->subscriber,
                'body' => $this->emailBody,
                'greeting' => "Hello, {$this->subscriber->name}!",
                'siteName' => Setting::get('general.site_name', config('app.name')),
                'logoUrl' => Setting::get('general.logo') ? asset('storage/' . Setting::get('general.logo')) : null,
                'primaryColor' => Setting::get('appearance.primary_color', '#08c'),
                'address' => Setting::get('contact.address', ''),
                'unsubscribeUrl' => url("/newsletter/unsubscribe/{$this->subscriber->token}"),
            ],
        );
    }
}
