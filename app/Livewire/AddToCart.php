<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AddToCart extends Component
{
    public $productId;
    public $variantId = null;
    public $quantity = 1;
    public $maxQty = 999;
    public $message = '';
    public $messageType = '';
    public $type = 'single';

    public function mount($productId, $maxQty = 999, $type = 'single')
    {
        $this->productId = $productId;
        $this->maxQty = $maxQty;
        $this->type = $type;
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
        // Require authentication before adding to cart
        if (!Auth::check()) {
            session()->put('url.intended', url()->previous());
            $this->dispatch('notify', message: 'Please login to add items to your cart.', type: 'info');
            return $this->redirect(route('login'), navigate: false);
        }

        $cart = app(CartService::class);
        $result = $cart->addItem($this->productId, $this->variantId, $this->quantity);

        $this->message = $result['message'];
        $this->messageType = $result['success'] ? 'success' : 'error';

        if ($result['success']) {
            $this->dispatch('cartUpdated');
            $this->dispatch('notify', message: $result['message'], type: 'success');
        } else {
            $this->dispatch('notify', message: $result['message'], type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
