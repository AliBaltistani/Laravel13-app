{{-- Cart Page Livewire View - Porto cart.html markup --}}
<div class="cart-table-container">
    @if($items->count())
        <table class="table table-cart">
            <thead>
                <tr>
                    <th class="thumbnail-col"></th>
                    <th class="product-col">Product</th>
                    <th class="price-col">Price</th>
                    <th class="qty-col">Quantity</th>
                    <th class="text-right">Subtotal</th>
                    <th class="action-col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    @php
                        $img = $item->product->images->where('is_primary', true)->first() ?? $item->product->images->first();
                        $imgPath = $img ? asset('storage/' . $img->image_path) : asset('images/no-image.svg');
                    @endphp
                    <tr>
                        <td class="thumbnail-col">
                            <figure>
                                <a href="{{ url('/product/' . $item->product->slug) }}">
                                    <img src="{{ $imgPath }}" width="80" height="80" alt="{{ $item->product->name }}">
                                </a>
                            </figure>
                        </td>
                        <td class="product-col">
                            <h5 class="product-title">
                                <a href="{{ url('/product/' . $item->product->slug) }}">{{ $item->product->name }}</a>
                            </h5>
                            @if($item->variant)
                                <span class="text-muted small">{{ $item->variant->name }}</span>
                            @endif
                        </td>
                        <td>@price($item->unit_price)</td>
                        <td class="qty-col">
                            <div class="product-single-qty">
                                <input class="horizontal-quantity form-control" type="number"
                                       value="{{ $item->quantity }}" min="1" max="99"
                                       wire:change="updateQuantity({{ $item->id }}, $event.target.value)">
                            </div>
                        </td>
                        <td class="text-right">@price($item->unit_price * $item->quantity)</td>
                        <td class="action-col">
                            <button class="btn-remove" title="Remove Product" wire:click="removeItem({{ $item->id }})">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="clearfix">
                        <div class="float-left">
                            <div class="cart-discount">
                                <form class="input-group" wire:submit.prevent="applyCoupon">
                                    <input type="text" class="form-control form-control-sm" placeholder="Coupon code" wire:model="couponCode" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-dark" type="submit">Apply Coupon</button>
                                    </div>
                                </form>
                                @if($couponMessage)
                                    <small class="d-block mt-1 {{ $couponMessageType === 'success' ? 'text-success' : 'text-danger' }}">{{ $couponMessage }}</small>
                                @endif
                                @if($coupon)
                                    <small class="d-block mt-1 text-info">
                                        Coupon "{{ $coupon->code }}" applied
                                        <a href="#" wire:click.prevent="removeCoupon" class="text-danger ml-1">[Remove]</a>
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="float-right">
                            <a href="{{ url('/shop') }}" class="btn btn-outline-secondary btn-sm">Continue Shopping</a>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="row justify-content-end">
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h3>Cart Totals</h3>

                    <table class="table table-totals">
                        <tbody>
                            <tr>
                                <td>Subtotal</td>
                                <td>@price($subtotal)</td>
                            </tr>
                            @if($discount > 0)
                                <tr>
                                    <td>Discount</td>
                                    <td class="text-success">-@price($discount)</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td>@price($total)</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="checkout-methods">
                        <a href="{{ url('/checkout') }}" class="btn btn-block btn-dark">Proceed to Checkout
                            <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="icon-bag-1" style="font-size: 64px; color: #ddd;"></i>
            <h3 class="mt-3">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any products yet.</p>
            <a href="{{ url('/shop') }}" class="btn btn-dark">Continue Shopping</a>
        </div>
    @endif
</div>
