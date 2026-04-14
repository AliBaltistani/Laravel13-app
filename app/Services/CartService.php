<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get or create the current cart.
     * Requires authentication — guests cannot have carts.
     */
    public function getCart(): Cart
    {
        if (!Auth::check()) {
            // Return a transient empty cart for non-authenticated users
            // This prevents errors in rendering but blocks all mutations
            return new Cart(['session_id' => Session::getId()]);
        }

        return Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['session_id' => Session::getId()]
        );
    }

    /**
     * Get cart items with eager-loaded relationships.
     */
    public function getItems()
    {
        return $this->getCart()->items()->with(['product.images', 'product.category', 'variant'])->get();
    }

    /**
     * Add an item to the cart.
     */
    public function addItem(int $productId, ?int $variantId = null, int $qty = 1): array
    {
        $product = Product::findOrFail($productId);

        // Stock validation
        if ($product->manage_stock && !$product->allow_backorder) {
            if ($product->stock_quantity < $qty) {
                return ['success' => false, 'message' => 'Not enough stock available. Only ' . $product->stock_quantity . ' left.'];
            }
        }

        $cart = $this->getCart();

        // Check if item already in cart
        $existing = $cart->items()
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($existing) {
            $newQty = $existing->quantity + $qty;

            // Validate against stock
            if ($product->manage_stock && !$product->allow_backorder && $newQty > $product->stock_quantity) {
                return ['success' => false, 'message' => 'Cannot add more. Stock limit reached.'];
            }

            $existing->update([
                'quantity' => $newQty,
                'unit_price' => $this->getItemPrice($product, $variantId),
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $qty,
                'unit_price' => $this->getItemPrice($product, $variantId),
            ]);
        }

        return ['success' => true, 'message' => $product->name . ' added to cart!'];
    }

    /**
     * Update item quantity.
     */
    public function updateItem(int $cartItemId, int $qty): array
    {
        $item = $this->getCart()->items()->findOrFail($cartItemId);
        $product = $item->product;

        if ($qty <= 0) {
            $item->delete();
            return ['success' => true, 'message' => 'Item removed from cart.'];
        }

        if ($product->manage_stock && !$product->allow_backorder && $qty > $product->stock_quantity) {
            return ['success' => false, 'message' => 'Only ' . $product->stock_quantity . ' available.'];
        }

        $item->update(['quantity' => $qty]);
        return ['success' => true, 'message' => 'Cart updated.'];
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(int $cartItemId): void
    {
        $this->getCart()->items()->where('id', $cartItemId)->delete();
    }

    /**
     * Apply a coupon to the cart.
     */
    public function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code.'];
        }

        if (!$coupon->is_active) {
            return ['success' => false, 'message' => 'This coupon is no longer active.'];
        }

        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            return ['success' => false, 'message' => 'This coupon is not yet valid.'];
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return ['success' => false, 'message' => 'This coupon has expired.'];
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return ['success' => false, 'message' => 'This coupon has reached its usage limit.'];
        }

        if (Auth::check() && $coupon->usage_limit_per_user) {
            $userUsage = \App\Models\Order::where('user_id', Auth::id())
                ->where('coupon_code', $coupon->code)
                ->count();
            if ($userUsage >= $coupon->usage_limit_per_user) {
                return ['success' => false, 'message' => 'You have already used this coupon the maximum number of times.'];
            }
        }

        $subtotal = $this->getSubtotal();
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return ['success' => false, 'message' => 'Minimum order amount of $' . number_format($coupon->min_order_amount, 2) . ' required.'];
        }

        $cart = $this->getCart();
        $cart->update(['coupon_id' => $coupon->id]);

        return ['success' => true, 'message' => 'Coupon applied! You save $' . number_format($this->getDiscount(), 2)];
    }

    /**
     * Remove coupon from the cart.
     */
    public function removeCoupon(): void
    {
        $this->getCart()->update(['coupon_id' => null]);
    }

    /**
     * Merge guest cart into user cart on login.
     */
    public function mergeGuestCart(string $sessionId, int $userId): void
    {
        $guestCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
        if (!$guestCart) return;

        $userCart = Cart::firstOrCreate(['user_id' => $userId], ['session_id' => $sessionId]);

        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            if ($existing) {
                $existing->update(['quantity' => $existing->quantity + $guestItem->quantity]);
            } else {
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        // Delete the guest cart (remaining items were merged)
        $guestCart->items()->delete();
        $guestCart->delete();
    }

    /**
     * Clear all items from the cart.
     */
    public function clear(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        $cart->update(['coupon_id' => null]);
    }

    /**
     * Get the cart subtotal.
     */
    public function getSubtotal(): float
    {
        return (float) $this->getItems()->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });
    }

    /**
     * Get the discount amount from the applied coupon.
     */
    public function getDiscount(): float
    {
        $cart = $this->getCart();
        if (!$cart->coupon_id) return 0;

        $coupon = $cart->coupon;
        if (!$coupon) return 0;

        $subtotal = $this->getSubtotal();

        return match ($coupon->type) {
            'percent' => min(
                $subtotal * ($coupon->value / 100),
                $coupon->max_discount ?? PHP_FLOAT_MAX
            ),
            'fixed' => min($coupon->value, $subtotal),
            'free_shipping' => 0, // Handled in shipping calculation
            default => 0,
        };
    }

    /**
     * Check if free shipping coupon is applied.
     */
    public function hasFreeShippingCoupon(): bool
    {
        $cart = $this->getCart();
        if (!$cart->coupon_id) return false;
        return $cart->coupon?->type === 'free_shipping';
    }

    /**
     * Get shipping cost for a given method.
     */
    public function getShipping(?int $methodId = null): float
    {
        if ($this->hasFreeShippingCoupon()) return 0;
        if (!$methodId) return 0;

        $method = \App\Models\ShippingMethod::find($methodId);
        if (!$method) return 0;

        if ($method->type === 'free') {
            $subtotal = $this->getSubtotal();
            if ($method->min_order_amount && $subtotal < $method->min_order_amount) {
                return 0; // Not eligible
            }
            return 0;
        }

        return (float) $method->price;
    }

    /**
     * Get tax amount (can be extended with tax rules).
     */
    public function getTax(): float
    {
        return 0; // Tax calculation can be configured via settings
    }

    /**
     * Get the grand total.
     */
    public function getTotal(?int $shippingMethodId = null): float
    {
        return $this->getSubtotal() - $this->getDiscount() + $this->getShipping($shippingMethodId) + $this->getTax();
    }

    /**
     * Get the cart item count.
     */
    public function getItemCount(): int
    {
        return (int) $this->getItems()->sum('quantity');
    }

    /**
     * Get the item price (check variant first, then product effective price).
     */
    private function getItemPrice(Product $product, ?int $variantId = null): float
    {
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant && $variant->price) {
                return (float) $variant->price;
            }
        }
        return $product->effectivePrice();
    }
}
