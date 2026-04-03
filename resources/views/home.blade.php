@extends('layouts.app')

@section('is_home', 'true')
@section('header-class', 'home')
@section('header-top-class', 'bg-primary text-uppercase')
@section('main-class', 'home')

@section('content')
<div class="container mb-2">
    {{-- Info Boxes (Admin-configurable) --}}
    @if(Setting::get('home.show_info_boxes', '1') === '1')
    <div class="info-boxes-container row row-joined mb-2 font2">
        <div class="info-box info-box-icon-left col-lg-4">
            <i class="{{ Setting::get('home.info_box1_icon', 'icon-shipping') }}"></i>
            <div class="info-box-content">
                <h4>{{ Setting::get('home.info_box1_title', 'FREE SHIPPING & RETURN') }}</h4>
                <p class="text-body">{{ Setting::get('home.info_box1_text', 'Free shipping on all orders over $99') }}</p>
            </div>
        </div>
        <div class="info-box info-box-icon-left col-lg-4">
            <i class="{{ Setting::get('home.info_box2_icon', 'icon-money') }}"></i>
            <div class="info-box-content">
                <h4>{{ Setting::get('home.info_box2_title', 'MONEY BACK GUARANTEE') }}</h4>
                <p class="text-body">{{ Setting::get('home.info_box2_text', '100% money back guarantee') }}</p>
            </div>
        </div>
        <div class="info-box info-box-icon-left col-lg-4">
            <i class="{{ Setting::get('home.info_box3_icon', 'icon-support') }}"></i>
            <div class="info-box-content">
                <h4>{{ Setting::get('home.info_box3_title', 'ONLINE SUPPORT 24/7') }}</h4>
                <p class="text-body">{{ Setting::get('home.info_box3_text', 'Get support any time you need') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-9">
            {{-- Hero Slider (Admin-managed only) --}}
            @if($slider && $slider->activeSlides->count())
            <div class="home-slider slide-animate owl-carousel owl-theme mb-2" data-owl-options="{
                'loop': false,
                'dots': true,
                'nav': false
            }">
                @foreach($slider->activeSlides as $slide)
                <div class="home-slide banner banner-md-vw banner-sm-vw d-flex align-items-center">
                    @if($slide->image_desktop)
                        <img class="slide-bg" style="background-color: #dadada;" src="{{ asset('storage/' . $slide->image_desktop) }}" width="880" height="428" alt="{{ $slide->title }}">
                    @endif
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
            </div>
            @endif

            {{-- Promo Banners (Admin-managed via Banner model, position: home-mid) --}}
            @if(Setting::get('home.show_promo_banners', '1') === '1' && $banners->count())
            <div class="banners-container m-b-2 owl-carousel owl-theme" data-owl-options="{
                'dots': false,
                'margin': 20,
                'loop': false,
                'responsive': {
                    '480': { 'items': 2 },
                    '768': { 'items': 3 }
                }
            }">
                @foreach($banners as $banner)
                <div class="banner banner-hover-shadow d-flex align-items-center mb-2 w-100 appear-animate" data-animation-name="fadeInUpShorter" data-animation-delay="200">
                    @if($banner->image)
                    <figure class="w-100">
                        <img src="{{ asset('storage/' . $banner->image) }}" style="background-color: #dadada;" alt="{{ $banner->title }}">
                    </figure>
                    @endif
                    <div class="banner-layer">
                        @if($banner->title)
                            <h3 class="m-b-2">{{ $banner->title }}</h3>
                        @endif
                        @if($banner->subtitle)
                            <h4 class="mb-3 text-primary text-uppercase">{{ $banner->subtitle }}</h4>
                        @endif
                        @if($banner->button_text)
                            <a href="{{ $banner->button_url ?? url('/shop') }}" class="text-dark text-uppercase ls-10">{{ $banner->button_text }}</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

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
                    @if($brand->logo)
                    <a href="{{ url('/shop?brand=' . $brand->slug) }}">
                        <img src="{{ asset('storage/' . $brand->logo) }}" width="140" height="60" alt="{{ $brand->name }}">
                    </a>
                    @endif
                @endforeach
            </div>
            @endif

            {{-- Product Widgets Row --}}
            <div class="row products-widgets">
                @if($topRated->count())
                <div class="col-sm-6 col-md-4 pb-4 pb-md-0 appear-animate" data-animation-name="fadeInLeftShorter" data-animation-delay="200">
                    <div class="product-column">
                        <h3 class="section-sub-title ls-n-20">Top Rated Products</h3>
                        @foreach($topRated as $product)
                            @include('partials.product-widget', ['product' => $product])
                        @endforeach
                    </div>
                </div>
                @endif
                @if($bestSelling->count())
                <div class="col-sm-6 col-md-4 pb-4 pb-md-0 appear-animate" data-animation-name="fadeInLeftShorter" data-animation-delay="500">
                    <div class="product-column">
                        <h3 class="section-sub-title ls-n-20">Best Selling Products</h3>
                        @foreach($bestSelling as $product)
                            @include('partials.product-widget', ['product' => $product])
                        @endforeach
                    </div>
                </div>
                @endif
                @if($latestProducts->count())
                <div class="col-sm-6 col-md-4 pb-4 pb-md-0 appear-animate" data-animation-name="fadeInRightShorter" data-animation-delay="200">
                    <div class="product-column">
                        <h3 class="section-sub-title ls-n-20">Latest Products</h3>
                        @foreach($latestProducts as $product)
                            @include('partials.product-widget', ['product' => $product])
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar (right column for home) --}}
        <div class="col-lg-3 sidebar-home">
            {{-- Sidebar Banner (Admin-managed, position: home-sidebar) --}}
            @if($sidebarBanners->count())
                @foreach($sidebarBanners as $sBanner)
                <div class="widget widget-banner">
                    <div class="banner banner-image appear-animate" data-animation-name="fadeInRightShorter">
                        <a href="{{ $sBanner->button_url ?? url('/shop') }}">
                            @if($sBanner->image)
                                <img src="{{ asset('storage/' . $sBanner->image) }}" alt="{{ $sBanner->title }}" width="280" style="background-color: #dadada;">
                            @endif
                        </a>
                    </div>
                </div>
                @endforeach
            @endif

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
