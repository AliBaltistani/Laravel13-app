@extends('layouts.app')

@section('meta_title', 'Shopping Cart - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
<main class="main">
    <div class="container mt-4">
        {{-- Modern Progress Bar --}}
        <div class="checkout-progress-wrapper mb-4 pb-2">
            <ul class="checkout-progress-bar d-flex justify-content-center flex-wrap">
                <li class="active">
                    <a href="{{ url('/cart') }}"><i class="fas fa-shopping-cart mr-1"></i> Shopping Cart</a>
                </li>
                <li>
                    <a href="{{ url('/checkout') }}"><i class="fas fa-credit-card mr-1"></i> Checkout</a>
                </li>
                <li class="disabled">
                    <a href="#"><i class="fas fa-check-circle mr-1"></i> Order Complete</a>
                </li>
            </ul>
        </div>

        @livewire('cart-page')
    </div><!-- End .container -->

    <div class="mb-6"></div><!-- margin -->
</main><!-- End .main -->
@endsection

@push('styles')
<style>
    .checkout-progress-bar {
        font-size: 24px;
        font-weight: 700;
        line-height: 1.2;
        margin: 0;
        list-style: none;
        padding: 0;
        color: #dbdbdb;
    }
    
    .checkout-progress-bar li {
        position: relative;
        padding: 0 25px;
        transition: color .3s ease;
    }
    
    .checkout-progress-bar li::after {
        color: inherit;
        content: '\203A';
        font-size: 32px;
        line-height: 1;
        position: absolute;
        right: -8px;
        top: 50%;
        margin-top: -16px;
    }
    
    .checkout-progress-bar li:last-child::after {
        display: none;
    }
    
    .checkout-progress-bar li a {
        color: inherit;
        text-decoration: none;
    }
    
    .checkout-progress-bar li.active, 
    .checkout-progress-bar li:hover {
        color: #222;
    }
    
    .checkout-progress-bar li.disabled {
        pointer-events: none;
        opacity: 0.8;
    }

    .checkout-progress-bar li.active a i {
        color: #667eea;
    }
</style>
@endpush
