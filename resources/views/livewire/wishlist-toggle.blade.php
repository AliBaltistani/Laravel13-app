{{-- Wishlist Toggle Livewire Component --}}
<button wire:click="toggle" class="btn-icon-wish {{ $isInWishlist ? 'added-wishlist' : '' }}" title="{{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
    <i class="icon-heart{{ $isInWishlist ? '' : '' }}" style="{{ $isInWishlist ? 'color: #e74c3c;' : '' }}"></i>
</button>
