@extends('layouts.app')

@section('is_home', 'true')
@section('header-class', 'home')
@section('header-top-class', 'bg-primary text-uppercase')
@section('main-class', 'home')

@section('content')
<div class="container mb-2">
    {{-- Info Boxes --}}
    <div class="info-boxes-container row row-joined mb-2 font2">
        <div class="info-box info-box-icon-left col-lg-4">
            <i class="icon-shipping"></i>
            <div class="info-box-content">
                <h4>FREE SHIPPING &amp; RETURN</h4>
                <p class="text-body">{{ Setting::get('shipping.free_threshold_label', 'Free shipping on all orders over $99') }}</p>
            </div>
        </div>
        <div class="info-box info-box-icon-left col-lg-4">
            <i class="icon-money"></i>
            <div class="info-box-content">
                <h4>MONEY BACK GUARANTEE</h4>
                <p class="text-body">100% money back guarantee</p>
            </div>
        </div>
        <div class="info-box info-box-icon-left col-lg-4">
            <i class="icon-support"></i>
            <div class="info-box-content">
                <h4>ONLINE SUPPORT 24/7</h4>
                <p class="text-body">Get support any time you need</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            {{-- Hero Slider --}}
            <div class="home-slider slide-animate owl-carousel owl-theme mb-2" data-owl-options="{
                'loop': false,
                'dots': true,
                'nav': false
            }">
                @if($slider && $slider->activeSlides->count())
                    @foreach($slider->activeSlides as $slide)
                    <div class="home-slide banner banner-md-vw banner-sm-vw d-flex align-items-center">
                        <img class="slide-bg" style="background-color: #dadada;" src="{{ $slide->image_desktop ? asset('storage/' . $slide->image_desktop) : asset('themes/porto/images/demoes/demo1/slider/slide-1.png') }}" width="880" height="428" alt="{{ $slide->title }}">
                        <div class="banner-layer appear-animate" data-animation-name="fadeInUpShorter">
                            @if($slide->subtitle)
                                <h4 class="{{ $slide->text_color === 'light' ? 'text-white' : '' }} mb-0">{{ $slide->subtitle }}</h4>
                            @endif
                            @if($slide->title)
                                <h2 class="{{ $slide->text_color === 'light' ? 'text-white' : '' }} mb-0">{{ $slide->title }}</h2>
                            @endif
                            @if($slide->description)
                                <h5 class="{{ $slide->text_color === 'light' ? 'text-white' : '' }} text-uppercase d-inline-block mb-0">{!! $slide->description !!}</h5>
                            @endif
                            @if($slide->button_text)
                                <a href="{{ $slide->button_url ?? '#' }}" class="btn btn-dark btn-md ls-10">{{ $slide->button_text }}</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    {{-- Default slides from template when no sliders in DB --}}
                    <div class="home-slide home-slide1 banner banner-md-vw banner-sm-vw d-flex align-items-center">
                        <img class="slide-bg" style="background-color: #2699D0;" src="{{ asset('themes/porto/images/demoes/demo1/slider/slide-1.png') }}" width="880" height="428" alt="home-slider">
                        <div class="banner-layer appear-animate" data-animation-name="fadeInUpShorter">
                            <h4 class="text-white mb-0">Find the Boundaries. Push Through!</h4>
                            <h2 class="text-white mb-0">Summer Sale</h2>
                            <h3 class="text-white text-uppercase m-b-3">70% Off</h3>
                            <a href="{{ url('/shop') }}" class="btn btn-dark btn-md ls-10">Shop Now!</a>
                        </div>
                    </div>
                    <div class="home-slide home-slide2 banner banner-md-vw banner-sm-vw d-flex align-items-center">
                        <img class="slide-bg" style="background-color: #dadada;" src="{{ asset('themes/porto/images/demoes/demo1/slider/slide-2.jpg') }}" width="880" height="428" alt="home-slider">
                        <div class="banner-layer text-uppercase appear-animate" data-animation-name="fadeInUpShorter">
                            <h4 class="m-b-2">Over 200 products with discounts</h4>
                            <h2 class="m-b-3">Great Deals</h2>
                            <a href="{{ url('/shop') }}" class="btn btn-dark btn-md ls-10">Get Yours!</a>
                        </div>
                    </div>
                    <div class="home-slide home-slide3 banner banner-md-vw banner-sm-vw d-flex align-items-center">
                        <img class="slide-bg" style="background-color: #e5e4e2;" src="{{ asset('themes/porto/images/demoes/demo1/slider/slide-3.jpg') }}" width="880" height="428" alt="home-slider">
                        <div class="banner-layer text-uppercase appear-animate" data-animation-name="fadeInUpShorter">
                            <h4 class="m-b-2">Up to 70% off</h4>
                            <h2 class="m-b-3">New Arrivals</h2>
                            <a href="{{ url('/shop') }}" class="btn btn-dark btn-md ls-10">Get Yours!</a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Promo Banners --}}
            <div class="banners-container m-b-2 owl-carousel owl-theme" data-owl-options="{
                'dots': false,
                'margin': 20,
                'loop': false,
                'responsive': {
                    '480': { 'items': 2 },
                    '768': { 'items': 3 }
                }
            }">
                <div class="banner banner1 banner-hover-shadow d-flex align-items-center mb-2 w-100 appear-animate" data-animation-name="fadeInLeftShorter" data-animation-delay="500">
                    <figure class="w-100">
                        <img src="{{ asset('themes/porto/images/demoes/demo1/banners/banner-1.jpg') }}" style="background-color: #dadada;" alt="banner">
                    </figure>
                    <div class="banner-layer">
                        <h3 class="m-b-2">Porto Watches</h3>
                        <h4 class="m-b-4 text-primary"><sup class="text-dark"><del>20%</del></sup>30%<sup>OFF</sup></h4>
                        <a href="{{ url('/shop') }}" class="text-dark text-uppercase ls-10">Shop Now</a>
                    </div>
                </div>
                <div class="banner banner2 text-uppercase banner-hover-shadow d-flex align-items-center mb-2 w-100 appear-animate" data-animation-name="fadeInUpShorter" data-animation-delay="200">
                    <figure class="w-100">
                        <img src="{{ asset('themes/porto/images/demoes/demo1/banners/banner-2.jpg') }}" style="background-color: #dadada;" alt="banner">
                    </figure>
                    <div class="banner-layer text-center">
                        <h3 class="m-b-1 ls-n-20">Deal Promos</h3>
                        <h4 class="text-body">Starting at $99</h4>
                        <a href="{{ url('/shop') }}" class="text-dark text-uppercase ls-10">Shop Now</a>
                    </div>
                </div>
                <div class="banner banner3 banner-hover-shadow d-flex align-items-center mb-2 w-100 appear-animate" data-animation-name="fadeInRightShorter" data-animation-delay="500">
                    <figure class="w-100">
                        <img src="{{ asset('themes/porto/images/demoes/demo1/banners/banner-3.jpg') }}" style="background-color: #dadada;" alt="banner">
                    </figure>
                    <div class="banner-layer text-right">
                        <h3 class="m-b-2">Handbags</h3>
                        <h4 class="mb-3 text-secondary text-uppercase">Starting at $99</h4>
                        <a href="{{ url('/shop') }}" class="text-dark text-uppercase ls-10">Shop Now</a>
                    </div>
                </div>
            </div>

            {{-- Featured Products --}}
            @if(Setting::get('home.show_featured_products', '1') === '1')
            <h2 class="section-title ls-n-10 m-b-4 appear-animate" data-animation-name="fadeInUpShorter">
                {{ Setting::get('home.featured_title', 'Featured Products') }}
            </h2>
            @if($featuredProducts->count())
                <div class="products-slider owl-carousel owl-theme dots-top dots-small m-b-1 pb-1 appear-animate" data-animation-name="fadeInUpShorter">
                    @foreach($featuredProducts as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted py-4">No featured products yet. Add products from the admin panel.</p>
            @endif
            @endif

            {{-- Brands Slider --}}
            @if(Setting::get('home.show_brands', '1') === '1' && $brands->count())
            <div class="brands-slider owl-carousel owl-theme images-center appear-animate" data-animation-name="fadeIn" data-animation-duration="700" data-owl-options="{
                'margin': 0,
                'responsive': {
                    '768': { 'items': 4 },
                    '991': { 'items': 4 },
                    '1200': { 'items': 5 }
                }
            }">
                @foreach($brands as $brand)
                    <a href="{{ url('/shop?brand=' . $brand->slug) }}">
                        <img src="{{ $brand->logo ? asset('storage/' . $brand->logo) : asset('themes/porto/images/brands/small/brand1.png') }}" width="140" height="60" alt="{{ $brand->name }}">
                    </a>
                @endforeach
            </div>
            @endif

            {{-- Product Widgets Row --}}
            <div class="row products-widgets">
                <div class="col-sm-6 col-md-4 pb-4 pb-md-0 appear-animate" data-animation-name="fadeInLeftShorter" data-animation-delay="200">
                    <div class="product-column">
                        <h3 class="section-sub-title ls-n-20">Top Rated Products</h3>
                        @foreach($topRated as $product)
                            @include('partials.product-widget', ['product' => $product])
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 pb-4 pb-md-0 appear-animate" data-animation-name="fadeInLeftShorter" data-animation-delay="500">
                    <div class="product-column">
                        <h3 class="section-sub-title ls-n-20">Best Selling Products</h3>
                        @foreach($bestSelling as $product)
                            @include('partials.product-widget', ['product' => $product])
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 pb-4 pb-md-0 appear-animate" data-animation-name="fadeInRightShorter" data-animation-delay="200">
                    <div class="product-column">
                        <h3 class="section-sub-title ls-n-20">Latest Products</h3>
                        @foreach($latestProducts as $product)
                            @include('partials.product-widget', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar (right column for home) --}}
        <div class="col-lg-3 sidebar-home">
            {{-- Static sidebar banners from template --}}
            <div class="widget widget-banner">
                <div class="banner banner-image appear-animate" data-animation-name="fadeInRightShorter">
                    <a href="{{ url('/shop') }}">
                        <img src="{{ asset('themes/porto/images/demoes/demo1/banners/banner-sidebar.jpg') }}" alt="banner" width="280" style="background-color: #dadada;">
                    </a>
                </div>
            </div>

            {{-- New Arrivals side --}}
            @if(Setting::get('home.show_new_arrivals', '1') === '1' && $newArrivals->count())
            <div class="widget widget-products appear-animate" data-animation-name="fadeInRightShorter" data-animation-delay="300">
                <h4 class="widget-title">{{ Setting::get('home.new_arrivals_title', 'New Arrivals') }}</h4>
                <div class="products-slider owl-carousel owl-theme dots-top m-b-2 pb-1" data-owl-options="{'loop': false}">
                    @foreach($newArrivals->chunk(3) as $chunk)
                    <div class="product-col">
                        @foreach($chunk as $product)
                            @include('partials.product-widget', ['product' => $product])
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
