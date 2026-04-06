@extends('layouts.app')

@section('is_home', 'true')

@section('content')
<div class="container">
    {{-- ===== HERO SLIDER (Demo8 style with sale-banner overlays) ===== --}}
    @if($slider && $slider->activeSlides->count())
    <div class="home-slider-container">
        <div class="home-slider owl-carousel owl-theme owl-theme-light nav-outer show-nav-hover slide-animate" data-owl-options="{
                'navText': [ '<i class=icon-left-open-big>', '<i class=icon-right-open-big>' ]
                }">
            @foreach($slider->activeSlides as $index => $slide)
            <div class="home-slide {{ $index % 2 !== 0 ? 'home-slide-left' : '' }}">
                <figure style="background-color: {{ $slide->text_color === 'light' ? '#d7b697' : '#ceb49d' }};">
                    @if($slide->image_desktop)
                        <img class="slide-bg" src="{{ asset('storage/' . $slide->image_desktop) }}" width="1180" height="569" alt="{{ $slide->title }}" />
                    @endif
                </figure>
                <div class="home-slide-content {{ $index % 2 !== 0 ? 'slide-content-dark' : '' }} sale-banner">
                    <div class="row no-gutter {{ $index % 2 !== 0 ? 'bg-secondary' : 'bg-primary' }} appear-animate" data-animation-name="{{ $index % 2 !== 0 ? 'fadeInRightShorter' : 'fadeInLeftShorter' }}">
                        <div class="col-auto col-md-6 d-flex flex-column justify-content-center col-1">
                            @if($slide->subtitle)
                            <h4 class="align-left text-uppercase mb-0 appear-animate" data-animation-name="slideInRight" data-animation-delay="400">{{ $slide->subtitle }}</h4>
                            @endif
                            @if($slide->title)
                            <h3 class="text-white mb-0 align-left text-uppercase appear-animate" data-animation-name="slideInRight" data-animation-delay="400">{{ $slide->title }}</h3>
                            @endif
                        </div>

                        <div class="col-auto col-md-6 col-2 appear-animate" data-animation-name="slideInLeft" data-animation-delay="400">
                            @if($slide->description)
                            <h2 class="text-white mb-0 position-relative align-left">
                                {!! $slide->description !!}
                            </h2>
                            @endif
                        </div>
                    </div>
                    @if($slide->button_text)
                    <div class="mb-0 {{ $index % 2 !== 0 ? '' : 'text-right' }}">
                        <a href="{{ $slide->button_url ?? url('/shop') }}" class="btn btn-lg {{ $index % 2 !== 0 ? 'btn-primary' : 'btn-dark' }} appear-animate" data-animation-name="fadeInUpShorter" data-animation-delay="600">{{ $slide->button_text }}</a>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        {{-- End .home-slider --}}
    </div>
    {{-- End .home-slider-container --}}
    @endif
</div>
{{-- End .container --}}

{{-- ===== CATEGORY BANNERS (Demo8 — 3-column grid from admin-managed Banners) ===== --}}
@if(Setting::get('home.show_promo_banners', '1') === '1' && $banners->count())
<div class="container banner-container">
    <div class="row justify-content-center">
        @foreach($banners->take(3) as $index => $banner)
        <div class="col-md-4 col-sm-6 mt-3 mt-md-0 appear-animate"
            @if($index === 0) data-animation-name="fadeInRightShorter" @elseif($index === 2) data-animation-name="fadeInLeftShorter" @endif
            data-animation-duration="1500">
            @if($banner->title)
            <h3 class="subtitle">{{ strtoupper($banner->title) }}</h3>
            <div class="heading-spacer"></div>
            @endif
            <div class="banner banner-image">
                <a href="{{ $banner->button_url ?? url('/shop') }}">
                    @if($banner->image)
                    <img src="{{ asset('storage/' . $banner->image) }}" style="background-color: #e4ddd7;" width="360" height="270" alt="{{ $banner->title }}" />
                    @endif
                </a>
                <div class="banner-meta">
                    <a href="{{ $banner->button_url ?? url('/shop') }}">{{ strtoupper($banner->title ?? '') }}</a>
                    @if($banner->subtitle)
                    <span class="banner-price">{{ $banner->subtitle }}</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="mb-3"></div>

{{-- ===== FEATURED PRODUCTS GRID (Demo8 — 4-col grid) ===== --}}
@if(Setting::get('home.show_featured_products', '1') === '1')
<div class="container feature-container">
    <h2 class="subtitle text-center">{{ strtoupper(Setting::get('home.featured_title', 'Featured Products')) }}</h2>
    <div class="heading-spacer"></div>
    @if($featuredProducts->count())
    <div class="row">
        @foreach($featuredProducts as $product)
        <div class="col-6 col-sm-4 col-md-3 appear-animate" data-animation-delay="100" data-animation-duration="1500">
            @include('partials.product-card', ['product' => $product])
        </div>
        @endforeach
    </div>
    @else
    <p class="text-center text-muted py-4">No featured products yet. Add products from the admin panel.</p>
    @endif
</div>
@endif

{{-- ===== SALE BANNER (Demo8 — full-width promotion bar) ===== --}}
@if(Setting::get('home.sale_banner_enabled', '1') === '1')
<div class="sale-banner banner appear-animate" data-animation-delay="100" data-animation-duration="1500">
    <div class="container banner-content">
        <div class="row no-gutter bg-secondary">
            <div class="col-auto col-md-4 d-flex flex-column justify-content-center col-1">
                <h4 class="align-left text-uppercase mb-0">{{ Setting::get('home.sale_banner_title', 'Furniture & Garden') }}</h4>
                <h3 class="text-white mb-0 align-left text-uppercase">{{ Setting::get('home.sale_banner_subtitle', 'Huge Sale') }}</h3>
            </div>
            <div class="col-auto col-md-4 col-2">
                <h5 class="text-white mb-0 position-relative align-left">{{ Setting::get('home.sale_banner_discount', '50') }}<small>%<ins>OFF</ins></small></h5>
            </div>
            <div class="mb-0 col-md-4 col-3 col-auto">
                <a href="{{ Setting::get('home.sale_banner_url', url('/shop')) }}" class="btn btn-lg btn-primary">{{ Setting::get('home.sale_banner_button', 'Shop Now!') }}</a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ===== PRODUCT WIDGETS ROW (Demo8 — Top Rated / Best Selling / Latest) ===== --}}
<div class="container">
    <div class="product-widgets row pt-1">
        {{-- Top Rated --}}
        @if($topRated->count())
        <div class="col-md-4 col-sm-6 pb-5 appear-animate" data-animation-name="fadeInRightShorter">
            <h4 class="subtitle text-left text-uppercase">{{ Setting::get('home.top_rated_title', 'Top Rated Products') }}</h4>
            <div class="heading-spacer"></div>
            @foreach($topRated as $product)
                @include('partials.product-widget', ['product' => $product])
            @endforeach
        </div>
        @endif

        {{-- Best Selling --}}
        @if($bestSelling->count())
        <div class="col-md-4 col-sm-6 pb-5 appear-animate" data-animation-delay="100" data-animation-duration="1500">
            <h4 class="subtitle text-left text-uppercase">{{ Setting::get('home.best_selling_title', 'Best Selling Products') }}</h4>
            <div class="heading-spacer"></div>
            @foreach($bestSelling as $product)
                @include('partials.product-widget', ['product' => $product])
            @endforeach
        </div>
        @endif

        {{-- Latest Products --}}
        @if($latestProducts->count())
        <div class="col-md-4 col-sm-6 pb-5 appear-animate" data-animation-name="fadeInLeftShorter">
            <h4 class="subtitle text-left text-uppercase">{{ Setting::get('home.latest_products_title', 'Latest Products') }}</h4>
            <div class="heading-spacer"></div>
            @foreach($latestProducts as $product)
                @include('partials.product-widget', ['product' => $product])
            @endforeach
        </div>
        @endif
    </div>
    {{-- End .product-widgets --}}
</div>

{{-- ===== BRANDS SLIDER (Demo8 — full container with nav arrows) ===== --}}
@if(Setting::get('home.show_brands', '1') === '1' && $brands->count())
<div class="container">
    <div class="brands-section appear-animate" data-animation-delay="200" data-animation-name="fadeIn" data-animation-duration="1000">
        <div class="brands-slider images-center owl-carousel owl-theme nav-outer show-nav-hover" data-owl-options="{
            'margin': 0,
            'nav': true
        }">
            @foreach($brands as $brand)
                @if($brand->logo)
                <a href="{{ url('/shop?brand=' . $brand->slug) }}">
                    <img src="{{ asset('storage/' . $brand->logo) }}" width="140" height="60" alt="{{ $brand->name }}">
                </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ===== INSTAGRAM SECTION (Demo8 — carousel of images from admin) ===== --}}
@if(Setting::get('home.show_instagram', '1') === '1' && $instagramImages->count())
<div class="instagram-section appear-animate">
    <h3 class="subtitle text-uppercase">{{ Setting::get('home.instagram_title', 'Follow On Instagram') }}</h3>
    <div class="heading-spacer"></div>
    <div class="owl-carousel owl-theme instagram-feed-carousel instagram-feed-list">
        @foreach($instagramImages as $instaImg)
            <a href="{{ $instaImg->button_url ?? (Setting::get('social.instagram') ?? '#') }}">
                @if($instaImg->image)
                <img src="{{ asset('storage/' . $instaImg->image) }}" width="197" height="150" alt="{{ $instaImg->title ?? 'Instagram Feed' }}">
                @endif
            </a>
        @endforeach
    </div>
</div>
@endif

@endsection
