@extends('layouts.app')

@section('meta_title', 'Payment Failed - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', ['title' => 'Payment Failed'])

    <div class="container">
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-times-circle text-danger" style="font-size: 72px;"></i>
            </div>
            <h2>Payment Failed</h2>
            <p class="text-muted mb-3">{{ $errorMessage }}</p>

            @if($orderNumber)
                <p class="mb-4">
                    <strong>Order Number: {{ $orderNumber }}</strong><br>
                    <small class="text-muted">Your order has been saved. You can retry payment or contact us for assistance.</small>
                </p>
            @endif

            <div class="mt-4">
                <a href="{{ route('checkout') }}" class="btn btn-dark mr-2">
                    <i class="fas fa-redo mr-1"></i> Retry Payment
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-dark mr-2">
                    <i class="fas fa-envelope mr-1"></i> Contact Support
                </a>
                <a href="{{ route('cart') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-shopping-cart mr-1"></i> Back to Cart
                </a>
            </div>
        </div>
    </div>
@endsection
