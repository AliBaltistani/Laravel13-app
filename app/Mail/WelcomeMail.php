<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(public User $user)
    {
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        $siteName = Setting::get('general.site_name', config('app.name'));
        return new Envelope(
            subject: "Welcome to {$siteName}!",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'user' => $this->user,
                'greeting' => "Welcome, {$this->user->name}!",
                'siteName' => Setting::get('general.site_name', config('app.name')),
                'logoUrl' => Setting::get('general.logo') ? asset('storage/' . Setting::get('general.logo')) : null,
                'primaryColor' => Setting::get('appearance.primary_color', '#08c'),
                'address' => Setting::get('contact.address', ''),
                'actionUrl' => route('shop.index'),
                'actionText' => 'Start Shopping',
            ],
        );
    }
}
