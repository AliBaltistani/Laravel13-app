<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\PasswordResetOtp;
use App\Models\Setting;
use App\Models\User;
use App\Services\DynamicMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form (Step 1: Enter email).
     */
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to email (Step 1 → Step 2).
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->input('email');

        // Rate limiting: 3 attempts per minute per IP
        $throttleKey = 'otp-send|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors(['email' => "Too many attempts. Please try again in {$seconds} seconds."])->onlyInput('email');
        }

        // Check if user exists
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Don't reveal if email exists - always show success
            return redirect()->route('password.otp.form', ['email' => $email])
                ->with('status', 'If an account with that email exists, we have sent an OTP code.');
        }

        // Check cooldown
        if (PasswordResetOtp::hasRecentOtp($email)) {
            $cooldown = (int) Setting::get('auth.otp_cooldown_seconds', 60);
            return back()->withErrors(['email' => "Please wait {$cooldown} seconds before requesting another OTP."])->onlyInput('email');
        }

        // Apply dynamic SMTP config
        app(DynamicMailService::class)->applySmtpConfig();

        // Generate and send OTP
        $otp = PasswordResetOtp::generateOtp($email);

        try {
            Mail::to($email)->send(new PasswordResetOtpMail($otp));
        } catch (\Exception $e) {
            // Log the error but don't reveal it to user
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
        }

        RateLimiter::hit($throttleKey);

        return redirect()->route('password.otp.form', ['email' => $email])
            ->with('status', 'We have sent a verification code to your email address.');
    }

    /**
     * Show OTP verification form (Step 2).
     */
    public function showOtpForm(Request $request)
    {
        $email = $request->query('email', old('email'));

        if (!$email) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', ['email' => $email]);
    }

    /**
     * Verify OTP (Step 2 → Step 3).
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $email = $request->input('email');
        $otp = $request->input('otp');

        if (!PasswordResetOtp::verifyOtp($email, $otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP code. Please try again.'])->withInput();
        }

        // Generate a temporary token for the reset form
        $token = \Illuminate\Support\Str::random(64);
        session()->put('password_reset_verified', [
            'email' => $email,
            'token' => $token,
            'verified_at' => now()->timestamp,
        ]);

        return redirect()->route('password.reset', ['token' => $token, 'email' => $email]);
    }

    /**
     * Resend OTP.
     */
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->input('email');

        // Check cooldown
        if (PasswordResetOtp::hasRecentOtp($email)) {
            $cooldown = (int) Setting::get('auth.otp_cooldown_seconds', 60);
            return back()->withErrors(['otp' => "Please wait {$cooldown} seconds before requesting another OTP."]);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            app(DynamicMailService::class)->applySmtpConfig();
            $otp = PasswordResetOtp::generateOtp($email);

            try {
                Mail::to($email)->send(new PasswordResetOtpMail($otp));
            } catch (\Exception $e) {
                \Log::error('Failed to resend OTP email: ' . $e->getMessage());
            }
        }

        return back()->with('status', 'A new verification code has been sent to your email.')->withInput();
    }
}
