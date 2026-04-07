@extends('layouts.app')

@section('content')
<main class="main">
    <div class="page-header">
        <div class="container d-flex flex-column align-items-center">
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Promotions & Coupons</li>
                    </ol>
                </div>
            </nav>
            <h1>Current Promotions</h1>
        </div>
    </div>

    <div class="container mt-4 mb-5">
        <div class="row">
            @forelse($coupons as $coupon)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card bg-light border-0 shadow-sm" style="border-radius: 10px;">
                        <div class="card-body text-center p-5">
                            <h3 class="mb-2 text-uppercase">{{ $coupon->name }}</h3>
                            <h2 class="text-primary font-weight-bolder mb-3" style="font-size: 3rem;">
                                @if($coupon->type === 'percent')
                                    {{ rtrim(rtrim($coupon->value, '0'), '.') }}% OFF
                                @elseif($coupon->type === 'fixed')
                                    @price($coupon->value) OFF
                                @else
                                    FREE SHIPPING
                                @endif
                            </h2>
                            <p class="text-muted mb-3">
                                @if($coupon->min_order_amount > 0)
                                    On orders over @price($coupon->min_order_amount)
                                @else
                                    No minimum order requirement
                                @endif
                            </p>
                            <div class="coupon-code-box mx-auto" style="border: 2px dashed #08c; background: #fff; padding: 10px 20px; border-radius: 5px; cursor: pointer; position: relative;" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); alert('Coupon code {{ $coupon->code }} copied to clipboard!');">
                                <span class="font-weight-bold" style="font-size: 1.5rem; letter-spacing: 2px;">{{ $coupon->code }}</span>
                                <div class="click-to-copy text-muted mt-1" style="font-size: 0.8rem;"><i class="icon-copy mr-1"></i>Click to Copy</div>
                            </div>
                            @if($coupon->expires_at)
                                <small class="d-block mt-3 text-danger">Expires: {{ $coupon->expires_at->format('M d, Y') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="icon-tags d-block mb-3" style="font-size: 4rem;"></i>
                    <h4>No active promotions at this time.</h4>
                    <p>Please check back later or subscribe to our newsletter for exclusive offers!</p>
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
