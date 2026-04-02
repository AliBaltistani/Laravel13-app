<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Order success page (Phase 5-H).
     */
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

    /**
     * Order failure page (Phase 5-H).
     */
    public function failure()
    {
        $errorMessage = session('checkout_error', 'An error occurred during payment.');
        $orderNumber = session('failed_order');

        return view('pages.checkout-failure', compact('errorMessage', 'orderNumber'));
    }

    /**
     * PayPal return callback (Phase 5-F).
     * Customer returns from PayPal after approving payment.
     */
    public function paypalReturn(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        // Verify ownership
        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }

        $paypalOrderId = json_decode($order->payment_details, true)['paypal_order_id'] ?? null;

        if (!$paypalOrderId) {
            return redirect()->route('checkout.failure')
                ->with('checkout_error', 'Invalid PayPal session.');
        }

        $paymentService = app(PaymentService::class);
        $result = $paymentService->capturePayPalOrder($order, $paypalOrderId);

        if ($result['success']) {
            return redirect()->route('checkout.success', $order->order_number);
        }

        session(['checkout_error' => $result['message'], 'failed_order' => $order->order_number]);
        return redirect()->route('checkout.failure');
    }

    /**
     * PayPal cancel callback (Phase 5-F).
     */
    public function paypalCancel(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $order->update(['payment_status' => 'failed']);
        $order->statusHistory()->create([
            'status' => $order->status,
            'payment_status' => 'failed',
            'comment' => 'Payment cancelled by customer on PayPal',
            'is_customer_notified' => true,
        ]);

        session(['checkout_error' => 'Payment was cancelled.', 'failed_order' => $order->order_number]);
        return redirect()->route('checkout.failure');
    }

    /**
     * Stripe webhook handler (Phase 5-E).
     */
    public function stripeWebhook(Request $request)
    {
        $payload = $request->all();

        // Optionally verify webhook signature with Stripe signing secret
        $paymentService = app(PaymentService::class);
        $paymentService->handleStripeWebhook($payload);

        return response()->json(['status' => 'ok']);
    }
}
