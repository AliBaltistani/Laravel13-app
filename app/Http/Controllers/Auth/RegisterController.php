<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
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
        return view('auth.login'); // Porto uses a combined login/register page
    }

    /**
     * Handle registration with rate limiting.
     * Phase 3-A: 3 registration attempts per minute per IP.
     * Phase 3-B: Fire UserRegistered event, dispatch welcome email via queue.
     */
    public function register(RegisterRequest $request)
    {
        // Rate limiting: 3 registration attempts per minute per IP
        $throttleKey = 'register|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'register_email' => "Too many registration attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        RateLimiter::hit($throttleKey);

        $user = User::create([
            'name' => $request->name,
            'first_name' => $request->name,
            'email' => $request->register_email,
            'password' => Hash::make($request->register_password),
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
