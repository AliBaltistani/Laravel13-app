<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;

class AddToCart extends Component
{
    public $productId;
    public $variantId = null;
    public $quantity = 1;
    public $maxQty = 999;
    public $message = '';
    public $messageType = '';

    public function mount($productId, $maxQty = 999)
    {
        $this->productId = $productId;
        $this->maxQty = $maxQty;
    }

    public function increment()
    {
        if ($this->quantity < $this->maxQty) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function setVariant($variantId)
    {
        $this->variantId = $variantId;
    }

    public function addToCart()
    {
        $cart = app(CartService::class);
        $result = $cart->addItem($this->productId, $this->variantId, $this->quantity);

        $this->message = $result['message'];
        $this->messageType = $result['success'] ? 'success' : 'error';

        if ($result['success']) {
            $this->dispatch('cartUpdated');
        }
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
