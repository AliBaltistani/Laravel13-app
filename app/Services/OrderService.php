<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    /**
     * Place an order from the cart.
     */
    public function placeOrder(CartService $cart, array $data): array
    {
        return DB::transaction(function () use ($cart, $data) {
            $items = $cart->getItems();

            if ($items->isEmpty()) {
                return ['success' => false, 'message' => 'Your cart is empty.'];
            }

            // Final stock validation
            foreach ($items as $item) {
                $product = $item->product;
                if ($product->manage_stock && !$product->allow_backorder) {
                    if ($product->stock_quantity < $item->quantity) {
                        return [
                            'success' => false,
                            'message' => "{$product->name} only has {$product->stock_quantity} units in stock. Please update your cart.",
                        ];
                    }
                }
            }

            $subtotal = $cart->getSubtotal();
            $discount = $cart->getDiscount();
            $shippingMethodId = $data['shipping_method_id'] ?? null;
            $shipping = $cart->getShipping($shippingMethodId);
            $tax = $cart->getTax();
            $total = $subtotal - $discount + $shipping + $tax;

            $coupon = $cart->getCart()->coupon;
            $shippingMethod = $shippingMethodId ? \App\Models\ShippingMethod::find($shippingMethodId) : null;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $data['payment_method'] ?? 'cod',
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'coupon_code' => $coupon?->code,
                'shipping_amount' => $shipping,
                'shipping_method_name' => $shippingMethod?->name ?? 'Standard Shipping',
                'tax_amount' => $tax,
                'total' => $total,
                'customer_notes' => $data['customer_notes'] ?? null,
                'ip_address' => request()->ip(),
                'currency_id' => 1,
                'exchange_rate' => 1.00,
                // Billing
                'billing_first_name' => $data['billing_first_name'] ?? '',
                'billing_last_name' => $data['billing_last_name'] ?? '',
                'billing_address_line1' => $data['billing_address_line1'] ?? '',
                'billing_address_line2' => $data['billing_address_line2'] ?? null,
                'billing_city' => $data['billing_city'] ?? '',
                'billing_state' => $data['billing_state'] ?? '',
                'billing_postal_code' => $data['billing_postal_code'] ?? '',
                'billing_country' => $data['billing_country'] ?? '',
                'billing_phone' => $data['billing_phone'] ?? null,
                // Shipping
                'shipping_first_name' => $data['shipping_first_name'] ?? $data['billing_first_name'] ?? '',
                'shipping_last_name' => $data['shipping_last_name'] ?? $data['billing_last_name'] ?? '',
                'shipping_address_line1' => $data['shipping_address_line1'] ?? $data['billing_address_line1'] ?? '',
                'shipping_address_line2' => $data['shipping_address_line2'] ?? $data['billing_address_line2'] ?? null,
                'shipping_city' => $data['shipping_city'] ?? $data['billing_city'] ?? '',
                'shipping_state' => $data['shipping_state'] ?? $data['billing_state'] ?? '',
                'shipping_postal_code' => $data['shipping_postal_code'] ?? $data['billing_postal_code'] ?? '',
                'shipping_country' => $data['shipping_country'] ?? $data['billing_country'] ?? '',
                'shipping_phone' => $data['shipping_phone'] ?? $data['billing_phone'] ?? null,
            ]);

            // Create order items with snapshots
            foreach ($items as $item) {
                $product = $item->product;
                $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'variant_name' => $item->variant?->name,
                    'product_image' => $primaryImage?->image_path,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->unit_price * $item->quantity,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total' => $item->unit_price * $item->quantity,
                ]);

                // Decrement stock
                if ($product->manage_stock) {
                    $product->decrement('stock_quantity', $item->quantity);
                }
                $product->increment('sold_count', $item->quantity);
            }

            // Increment coupon usage
            if ($coupon) {
                $coupon->increment('used_count');
            }

            // Record initial status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'comment' => 'Order placed successfully',
                'is_customer_notified' => true,
            ]);

            // Handle payment method
            if ($data['payment_method'] === 'cod') {
                // COD - remains unpaid until delivery
            } elseif ($data['payment_method'] === 'bank_transfer') {
                // Bank transfer - remains unpaid until admin confirms
            }

            // Clear cart
            $cart->clear();

            // Fire event
            event(new \App\Events\OrderPlaced($order));

            return ['success' => true, 'order' => $order];
        });
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('Ymd') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Generate a PDF invoice for an order.
     */
    public function generateInvoice(Order $order)
    {
        $order->load(['items', 'user', 'statusHistory']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', [
            'order' => $order,
            'settings' => [
                'site_name' => \App\Models\Setting::get('general.site_name', 'Porto Shop'),
                'address' => \App\Models\Setting::get('contact.address', ''),
                'phone' => \App\Models\Setting::get('contact.phone', ''),
                'email' => \App\Models\Setting::get('contact.email', ''),
            ],
        ]);

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }
}
