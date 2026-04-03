@extends('layouts.app')

@section('meta_title', ($product->meta_title ?? $product->name) . ' - ' . Setting::get('general.site_name', 'Porto Shop'))
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ url('/shop') }}">Shop</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ url('/shop/category/' . $product->category->slug) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="product-single-container product-single-default">
        <div class="row">
            {{-- Product Gallery --}}
            <div class="col-lg-5 col-md-6 product-single-gallery">
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
                                <img class="product-single-image" src="{{ asset('storage/' . $image->image_path) }}" data-zoom-image="{{ asset('storage/' . $image->image_path) }}" width="468" height="468" alt="{{ $product->name }}">
                            </div>
                        @empty
                            <div class="product-item">
                                <img class="product-single-image" src="{{ asset('images/no-image.svg') }}" width="468" height="468" alt="{{ $product->name }}">
                            </div>
                        @endforelse
                    </div>
                    <span class="prod-full-screen"><i class="icon-plus"></i></span>
                </div>

                @if($product->images->count() > 1)
                <div class="prod-thumbnail owl-dots">
                    @foreach($product->images as $image)
                        <div class="owl-dot">
                            <img src="{{ asset('storage/' . $image->image_path) }}" width="110" height="110" alt="{{ $product->name }} thumbnail">
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Product Details --}}
            <div class="col-lg-7 col-md-6 product-single-details">
                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="ratings-container">
                    <div class="product-ratings">
                        <span class="ratings" style="width:{{ ($avgRating / 5) * 100 }}%"></span>
                        <span class="tooltiptext tooltip-top"></span>
                    </div>
                    <a href="#product-reviews-content" class="rating-link">( {{ $totalReviews }} Reviews )</a>
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

                @if($flashSale)
                <div class="product-countdown-container mb-2">
                    <span class="product-countdown-title">Offer ends in:</span>
                    <div class="product-countdown countdown-compact" data-until="{{ $flashSale->flashSale->expires_at->format('Y, m, d') }}" data-compact="true"></div>
                </div>
                @endif

                <ul class="single-info-list">
                    @if($product->sku)
                        <li>SKU: <strong>{{ $product->sku }}</strong></li>
                    @endif
                    @if($product->category)
                        <li>CATEGORY: <strong><a href="{{ url('/shop/category/' . $product->category->slug) }}" class="product-category">{{ strtoupper($product->category->name) }}</a></strong></li>
                    @endif
                    @if($product->brand)
                        <li>BRAND: <strong><a href="{{ url('/shop/brand/' . $product->brand->slug) }}" class="product-category">{{ strtoupper($product->brand->name) }}</a></strong></li>
                    @endif
                    @if($product->tags->count())
                        <li>TAGs: @foreach($product->tags as $tag)<strong><a href="{{ url('/shop?tag=' . $tag->slug) }}" class="product-category">{{ strtoupper($tag->name) }}</a></strong>{{ !$loop->last ? ', ' : '' }}@endforeach</li>
                    @endif
                </ul>

                {{-- Stock Status --}}
                <div class="mb-2">
                    @if($product->isInStock())
                        <span class="text-success"><i class="fas fa-check-circle"></i> In Stock</span>
                        @if($product->manage_stock && $product->stock_quantity <= 5)
                            <span class="text-warning ml-2">Only {{ $product->stock_quantity }} left!</span>
                        @endif
                    @else
                        <span class="text-danger"><i class="fas fa-times-circle"></i> Out of Stock</span>
                    @endif
                </div>

                {{-- Add to Cart --}}
                @if($product->isInStock())
                    @livewire('add-to-cart', ['productId' => $product->id, 'maxQty' => $product->manage_stock ? $product->stock_quantity : 999])
                @else
                    <button class="btn btn-dark btn-disabled" disabled>Out of Stock</button>
                @endif

                <hr class="divider mb-0 mt-0">

                <div class="product-single-share mb-3">
                    <label class="sr-only">Share:</label>
                    <div class="social-icons mr-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="social-icon social-facebook icon-facebook" target="_blank" title="Facebook"></a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->name) }}" class="social-icon social-twitter icon-twitter" target="_blank" title="Twitter"></a>
                        <a href="mailto:?subject={{ urlencode($product->name) }}&body={{ urlencode(url()->current()) }}" class="social-icon social-mail icon-mail-alt" target="_blank" title="Mail"></a>
                    </div>
                    @livewire('wishlist-toggle', ['productId' => $product->id])
                    <span>Add to Wishlist</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Tabs --}}
    <div class="product-single-tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="product-tab-desc" data-toggle="tab" href="#product-desc-content" role="tab">Description</a>
            </li>
            @if($product->attributes->count())
            <li class="nav-item">
                <a class="nav-link" id="product-tab-tags" data-toggle="tab" href="#product-tags-content" role="tab">Additional Information</a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" id="product-tab-reviews" data-toggle="tab" href="#product-reviews-content" role="tab">Reviews ({{ $totalReviews }})</a>
            </li>
        </ul>

        <div class="tab-content">
            {{-- Description Tab --}}
            <div class="tab-pane fade show active" id="product-desc-content" role="tabpanel">
                <div class="product-desc-content">
                    {!! $product->description !!}
                </div>
            </div>

            {{-- Additional Info Tab --}}
            @if($product->attributes->count())
            <div class="tab-pane fade" id="product-tags-content" role="tabpanel">
                <table class="table table-striped mt-2">
                    <tbody>
                        @foreach($product->attributes->groupBy(fn($a) => $a->attributeGroup->name) as $groupName => $attrs)
                            <tr>
                                <th>{{ $groupName }}</th>
                                <td>{{ $attrs->pluck('value')->join(', ') }}</td>
                            </tr>
                        @endforeach
                        @if($product->weight)
                            <tr><th>Weight</th><td>{{ $product->weight }} kg</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Reviews Tab --}}
            <div class="tab-pane fade" id="product-reviews-content" role="tabpanel">
                @livewire('review-section', ['productId' => $product->id])
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count())
    <div class="products-section pt-0">
        <h2 class="section-title">Related Products</h2>
        <div class="products-slider owl-carousel owl-theme dots-top dots-small">
            @foreach($relatedProducts as $relProduct)
                @include('partials.product-card', ['product' => $relProduct])
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
