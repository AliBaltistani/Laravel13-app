<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;

class PasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public int $expiryMinutes;
    public string $siteName;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
        $this->expiryMinutes = (int) Setting::get('auth.otp_expiry_minutes', 10);
        $this->siteName = Setting::get('general.site_name', 'Porto Shop');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset OTP - ' . $this->siteName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-otp',
        );
    }
}
