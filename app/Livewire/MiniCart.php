<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class MiniCart extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $itemCount = 0;

    public function mount()
    {
        $this->refreshCart();
    }

    #[On('cartUpdated')]
    public function refreshCart()
    {
        if (!Auth::check()) {
            $this->cartItems = [];
            $this->subtotal = 0;
            $this->itemCount = 0;
            return;
        }

        $cart = app(CartService::class);
        $this->cartItems = $cart->getItems()->take(3)->toArray();
        $this->subtotal = $cart->getSubtotal();
        $this->itemCount = $cart->getItemCount();
    }

    #[On('addToCart')]
    public function handleAddToCart($productId = null)
    {
        // Require authentication
        if (!Auth::check()) {
            $this->dispatch('notify', message: 'Please login to add items to your cart.', type: 'info');
            return $this->redirect(route('login'), navigate: false);
        }

        if ($productId) {
            $cart = app(CartService::class);
            $cart->addItem($productId, null, 1);
            $this->refreshCart();
            $this->dispatch('notify', message: 'Added to cart successfully!', type: 'success');
        }
    }

    #[On('toggleWishlist')]
    public function handleToggleWishlist($productId = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($productId) {
            $existing = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                $existing->delete();
                $this->dispatch('notify', message: 'Removed from wishlist', type: 'info');
            } else {
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'added_at' => now(),
                ]);
                $this->dispatch('notify', message: 'Added to wishlist!', type: 'success');
            }
        }
    }

    public function removeItem($cartItemId)
    {
        app(CartService::class)->removeItem($cartItemId);
        $this->refreshCart();
    }

    public function render()
    {
        return view('livewire.mini-cart');
    }
}
