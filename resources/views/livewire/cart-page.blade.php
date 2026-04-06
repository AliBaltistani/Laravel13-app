{{-- Cart Page Livewire View - Porto cart.html markup --}}
<div class="row">
    <div class="col-lg-8">
        @if($items->count())
        <div class="cart-table-container">
            <table class="table table-cart">
                <thead>
                    <tr>
                        <th class="thumbnail-col"></th>
                        <th class="product-col">Product</th>
                        <th class="price-col">Price</th>
                        <th class="qty-col">Quantity</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        @php
                            $img = $item->product->images->where('is_primary', true)->first() ?? $item->product->images->first();
                            $imgPath = $img ? asset('storage/' . $img->image_path) : asset('images/no-image.svg');
                        @endphp
                        <tr class="product-row">
                            <td>
                                <figure class="product-image-container">
                                    <a href="{{ url('/product/' . $item->product->slug) }}" class="product-image">
                                        <img src="{{ $imgPath }}" alt="{{ $item->product->name }}" width="80" height="80">
                                    </a>

                                    <a href="#" class="btn-remove icon-cancel" title="Remove Product"
                                       wire:click.prevent="removeItem({{ $item->id }})"></a>
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
                            <td>
                                <div class="input-group mx-auto" style="width: 110px;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="button" style="padding: 0 12px; border-color: #ddd; background: #f9f9f9; z-index: 1;"
                                                wire:click="updateQuantity({{ $item->id }}, {{ max(1, $item->quantity - 1) }})">
                                            <i class="fas fa-minus" style="font-size: 10px;"></i>
                                        </button>
                                    </div>
                                    <input class="form-control text-center" type="number"
                                           value="{{ $item->quantity }}" min="1" max="99"
                                           style="padding: 0; height: 38px; border-color: #ddd; border-left: none; border-right: none; box-shadow: none;"
                                           wire:change="updateQuantity({{ $item->id }}, $event.target.value)">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" style="padding: 0 12px; border-color: #ddd; background: #f9f9f9; z-index: 1;"
                                                wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">
                                            <i class="fas fa-plus" style="font-size: 10px;"></i>
                                        </button>
                                    </div>
                                </div><!-- End .input-group -->
                            </td>
                            <td class="text-right"><span class="subtotal-price">@price($item->unit_price * $item->quantity)</span></td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="5" class="clearfix">
                            <div class="float-left">
                                <div class="cart-discount">
                                    <form wire:submit.prevent="applyCoupon">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm"
                                                   placeholder="Coupon Code" wire:model="couponCode" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-sm" type="submit">Apply Coupon</button>
                                            </div>
                                        </div><!-- End .input-group -->
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
                            </div><!-- End .float-left -->

                            <div class="float-right">
                                <a href="{{ url('/shop') }}" class="btn btn-shop btn-update-cart">Continue Shopping</a>
                            </div><!-- End .float-right -->
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div><!-- End .cart-table-container -->
        @else
        <div class="text-center py-5">
            <i class="icon-bag-1" style="font-size: 64px; color: #ddd;"></i>
            <h3 class="mt-3">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any products yet.</p>
            <a href="{{ url('/shop') }}" class="btn btn-dark">Continue Shopping</a>
        </div>
        @endif
    </div><!-- End .col-lg-8 -->

    <div class="col-lg-4">
        @if($items->count())
        <div class="cart-summary">
            <h3>CART TOTALS</h3>

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
                    <i class="fa fa-arrow-right"></i></a>
            </div>
        </div><!-- End .cart-summary -->
        @endif
    </div><!-- End .col-lg-4 -->
</div><!-- End .row -->
