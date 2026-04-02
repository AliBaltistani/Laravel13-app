<?php

namespace App\Livewire;

use App\Models\Setting;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Models\ShippingMethod;
use App\Models\UserAddress;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CheckoutPage extends Component
{
    // Billing
    public $billing_first_name = '';
    public $billing_last_name = '';
    public $billing_address_line1 = '';
    public $billing_address_line2 = '';
    public $billing_city = '';
    public $billing_state = '';
    public $billing_postal_code = '';
    public $billing_country = 'US';
    public $billing_phone = '';
    public $billing_email = '';

    // Shipping
    public $shipToDifferentAddress = false;
    public $shipping_first_name = '';
    public $shipping_last_name = '';
    public $shipping_address_line1 = '';
    public $shipping_address_line2 = '';
    public $shipping_city = '';
    public $shipping_state = '';
    public $shipping_postal_code = '';
    public $shipping_country = 'US';
    public $shipping_phone = '';

    // Order
    public $shipping_method_id = null;
    public $payment_method = 'cod';
    public $customer_notes = '';
    public $saved_address_id = null;

    // Stripe
    public $stripe_payment_method_id = null;

    public $errorMessage = '';
    public $processing = false;

    protected $rules = [
        'billing_first_name' => 'required|string|max:120',
        'billing_last_name' => 'required|string|max:120',
        'billing_address_line1' => 'required|string|max:255',
        'billing_city' => 'required|string|max:120',
        'billing_state' => 'required|string|max:120',
        'billing_postal_code' => 'required|string|max:20',
        'billing_country' => 'required|string|max:2',
        'billing_phone' => 'nullable|string|max:30',
        'billing_email' => 'required|email|max:191',
        'payment_method' => 'required|in:cod,bank_transfer,stripe,paypal',
    ];

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->billing_first_name = $user->first_name ?? '';
            $this->billing_last_name = $user->last_name ?? '';
            $this->billing_email = $user->email;
            $this->billing_phone = $user->phone ?? '';

            // Pre-fill from default address
            $defaultAddress = $user->addresses()->where('is_default_billing', true)->first()
                ?? $user->addresses()->first();
            if ($defaultAddress) {
                $this->billing_first_name = $defaultAddress->first_name;
                $this->billing_last_name = $defaultAddress->last_name;
                $this->billing_address_line1 = $defaultAddress->address_line1;
                $this->billing_address_line2 = $defaultAddress->address_line2 ?? '';
                $this->billing_city = $defaultAddress->city;
                $this->billing_state = $defaultAddress->state;
                $this->billing_postal_code = $defaultAddress->postal_code;
                $this->billing_phone = $defaultAddress->phone ?? '';
            }
        }

        // Set default shipping method
        $methods = $this->getShippingMethods();
        if ($methods->isNotEmpty()) {
            $this->shipping_method_id = $methods->first()->id;
        }
    }

    public function loadSavedAddress($addressId)
    {
        $address = UserAddress::where('user_id', Auth::id())->find($addressId);
        if ($address) {
            $this->billing_first_name = $address->first_name;
            $this->billing_last_name = $address->last_name;
            $this->billing_address_line1 = $address->address_line1;
            $this->billing_address_line2 = $address->address_line2 ?? '';
            $this->billing_city = $address->city;
            $this->billing_state = $address->state;
            $this->billing_postal_code = $address->postal_code;
            $this->billing_phone = $address->phone ?? '';
            $this->saved_address_id = $address->id;
        }
    }

    public function placeOrder()
    {
        $this->validate();
        $this->processing = true;
        $this->errorMessage = '';

        $cart = app(CartService::class);

        if ($cart->getItemCount() === 0) {
            $this->errorMessage = 'Your cart is empty.';
            $this->processing = false;
            return;
        }

        $data = [
            'billing_first_name' => $this->billing_first_name,
            'billing_last_name' => $this->billing_last_name,
            'billing_address_line1' => $this->billing_address_line1,
            'billing_address_line2' => $this->billing_address_line2,
            'billing_city' => $this->billing_city,
            'billing_state' => $this->billing_state,
            'billing_postal_code' => $this->billing_postal_code,
            'billing_country' => $this->billing_country,
            'billing_phone' => $this->billing_phone,
            'payment_method' => $this->payment_method,
            'customer_notes' => $this->customer_notes,
            'shipping_method_id' => $this->shipping_method_id,
        ];

        if ($this->shipToDifferentAddress) {
            $data['shipping_first_name'] = $this->shipping_first_name;
            $data['shipping_last_name'] = $this->shipping_last_name;
            $data['shipping_address_line1'] = $this->shipping_address_line1;
            $data['shipping_address_line2'] = $this->shipping_address_line2;
            $data['shipping_city'] = $this->shipping_city;
            $data['shipping_state'] = $this->shipping_state;
            $data['shipping_postal_code'] = $this->shipping_postal_code;
            $data['shipping_country'] = $this->shipping_country;
            $data['shipping_phone'] = $this->shipping_phone;
        }

        $orderService = app(OrderService::class);
        $result = $orderService->placeOrder($cart, $data);

        if (!$result['success']) {
            $this->errorMessage = $result['message'];
            $this->processing = false;
            return;
        }

        $order = $result['order'];

        // Handle payment based on method
        if ($this->payment_method === 'stripe') {
            return $this->processStripePayment($order);
        }

        if ($this->payment_method === 'paypal') {
            return $this->processPayPalPayment($order);
        }

        // COD or Bank Transfer — order is placed, redirect to success
        $this->dispatch('cartUpdated');
        return redirect()->route('checkout.success', $order->order_number);
    }

    /**
     * Process Stripe payment after order is placed.
     */
    private function processStripePayment($order)
    {
        if (!$this->stripe_payment_method_id) {
            $this->errorMessage = 'Please enter your card details.';
            $this->processing = false;
            return;
        }

        $paymentService = app(PaymentService::class);
        $result = $paymentService->chargeStripe($order, $this->stripe_payment_method_id);

        if ($result['success']) {
            $this->dispatch('cartUpdated');
            return redirect()->route('checkout.success', $order->order_number);
        }

        // Payment failed — redirect to failure page
        session(['checkout_error' => $result['message'], 'failed_order' => $order->order_number]);
        return redirect()->route('checkout.failure');
    }

    /**
     * Process PayPal payment — redirect to PayPal for approval.
     */
    private function processPayPalPayment($order)
    {
        $paymentService = app(PaymentService::class);
        $result = $paymentService->createPayPalOrder($order);

        if ($result['success'] && !empty($result['approval_url'])) {
            return redirect()->away($result['approval_url']);
        }

        session(['checkout_error' => $result['message'], 'failed_order' => $order->order_number]);
        return redirect()->route('checkout.failure');
    }

    private function getShippingMethods()
    {
        return ShippingMethod::where('is_active', true)->orderBy('sort_order')->get();
    }

    /**
     * Get enabled payment methods from settings.
     */
    private function getEnabledPaymentMethods(): array
    {
        $methods = [];

        if (Setting::get('payment.cod_enabled', true)) {
            $methods['cod'] = 'Cash on Delivery';
        }
        if (Setting::get('payment.bank_transfer_enabled', false)) {
            $methods['bank_transfer'] = 'Bank Transfer';
        }
        if (Setting::get('payment.stripe_enabled', false)) {
            $methods['stripe'] = 'Credit / Debit Card (Stripe)';
        }
        if (Setting::get('payment.paypal_enabled', false)) {
            $methods['paypal'] = 'PayPal';
        }

        return $methods;
    }

    public function render()
    {
        $cart = app(CartService::class);
        $shippingMethods = $this->getShippingMethods();
        $savedAddresses = Auth::check() ? Auth::user()->addresses()->get() : collect();

        return view('livewire.checkout-page', [
            'items' => $cart->getItems(),
            'subtotal' => $cart->getSubtotal(),
            'discount' => $cart->getDiscount(),
            'shippingCost' => $cart->getShipping($this->shipping_method_id),
            'total' => $cart->getTotal($this->shipping_method_id),
            'coupon' => $cart->getCart()->coupon,
            'shippingMethods' => $shippingMethods,
            'savedAddresses' => $savedAddresses,
            'enabledPaymentMethods' => $this->getEnabledPaymentMethods(),
            'stripePublishableKey' => Setting::get('payment.stripe_publishable_key', config('services.stripe.key')),
            'codInstructions' => Setting::get('payment.cod_instructions', ''),
            'bankTransferDetails' => Setting::get('payment.bank_transfer_details', ''),
        ]);
    }
}
