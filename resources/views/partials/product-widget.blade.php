{{-- Product Widget (small horizontal card for sidebar columns)
    Usage: @include('partials.product-widget', ['product' => $product])
--}}
@php
    $wPrimaryImg = $product->primaryImage;
    $wImgPath = $wPrimaryImg ? asset('storage/' . $wPrimaryImg->image_path) : asset('themes/porto/images/products/product-1.jpg');
    $wRating = $product->averageRating();
    $wRatingPercent = ($wRating / 5) * 100;
@endphp

<div class="product-default left-details product-widget">
    <figure>
        <a href="{{ url('/product/' . $product->slug) }}">
            <img src="{{ $wImgPath }}" width="84" height="84" alt="{{ $product->name }}">
        </a>
    </figure>
    <div class="product-details">
        <h3 class="product-title">
            <a href="{{ url('/product/' . $product->slug) }}">{{ $product->name }}</a>
        </h3>
        <div class="ratings-container">
            <div class="product-ratings">
                <span class="ratings" style="width:{{ $wRatingPercent }}%"></span>
                <span class="tooltiptext tooltip-top"></span>
            </div>
        </div>
        <div class="price-box">
            @if($product->compare_price && $product->compare_price > $product->price)
                <span class="old-price">${{ number_format($product->compare_price, 2) }}</span>
            @endif
            <span class="product-price">${{ number_format($product->price, 2) }}</span>
        </div>
    </div>
</div>
