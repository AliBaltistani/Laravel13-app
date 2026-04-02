<?php

namespace App\Jobs;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 60, 300];

    public function __construct(
        public ContactMessage $contactMessage
    ) {
        $this->onQueue('notifications');
    }

    public function handle(): void
    {
        $adminEmail = Setting::get('contact.email', config('mail.from.address'));
        $siteName = Setting::get('general.site_name', config('app.name'));

        if (!$adminEmail) {
            return;
        }

        Mail::raw(
            "New contact message from {$this->contactMessage->name} ({$this->contactMessage->email})\n\n" .
            "Subject: {$this->contactMessage->subject}\n" .
            "Phone: {$this->contactMessage->phone}\n\n" .
            "Message:\n{$this->contactMessage->message}",
            function ($mail) use ($adminEmail, $siteName) {
                $mail->to($adminEmail)
                    ->subject("New Contact Message: {$this->contactMessage->subject} - {$siteName}");
            }
        );
    }
}
