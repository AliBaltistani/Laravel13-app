<style>
    .quickview-wrapper-custom {
        padding: 30px;
        background: #fff;
        overflow-x: hidden;
    }
    .quickview-wrapper-custom .row {
        margin-left: 0;
        margin-right: 0;
    }
    .quickview-wrapper-custom .col-md-6 {
        padding-left: 15px;
        padding-right: 15px;
    }
</style>
<div class="product-single-container product-single-default product-quick-view mb-0 custom-scrollbar quickview-wrapper-custom">
    <div class="row">
        <div class="col-md-6 product-single-gallery mb-md-0">
            <div class="product-slider-container">
                @php
                    $hasDiscount = $product->compare_price && $product->compare_price > $product->price;
                    $discountPercent = $hasDiscount ? round(($product->compare_price - $product->price) / $product->compare_price * 100) : 0;
                    $flashSale = $product->activeFlashSale();
                @endphp
                <div class="label-group">
                    @if($product->is_new)
                        <div class="product-label label-hot">NEW</div>
                    @endif
                    @if($hasDiscount)
                        <div class="product-label label-sale">-{{ $discountPercent }}%</div>
                    @endif
                </div>

                <div class="product-single-carousel owl-carousel owl-theme show-nav-hover">
                    @forelse($product->images as $image)
                        <div class="product-item">
                            <img class="product-single-image" src="{{ asset('storage/' . $image->image_path) }}" data-zoom-image="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}">
                        </div>
                    @empty
                        <div class="product-item">
                            <img class="product-single-image" src="{{ asset('images/no-image.svg') }}" alt="{{ $product->name }}">
                        </div>
                    @endforelse
                </div>
            </div>
            @if($product->images->count() > 1)
            <div class="prod-thumbnail owl-dots">
                @foreach($product->images as $image)
                    <div class="owl-dot">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }} thumbnail">
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="col-md-6 product-single-details mb-0 pb-0">
            <h1 class="product-title">{{ $product->name }}</h1>

            <div class="ratings-container">
                <div class="product-ratings">
                    <span class="ratings" style="width:{{ ($product->averageRating() / 5) * 100 }}%"></span>
                    <span class="tooltiptext tooltip-top"></span>
                </div>
                <a href="{{ url('/product/' . $product->slug) }}#product-reviews-content" class="rating-link">( {{ $product->reviewCount() }} Reviews )</a>
            </div>

            <hr class="short-divider">

            <div class="price-box">
                @if($hasDiscount)
                    <span class="old-price">@price($product->compare_price)</span>
                @endif
                <span class="new-price">@price($product->effectivePrice())</span>
            </div>

            @if($product->short_description)
            <div class="product-desc">
                <p>{{ $product->short_description }}</p>
            </div>
            @endif

            <ul class="single-info-list mt-2 mb-2">
                @if($product->sku)
                    <li>SKU: <strong>{{ $product->sku }}</strong></li>
                @endif
                @if($product->category)
                    <li>CATEGORY: <strong><a href="{{ url('/shop/category/' . $product->category->slug) }}" class="product-category">{{ strtoupper($product->category->name) }}</a></strong></li>
                @endif
            </ul>

            <div class="mb-2">
                @if($product->isInStock())
                    <span class="text-success"><i class="fas fa-check-circle"></i> In Stock</span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i> Out of Stock</span>
                @endif
            </div>

            @if($product->isInStock())
                <div class="product-action mt-2 mb-2">
                    <button class="btn btn-dark add-cart mr-2" title="Add to Cart" onclick="Livewire.dispatch('addToCart', { productId: {{ $product->id }} })">
                        Add to Cart
                    </button>
                </div>
            @endif

            <hr class="divider mb-0 mt-0">

            <div class="product-single-share mb-3">
                <label class="sr-only">Share:</label>
                <div class="social-icons mr-2">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/product/' . $product->slug)) }}" class="social-icon social-facebook icon-facebook" target="_blank" title="Facebook"></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/product/' . $product->slug)) }}&text={{ urlencode($product->name) }}" class="social-icon social-twitter icon-twitter" target="_blank" title="Twitter"></a>
                </div>
                @php
                    $inWishlist = \Illuminate\Support\Facades\Auth::check() && \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
                @endphp
                <a href="#" class="btn-icon-wish {{ $inWishlist ? 'added-wishlist' : '' }}" title="Add to Wishlist" onclick="event.preventDefault(); this.classList.toggle('added-wishlist'); this.querySelector('i').style.color = this.classList.contains('added-wishlist') ? '#e74c3c' : ''; Livewire.dispatch('toggleWishlist', { productId: {{ $product->id }} });"><i class="icon-heart" style="{{ $inWishlist ? 'color: #e74c3c;' : '' }}"></i></a>
                <span>Add to Wishlist</span>
            </div>
            
            <a href="{{ url('/product/' . $product->slug) }}" class="btn btn-dark btn-block mt-2">View Full Product Details</a>
        </div>
    </div>
</div>
