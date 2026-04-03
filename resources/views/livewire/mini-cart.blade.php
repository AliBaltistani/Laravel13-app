{{-- Mini Cart Livewire Component --}}
<div class="dropdown cart-dropdown" wire:ignore.self>
    <a href="#" title="Cart" class="dropdown-toggle dropdown-arrow cart-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
        <i class="minicart-icon"></i>
        <span class="cart-count badge-circle">{{ $itemCount }}</span>
    </a>

    <div class="cart-overlay"></div>

    <div class="dropdown-menu mobile-cart">
        <a href="#" title="Close (Esc)" class="btn-close">×</a>

        <div class="dropdownmenu-wrapper custom-scrollbar">
            <div class="dropdown-cart-header">Shopping Cart</div>

            <div class="dropdown-cart-products">
                @forelse($cartItems as $item)
                    <div class="product">
                        <div class="product-details">
                            <h4 class="product-title">
                                <a href="{{ url('/product/' . ($item['product']['slug'] ?? '')) }}">{{ $item['product']['name'] ?? 'Product' }}</a>
                            </h4>
                            <span class="cart-product-info">
                                <span class="cart-product-qty">{{ $item['quantity'] }}</span> × @price($item['unit_price'])
                            </span>
                        </div>

                        <figure class="product-image-container">
                            <a href="{{ url('/product/' . ($item['product']['slug'] ?? '')) }}" class="product-image">
                                @php
                                    $img = collect($item['product']['images'] ?? [])->firstWhere('is_primary', true) ?? collect($item['product']['images'] ?? [])->first();
                                @endphp
                                <img src="{{ $img ? asset('storage/' . $img['image_path']) : asset('images/no-image.svg') }}" alt="{{ $item['product']['name'] ?? '' }}" width="80" height="80">
                            </a>
                            <a href="#" wire:click.prevent="removeItem({{ $item['id'] }})" class="btn-remove" title="Remove Product"><span>×</span></a>
                        </figure>
                    </div>
                @empty
                    <p class="text-center text-muted py-3">Your cart is empty.</p>
                @endforelse
            </div>

            <div class="dropdown-cart-total">
                <span>SUBTOTAL:</span>
                <span class="cart-total-price float-right">@price($subtotal)</span>
            </div>

            <div class="dropdown-cart-action">
                <a href="{{ url('/cart') }}" class="btn btn-gray btn-block view-cart">View Cart</a>
                <a href="{{ url('/checkout') }}" class="btn btn-dark btn-block">Checkout</a>
            </div>
        </div>
    </div>
</div>
