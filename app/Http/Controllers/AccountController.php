<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserAddress;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{

    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('account.dashboard', compact('user', 'recentOrders'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('order_number', $orderNumber)
            ->with(['items', 'statusHistory', 'shipments'])
            ->firstOrFail();

        return view('account.order-detail', compact('order'));
    }

    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending'])) {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        $order->update(['status' => 'cancelled']);
        $order->statusHistory()->create([
            'status' => 'cancelled',
            'payment_status' => $order->payment_status,
            'comment' => 'Order cancelled by customer',
            'is_customer_notified' => true,
        ]);

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product && $item->product->manage_stock) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        return back()->with('success', 'Order has been cancelled.');
    }

    public function downloadInvoice(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $orderService = app(OrderService::class);
        return $orderService->generateInvoice($order);
    }

    public function addresses()
    {
        $addresses = Auth::user()->addresses()->get();
        return view('account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'first_name' => 'required|string|max:120',
            'last_name' => 'required|string|max:120',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:120',
            'state' => 'required|string|max:120',
            'postal_code' => 'required|string|max:20',
            'phone' => 'nullable|string|max:30',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['country_id'] = 1;

        if (Auth::user()->addresses()->count() === 0) {
            $validated['is_default_shipping'] = true;
            $validated['is_default_billing'] = true;
        }

        UserAddress::create($validated);

        return back()->with('success', 'Address added successfully.');
    }

    public function updateAddress(Request $request, UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'first_name' => 'required|string|max:120',
            'last_name' => 'required|string|max:120',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:120',
            'state' => 'required|string|max:120',
            'postal_code' => 'required|string|max:20',
            'phone' => 'nullable|string|max:30',
        ]);

        $address->update($validated);
        return back()->with('success', 'Address updated.');
    }

    public function deleteAddress(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        $address->delete();
        return back()->with('success', 'Address removed.');
    }

    public function setDefaultAddress(UserAddress $address, string $type)
    {
        if ($address->user_id !== Auth::id()) abort(403);

        $field = $type === 'shipping' ? 'is_default_shipping' : 'is_default_billing';

        // Reset all
        Auth::user()->addresses()->update([$field => false]);
        $address->update([$field => true]);

        return back()->with('success', ucfirst($type) . ' address set as default.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:120',
            'last_name' => 'required|string|max:120',
            'email' => 'required|email|max:191|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
        ]);

        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function wishlist()
    {
        $wishlistItems = \App\Models\Wishlist::where('user_id', Auth::id())
            ->with(['product.images', 'product.category'])
            ->latest('added_at')
            ->get();

        return view('account.wishlist', compact('wishlistItems'));
    }
}
