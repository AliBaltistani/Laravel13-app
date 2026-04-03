{{-- Add to Cart Livewire Component --}}
@if($type === 'card')
    <button wire:click.prevent="addToCart" wire:loading.attr="disabled" class="btn-icon btn-add-cart product-type-simple" title="Add To Cart">
        <i class="icon-shopping-cart" wire:loading.remove wire:target="addToCart"></i>
        <i class="fas fa-spinner fa-spin" wire:loading wire:target="addToCart"></i>
    </button>
@else
    <div>
        @if($message)
            <div class="cart-message {{ $messageType === 'success' ? 'd-block' : 'd-block' }}">
                @if($messageType === 'success')
                    <strong class="single-cart-notice">"{{ \App\Models\Product::find($productId)?->name }}"</strong>
                    <span>has been added to your cart.</span>
                @else
                    <div class="alert alert-danger mb-2">{{ $message }}</div>
                @endif
            </div>
        @endif

        <div class="product-action">
            <div class="product-single-qty">
                <input class="horizontal-quantity form-control" type="text"
                       wire:model.live="quantity"
                       min="1" max="{{ $maxQty }}">
            </div>

            <button wire:click.prevent="addToCart" wire:loading.attr="disabled" class="btn btn-dark add-cart mr-2" title="Add to Cart">
                <span wire:loading.remove wire:target="addToCart">Add to Cart</span>
                <span wire:loading wire:target="addToCart">Adding...</span>
            </button>

            <a href="{{ url('/cart') }}" class="btn btn-gray view-cart {{ $message && $messageType === 'success' ? '' : 'd-none' }}">View cart</a>
        </div>
    </div>
@endif
