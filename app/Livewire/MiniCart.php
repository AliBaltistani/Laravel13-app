<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;

class MiniCart extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $itemCount = 0;

    protected $listeners = ['cartUpdated' => 'refreshCart'];

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $cart = app(CartService::class);
        $this->cartItems = $cart->getItems()->take(3)->toArray();
        $this->subtotal = $cart->getSubtotal();
        $this->itemCount = $cart->getItemCount();
    }

    public function removeItem($cartItemId)
    {
        app(CartService::class)->removeItem($cartItemId);
        $this->refreshCart();
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.mini-cart');
    }
}
