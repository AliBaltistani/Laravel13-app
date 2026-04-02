<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'register_email' => 'required|email|unique:users,email',
            'register_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->register_email,
            'password' => Hash::make($request->register_password),
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('account.dashboard');
    }
}
