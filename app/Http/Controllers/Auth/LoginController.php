<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login/register form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request with rate limiting.
     * Phase 3-A: 5 failed login attempts per minute per IP.
     * Phase 3-C: Merge guest cart on login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Rate limiting: 5 attempts per minute per IP
        $throttleKey = Str::transliterate(
            Str::lower($request->input('email')) . '|' . $request->ip()
        );

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            // Merge guest cart into user cart (Phase 3-C)
            $sessionId = $request->session()->getId();
            app(CartService::class)->mergeGuestCart($sessionId, Auth::id());

            // Store intended URL for redirect
            return redirect()->intended(route('account.dashboard'));
        }

        RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout: invalidate session, regenerate CSRF, redirect home.
     * Phase 3-A.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
