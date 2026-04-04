<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showForm()
    {
        // Check if registration is enabled
        if (!Setting::get('auth.registration_enabled', true)) {
            abort(403, 'Registration is currently disabled.');
        }

        return view('auth.register');
    }

    /**
     * Handle registration with rate limiting.
     */
    public function register(Request $request)
    {
        // Check if registration is enabled
        if (!Setting::get('auth.registration_enabled', true)) {
            abort(403, 'Registration is currently disabled.');
        }

        $minPassword = (int) Setting::get('auth.password_min_length', 8);
        $termsRequired = (bool) Setting::get('auth.terms_required', true);

        // Rate limiting: 3 registration attempts per minute per IP
        $throttleKey = 'register|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Too many registration attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => "required|string|min:{$minPassword}|confirmed",
        ];

        if ($termsRequired) {
            $rules['terms'] = 'required|accepted';
        }

        $messages = [
            'terms.required' => 'You must accept the Terms & Conditions.',
            'terms.accepted' => 'You must accept the Terms & Conditions.',
        ];

        $request->validate($rules, $messages);

        RateLimiter::hit($throttleKey);

        $user = User::create([
            'name' => $request->name,
            'first_name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        $user->assignRole('customer');

        event(new UserRegistered($user));

        // Subscribe to newsletter if checked
        if ($request->boolean('newsletter')) {
            \App\Models\NewsletterSubscriber::firstOrCreate(
                ['email' => $user->email],
                ['name' => $user->name, 'token' => Str::random(64)]
            );
        }

        Auth::login($user);

        return redirect()->route('account.dashboard')->with('success', 'Welcome! Your account has been created.');
    }
}
