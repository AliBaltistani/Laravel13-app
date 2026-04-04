<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;

class DynamicMailService
{
    /**
     * Apply SMTP configuration from database settings.
     * Falls back to .env values if DB settings are empty.
     */
    public function applySmtpConfig(): void
    {
        $driver = Setting::get('mail.driver');
        $host = Setting::get('mail.host');
        $port = Setting::get('mail.port');
        $username = Setting::get('mail.username');
        $password = Setting::get('mail.password');
        $encryption = Setting::get('mail.encryption');
        $senderName = Setting::get('mail.sender_name');
        $senderEmail = Setting::get('mail.sender_email');

        // Only override if SMTP settings are configured in DB
        if ($host && $username && $password) {
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', (int) ($port ?: 587));
            Config::set('mail.mailers.smtp.username', $username);
            Config::set('mail.mailers.smtp.password', $password);
            Config::set('mail.mailers.smtp.encryption', $encryption ?: 'tls');

            // Set scheme based on encryption
            if ($encryption === 'ssl') {
                Config::set('mail.mailers.smtp.scheme', 'ssl');
            } elseif ($encryption === 'tls') {
                Config::set('mail.mailers.smtp.scheme', 'tls');
            } else {
                Config::set('mail.mailers.smtp.scheme', null);
            }
        }

        // Override sender info if configured
        if ($senderEmail) {
            Config::set('mail.from.address', $senderEmail);
        }
        if ($senderName) {
            Config::set('mail.from.name', $senderName);
        }

        // Override default mailer if driver is set
        if ($driver && in_array($driver, ['smtp', 'sendmail', 'log', 'array'])) {
            Config::set('mail.default', $driver);
        }
    }
}
