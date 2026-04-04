<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetOtp extends Model
{
    public $timestamps = false;

    protected $fillable = ['email', 'otp', 'attempts', 'expires_at'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Generate a new OTP for the given email.
     * Returns the plain-text OTP (6-digit code).
     */
    public static function generateOtp(string $email): string
    {
        // Remove any existing OTPs for this email
        static::where('email', $email)->delete();

        $plainOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $expiryMinutes = (int) Setting::get('auth.otp_expiry_minutes', 10);

        static::create([
            'email' => $email,
            'otp' => Hash::make($plainOtp),
            'attempts' => 0,
            'expires_at' => now()->addMinutes($expiryMinutes),
            'created_at' => now(),
        ]);

        return $plainOtp;
    }

    /**
     * Verify OTP for the given email.
     * Returns true if valid, false otherwise.
     */
    public static function verifyOtp(string $email, string $otp): bool
    {
        $record = static::where('email', $email)->first();

        if (!$record) {
            return false;
        }

        // Check expiry
        if ($record->expires_at->isPast()) {
            $record->delete();
            return false;
        }

        // Check max attempts
        $maxAttempts = (int) Setting::get('auth.max_otp_attempts', 5);
        if ($record->attempts >= $maxAttempts) {
            $record->delete();
            return false;
        }

        // Verify OTP hash
        if (!Hash::check($otp, $record->otp)) {
            $record->increment('attempts');
            return false;
        }

        return true;
    }

    /**
     * Consume the OTP (delete after successful verification + password reset).
     */
    public static function consumeOtp(string $email): void
    {
        static::where('email', $email)->delete();
    }

    /**
     * Check if a recent OTP exists (for cooldown).
     */
    public static function hasRecentOtp(string $email): bool
    {
        $cooldown = (int) Setting::get('auth.otp_cooldown_seconds', 60);

        return static::where('email', $email)
            ->where('created_at', '>=', now()->subSeconds($cooldown))
            ->exists();
    }

    /**
     * Clean expired OTPs.
     */
    public static function cleanExpired(): int
    {
        return static::where('expires_at', '<', now())->delete();
    }
}
