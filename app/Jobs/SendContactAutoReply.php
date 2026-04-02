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

class SendContactAutoReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 60, 300];

    public function __construct(
        public ContactMessage $contactMessage
    ) {
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        $siteName = Setting::get('general.site_name', config('app.name'));
        $autoReplySubject = Setting::get('contact.auto_reply_subject', "Thank you for contacting {$siteName}");
        $autoReplyBody = Setting::get(
            'contact.auto_reply_body',
            "Dear {$this->contactMessage->name},\n\n" .
            "Thank you for reaching out to us. We have received your message and will get back to you as soon as possible.\n\n" .
            "Best regards,\nThe {$siteName} Team"
        );

        // Replace placeholders
        $autoReplyBody = str_replace(
            ['{name}', '{site_name}'],
            [$this->contactMessage->name, $siteName],
            $autoReplyBody
        );

        Mail::raw($autoReplyBody, function ($mail) use ($autoReplySubject, $siteName) {
            $mail->to($this->contactMessage->email)
                ->subject($autoReplySubject);
        });
    }
}
