<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Charge via Stripe using the Payment Intents API.
     * Phase 5-E: Uses Laravel HTTP Client (no Cashier needed).
     */
    public function chargeStripe(Order $order, string $paymentMethodId): array
    {
        $secretKey = Setting::get('payment.stripe_secret_key') ?: config('services.stripe.secret');

        if (!$secretKey) {
            return ['success' => false, 'message' => 'Stripe is not configured.'];
        }

        try {
            // Create a PaymentIntent
            $response = Http::withBasicAuth($secretKey, '')
                ->asForm()
                ->post('https://api.stripe.com/v1/payment_intents', [
                    'amount' => (int) round($order->total * 100), // cents
                    'currency' => strtolower($order->currency?->code ?? 'usd'),
                    'payment_method' => $paymentMethodId,
                    'confirm' => 'true',
                    'automatic_payment_methods[enabled]' => 'true',
                    'automatic_payment_methods[allow_redirects]' => 'never',
                    'metadata[order_id]' => $order->id,
                    'metadata[order_number]' => $order->order_number,
                    'description' => "Order #{$order->order_number}",
                ]);

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? '') === 'succeeded') {
                // Payment succeeded
                $order->update([
                    'payment_status' => 'paid',
                    'payment_transaction_id' => $data['id'],
                    'payment_details' => json_encode([
                        'stripe_payment_intent_id' => $data['id'],
                        'stripe_charge_id' => $data['latest_charge'] ?? null,
                    ]),
                ]);

                $order->statusHistory()->create([
                    'status' => $order->status,
                    'payment_status' => 'paid',
                    'comment' => 'Payment received via Stripe',
                    'is_customer_notified' => true,
                ]);

                return ['success' => true, 'transaction_id' => $data['id']];
            }

            // Payment failed
            $errorMessage = $data['error']['message'] ?? 'Payment failed. Please try again.';
            $order->update([
                'payment_status' => 'failed',
                'payment_details' => json_encode(['error' => $errorMessage]),
            ]);

            $order->statusHistory()->create([
                'status' => $order->status,
                'payment_status' => 'failed',
                'comment' => "Stripe payment failed: {$errorMessage}",
                'is_customer_notified' => true,
            ]);

            Log::error('Stripe payment failed', [
                'order' => $order->order_number,
                'error' => $data['error'] ?? $data,
            ]);

            return ['success' => false, 'message' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('Stripe payment exception', [
                'order' => $order->order_number,
                'exception' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'A payment error occurred. Please try again.'];
        }
    }

    /**
     * Create a PayPal order using the Orders API v2.
     * Phase 5-F: Uses Laravel HTTP Client.
     */
    public function createPayPalOrder(Order $order): array
    {
        $mode = Setting::get('payment.paypal_mode', 'sandbox');
        $clientId = config('services.paypal.client_id', Setting::get('payment.paypal_client_id'));
        $secret = config('services.paypal.secret', Setting::get('payment.paypal_secret'));
        $baseUrl = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        if (!$clientId || !$secret) {
            return ['success' => false, 'message' => 'PayPal is not configured.'];
        }

        try {
            // Get access token
            $tokenResponse = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("{$baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            if (!$tokenResponse->successful()) {
                return ['success' => false, 'message' => 'PayPal authentication failed.'];
            }

            $accessToken = $tokenResponse->json('access_token');

            // Create order
            $orderResponse = Http::withToken($accessToken)
                ->post("{$baseUrl}/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'reference_id' => $order->order_number,
                            'description' => "Order #{$order->order_number}",
                            'amount' => [
                                'currency_code' => strtoupper($order->currency?->code ?? 'USD'),
                                'value' => number_format($order->total, 2, '.', ''),
                            ],
                        ],
                    ],
                    'application_context' => [
                        'return_url' => route('checkout.paypal.return', $order->order_number),
                        'cancel_url' => route('checkout.paypal.cancel', $order->order_number),
                        'brand_name' => Setting::get('general.site_name', 'Porto Shop'),
                        'user_action' => 'PAY_NOW',
                    ],
                ]);

            $data = $orderResponse->json();

            if ($orderResponse->successful() && isset($data['id'])) {
                // Find the approval URL
                $approvalUrl = collect($data['links'] ?? [])
                    ->firstWhere('rel', 'approve')['href'] ?? null;

                $order->update([
                    'payment_details' => json_encode([
                        'paypal_order_id' => $data['id'],
                    ]),
                ]);

                return [
                    'success' => true,
                    'paypal_order_id' => $data['id'],
                    'approval_url' => $approvalUrl,
                ];
            }

            return ['success' => false, 'message' => 'Failed to create PayPal order.'];
        } catch (\Exception $e) {
            Log::error('PayPal order creation failed', [
                'order' => $order->order_number,
                'exception' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'A PayPal error occurred. Please try again.'];
        }
    }

    /**
     * Capture a PayPal order after customer approval.
     */
    public function capturePayPalOrder(Order $order, string $paypalOrderId): array
    {
        $mode = Setting::get('payment.paypal_mode', 'sandbox');
        $clientId = config('services.paypal.client_id', Setting::get('payment.paypal_client_id'));
        $secret = config('services.paypal.secret', Setting::get('payment.paypal_secret'));
        $baseUrl = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        try {
            // Get access token
            $tokenResponse = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("{$baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            $accessToken = $tokenResponse->json('access_token');

            // Capture
            $captureResponse = Http::withToken($accessToken)
                ->post("{$baseUrl}/v2/checkout/orders/{$paypalOrderId}/capture");

            $data = $captureResponse->json();

            if ($captureResponse->successful() && ($data['status'] ?? '') === 'COMPLETED') {
                $captureId = $data['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;

                $order->update([
                    'payment_status' => 'paid',
                    'payment_transaction_id' => $captureId ?? $paypalOrderId,
                    'payment_details' => json_encode([
                        'paypal_order_id' => $paypalOrderId,
                        'paypal_capture_id' => $captureId,
                    ]),
                ]);

                $order->statusHistory()->create([
                    'status' => $order->status,
                    'payment_status' => 'paid',
                    'comment' => 'Payment received via PayPal',
                    'is_customer_notified' => true,
                ]);

                return ['success' => true, 'transaction_id' => $captureId];
            }

            $order->update(['payment_status' => 'failed']);

            return ['success' => false, 'message' => 'PayPal payment capture failed.'];
        } catch (\Exception $e) {
            Log::error('PayPal capture failed', [
                'order' => $order->order_number,
                'exception' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'A payment error occurred.'];
        }
    }

    /**
     * Handle Stripe webhook events.
     * Phase 5-E: payment_intent.succeeded and payment_intent.payment_failed.
     */
    public function handleStripeWebhook(array $payload): bool
    {
        $event = $payload['type'] ?? null;
        $data = $payload['data']['object'] ?? [];

        $orderId = $data['metadata']['order_id'] ?? null;
        if (!$orderId) {
            Log::warning('Stripe webhook: no order_id in metadata', $payload);
            return false;
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::warning("Stripe webhook: order not found", ['order_id' => $orderId]);
            return false;
        }

        return match ($event) {
            'payment_intent.succeeded' => $this->handleStripeSuccess($order, $data),
            'payment_intent.payment_failed' => $this->handleStripeFailure($order, $data),
            default => true,
        };
    }

    private function handleStripeSuccess(Order $order, array $data): bool
    {
        if ($order->payment_status === 'paid') {
            return true; // Already processed
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_transaction_id' => $data['id'],
        ]);

        $order->statusHistory()->create([
            'status' => $order->status,
            'payment_status' => 'paid',
            'comment' => 'Payment confirmed via Stripe webhook',
            'is_customer_notified' => false,
        ]);

        return true;
    }

    private function handleStripeFailure(Order $order, array $data): bool
    {
        $error = $data['last_payment_error']['message'] ?? 'Payment failed';

        $order->update(['payment_status' => 'failed']);

        $order->statusHistory()->create([
            'status' => $order->status,
            'payment_status' => 'failed',
            'comment' => "Stripe webhook: {$error}",
            'is_customer_notified' => false,
        ]);

        return true;
    }
}
