<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('customer')->withCount('orders')->withSum('orders', 'total');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query->latest()->paginate(20)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => fn($q) => $q->latest()->take(20), 'addresses', 'reviews']);
        $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('total');
        return view('admin.customers.show', compact('user', 'totalSpent'));
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'banned';
        return back()->with('success', "Customer {$status}.");
    }

    public function sendEmail(Request $request, User $user)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Queue a basic email
        \Illuminate\Support\Facades\Mail::raw($request->message, function ($mail) use ($user, $request) {
            $mail->to($user->email)->subject($request->subject);
        });

        return back()->with('success', 'Email sent to ' . $user->email);
    }
}
