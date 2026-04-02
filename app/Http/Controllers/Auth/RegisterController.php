<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
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
                ['name' => $user->name, 'token' => \Illuminate\Support\Str::random(64)]
            );
        }

        Auth::login($user);

        return redirect()->route('account.dashboard')->with('success', 'Welcome! Your account has been created.');
    }
}
