{{-- Product Card Grid View
    Usage: @include('partials.product-card', ['product' => $product])
--}}
@php
    $primaryImg = $product->primaryImage;
    $imgPath = $primaryImg ? asset('storage/' . $primaryImg->image_path) : asset('images/no-image.svg');
    $secondImg = $product->images->where('is_primary', false)->first();
    $secondImgPath = $secondImg ? asset('storage/' . $secondImg->image_path) : null;
    $avgRating = $product->averageRating();
    $ratingPercent = ($avgRating / 5) * 100;
    $hasDiscount = $product->compare_price && $product->compare_price > $product->price;
    $discountPercent = $hasDiscount ? round((($product->compare_price - $product->price) / $product->compare_price) * 100) : 0;
    $flashSale = $product->activeFlashSale();
@endphp

<div class="product-default inner-quickview inner-icon">
    <figure class="img-effect">
        <a href="{{ url('/product/' . $product->slug) }}">
            <img src="{{ $imgPath }}" width="205" height="205" alt="{{ $product->name }}">
            @if($secondImgPath)
                <img src="{{ $secondImgPath }}" width="205" height="205" alt="{{ $product->name }}">
            @endif
        </a>
        <div class="label-group">
            @if($product->is_new)
                <div class="product-label label-hot">NEW</div>
            @endif
            @if($hasDiscount)
                <div class="product-label label-sale">-{{ $discountPercent }}%</div>
            @endif
        </div>
        <div class="btn-icon-group">
            @livewire('add-to-cart', ['productId' => $product->id, 'type' => 'card'], key('cart-btn-'.$product->id))
        </div>
        <a href="{{ url('/product/' . $product->slug) }}" class="btn-quickview" title="Quick View">Quick View</a>
        @if($flashSale)
        <div class="product-countdown-container">
            <span class="product-countdown-title">offer ends in:</span>
            <div class="product-countdown countdown-compact" data-until="{{ $flashSale->flashSale->expires_at->format('Y, m, d') }}" data-compact="true"></div>
        </div>
        @endif
    </figure>
    <div class="product-details">
        <div class="category-wrap">
            <div class="category-list">
                @if($product->category)
                    <a href="{{ url('/shop/category/' . $product->category->slug) }}" class="product-category">{{ $product->category->name }}</a>
                @endif
            </div>
            @livewire('wishlist-toggle', ['productId' => $product->id], key('wishlist-card-'.$product->id))
        </div>
        <h3 class="product-title">
            <a href="{{ url('/product/' . $product->slug) }}">{{ $product->name }}</a>
        </h3>
        <div class="ratings-container">
            <div class="product-ratings">
                <span class="ratings" style="width:{{ $ratingPercent }}%"></span>
                <span class="tooltiptext tooltip-top"></span>
            </div>
        </div>
        <div class="price-box">
            @if($hasDiscount)
                <span class="old-price">@price($product->compare_price)</span>
            @endif
            <span class="product-price">@price($product->effectivePrice())</span>
        </div>
    </div>
</div>
