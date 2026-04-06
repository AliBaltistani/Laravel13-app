{{-- Product Widget (Demo8 style — horizontal card with categories + dual images)
    Usage: @include('partials.product-widget', ['product' => $product])
--}}
@php
    $wPrimaryImg = $product->primaryImage;
    $wImgPath = $wPrimaryImg ? asset('storage/' . $wPrimaryImg->image_path) : asset('images/no-image.svg');
    $wSecondImg = $product->images->where('is_primary', false)->first();
    $wSecondImgPath = $wSecondImg ? asset('storage/' . $wSecondImg->image_path) : null;
    $wRating = $product->averageRating();
    $wRatingPercent = ($wRating / 5) * 100;
@endphp

<div class="product-default left-details product-widget mb-2">
    <figure>
        <a href="{{ url('/product/' . $product->slug) }}">
            <img src="{{ $wImgPath }}" width="175" height="175" alt="{{ $product->name }}" />
            @if($wSecondImgPath)
                <img src="{{ $wSecondImgPath }}" width="175" height="175" alt="{{ $product->name }}" />
            @endif
        </a>
    </figure>
    <div class="product-details">
        @if($product->category)
        <div class="category-list">
            <a href="{{ url('/shop/category/' . $product->category->slug) }}" class="product-category">{{ $product->category->name }}</a>
        </div>
        @endif
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
                <span class="old-price">@price($product->compare_price)</span>
            @endif
            <span class="product-price">@price($product->price)</span>
        </div>
    </div>
</div>
