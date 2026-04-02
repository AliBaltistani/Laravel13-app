<?php

namespace App\Http\Controllers;

use App\Models\Order;

class CheckoutController extends Controller
{
    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        // Only allow the order owner or guests to see
        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('pages.checkout-success', compact('order'));
    }
}
