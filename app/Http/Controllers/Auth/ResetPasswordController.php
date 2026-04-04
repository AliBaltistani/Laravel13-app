<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Show the reset password form (Step 3 — after OTP verification).
     */
    public function showForm(Request $request, string $token)
    {
        $verified = session('password_reset_verified');

        // Validate session token
        if (!$verified || $verified['token'] !== $token) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Invalid or expired reset session. Please start over.']);
        }

        // Check if session is still valid (max 15 minutes)
        if (now()->timestamp - $verified['verified_at'] > 900) {
            session()->forget('password_reset_verified');
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Reset session expired. Please start over.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $verified['email'],
        ]);
    }

    /**
     * Reset the password.
     */
    public function reset(Request $request)
    {
        $minPassword = (int) Setting::get('auth.password_min_length', 8);

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => "required|string|min:{$minPassword}|confirmed",
        ]);

        $verified = session('password_reset_verified');

        // Validate session
        if (!$verified || $verified['token'] !== $request->token || $verified['email'] !== $request->email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Invalid reset session. Please start over.']);
        }

        // Find and update user
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'No account found with that email address.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->setRememberToken(Str::random(60));
        $user->save();

        // Clean up
        PasswordResetOtp::consumeOtp($request->email);
        session()->forget('password_reset_verified');

        return redirect()->route('login')->with('status', 'Your password has been reset successfully! Please log in with your new password.');
    }
}
