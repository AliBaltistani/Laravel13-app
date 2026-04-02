<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Wishlist;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WishlistToggle extends Component
{
    public $productId;
    public $isInWishlist = false;

    protected $listeners = ['wishlistUpdated' => 'checkStatus'];

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->checkStatus();
    }

    public function checkStatus()
    {
        if (Auth::check()) {
            $this->isInWishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $this->productId)
                ->exists();
        }
    }

    public function toggle()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $this->productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->isInWishlist = false;
            $this->dispatch('notify', message: 'Removed from wishlist', type: 'info');
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $this->productId,
                'added_at' => now(),
            ]);
            $this->isInWishlist = true;
            $this->dispatch('notify', message: 'Added to wishlist!', type: 'success');
        }

        $this->dispatch('wishlistUpdated');
    }

    public function render()
    {
        return view('livewire.wishlist-toggle');
    }
}
