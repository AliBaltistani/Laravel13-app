<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class MiniCart extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $itemCount = 0;

    protected $listeners = [
        'cartUpdated' => 'refreshCart',
        'addToCart' => 'handleAddToCart',
        'toggleWishlist' => 'handleToggleWishlist'
    ];

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

    public function handleAddToCart($data)
    {
        $productId = $data['productId'] ?? null;
        if ($productId) {
            $cart = app(CartService::class);
            $cart->addItem($productId, null, 1);
            $this->refreshCart();
            $this->dispatch('cartUpdated');
            $this->dispatch('notify', message: 'Added to Cart successfully!', type: 'success');
        }
    }

    public function handleToggleWishlist($data)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $productId = $data['productId'] ?? null;
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
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.mini-cart');
    }
}
