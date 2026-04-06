<div>
    @if($type === 'card')
        <button wire:click.prevent="addToCart" wire:loading.attr="disabled" class="btn-icon btn-add-cart product-type-simple" title="Add To Cart">
            <i class="icon-shopping-cart" wire:loading.remove wire:target="addToCart"></i>
            <i class="fas fa-spinner fa-spin" wire:loading wire:target="addToCart"></i>
        </button>
    @else
        <div class="product-action-wrapper">
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
                <div class="input-group mr-3" style="width: 120px; float: left;">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary" type="button" style="padding: 0 14px; border-color: #e6e6e6; background: #f4f4f4; z-index: 1;"
                                wire:click.prevent="decrement">
                            <i class="fas fa-minus" style="font-size: 10px;"></i>
                        </button>
                    </div>
                    <input class="form-control text-center text-dark" type="number" 
                           value="{{ $quantity }}" 
                           wire:change="$set('quantity', $event.target.value)" 
                           min="1" max="{{ $maxQty }}"
                           style="padding: 0; height: 44px; border-color: #e6e6e6; border-left: none; border-right: none; box-shadow: none; font-weight: bold;">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" style="padding: 0 14px; border-color: #e6e6e6; background: #f4f4f4; z-index: 1;"
                                wire:click.prevent="increment">
                            <i class="fas fa-plus" style="font-size: 10px;"></i>
                        </button>
                    </div>
                </div>

                <button wire:click.prevent="addToCart" wire:loading.attr="disabled" class="btn btn-dark add-cart mr-2" title="Add to Cart">
                    <span wire:loading.remove wire:target="addToCart">Add to Cart</span>
                    <span wire:loading wire:target="addToCart">Adding...</span>
                </button>

                <a href="{{ url('/cart') }}" class="btn btn-gray view-cart {{ $message && $messageType === 'success' ? '' : 'd-none' }}">View cart</a>
            </div>
        </div>
    @endif
</div>
