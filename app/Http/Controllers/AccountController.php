<?php

namespace App\Http\Controllers;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $recentOrders = $user->orders()->latest()->take(5)->get();

        return view('account.dashboard', compact('user', 'recentOrders'));
    }
}
