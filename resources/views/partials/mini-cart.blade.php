{{-- Mini Cart Dropdown --}}
<div class="dropdown cart-dropdown">
    <a href="#" title="Cart" class="dropdown-toggle dropdown-arrow cart-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
        <i class="minicart-icon"></i>
        <span class="cart-count badge-circle" id="mini-cart-count">0</span>
    </a>

    <div class="cart-overlay"></div>

    <div class="dropdown-menu mobile-cart">
        <a href="#" title="Close (Esc)" class="btn-close">×</a>

        <div class="dropdownmenu-wrapper custom-scrollbar">
            <div class="dropdown-cart-header">Shopping Cart</div>

            <div class="dropdown-cart-products" id="mini-cart-items">
                {{-- Cart items will be loaded dynamically via Livewire --}}
                <p class="text-center text-muted py-3">Your cart is empty.</p>
            </div>

            <div class="dropdown-cart-total">
                <span>SUBTOTAL:</span>
                <span class="cart-total-price float-right" id="mini-cart-total">$0.00</span>
            </div>

            <div class="dropdown-cart-action">
                <a href="{{ url('/cart') }}" class="btn btn-gray btn-block view-cart">View Cart</a>
                <a href="{{ url('/checkout') }}" class="btn btn-dark btn-block">Checkout</a>
            </div>
        </div>
    </div>
</div>
