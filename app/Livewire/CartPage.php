<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Services\OrderService;
use App\Models\ShippingMethod;
use App\Models\UserAddress;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartPage extends Component
{
    public $couponCode = '';
    public $couponMessage = '';
    public $couponMessageType = '';

    protected $listeners = ['cartUpdated' => '$refresh'];

    public function updateQuantity($cartItemId, $qty)
    {
        $cart = app(CartService::class);
        $result = $cart->updateItem($cartItemId, (int)$qty);

        if (!$result['success']) {
            session()->flash('error', $result['message']);
        }

        $this->dispatch('cartUpdated');
    }

    public function removeItem($cartItemId)
    {
        app(CartService::class)->removeItem($cartItemId);
        $this->dispatch('cartUpdated');
    }

    public function applyCoupon()
    {
        $cart = app(CartService::class);
        $result = $cart->applyCoupon($this->couponCode);
        $this->couponMessage = $result['message'];
        $this->couponMessageType = $result['success'] ? 'success' : 'error';
        $this->dispatch('cartUpdated');
    }

    public function removeCoupon()
    {
        app(CartService::class)->removeCoupon();
        $this->couponCode = '';
        $this->couponMessage = '';
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        $cart = app(CartService::class);
        return view('livewire.cart-page', [
            'items' => $cart->getItems(),
            'subtotal' => $cart->getSubtotal(),
            'discount' => $cart->getDiscount(),
            'total' => $cart->getTotal(),
            'coupon' => $cart->getCart()->coupon,
        ]);
    }
}
