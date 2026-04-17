@extends('layouts.app')

@section('meta_title', 'Payment Failed - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', ['title' => 'Payment Failed'])

    {{-- Modern Step Indicator --}}
    <div class="ck-progress-wrapper">
        <div class="ck-progress-bar">
            <a href="{{ url('/cart') }}" class="ck-progress-step completed">
                <span class="ck-progress-step__circle">
                    <i class="fas fa-check"></i>
                </span>
                <span class="ck-progress-step__label">Cart</span>
            </a>
            <div class="ck-progress-line active"></div>
            <div class="ck-progress-step active">
                <span class="ck-progress-step__circle">
                    <span>2</span>
                </span>
                <span class="ck-progress-step__label">Checkout</span>
            </div>
            <div class="ck-progress-line"></div>
            <div class="ck-progress-step">
                <span class="ck-progress-step__circle">
                    <span>3</span>
                </span>
                <span class="ck-progress-step__label">Confirmation</span>
            </div>
        </div>
    </div>

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

@push('styles')
<style>
    /* ════════════════════════════════════════════════════════════
       DEVOGUE CHECKOUT — Premium Accordion Checkout Redesign
       ════════════════════════════════════════════════════════════ */

    /* ── Progress Bar ── */
    .ck-progress-wrapper {
        margin-top: 36px;
        margin-bottom: 36px;
        padding: 0 20px;
    }
    .ck-progress-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        max-width: 480px;
        margin: 0 auto;
    }
    .ck-progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
        flex-shrink: 0;
    }
    .ck-progress-step__circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-sans);
        font-size: 14px;
        font-weight: 700;
        background: var(--ink-100);
        color: var(--ink-300);
        border: 2px solid var(--ink-100);
        transition: all 0.3s ease;
    }
    .ck-progress-step.active .ck-progress-step__circle {
        background: var(--dv-navy);
        border-color: var(--dv-navy);
        color: #fff;
        box-shadow: 0 4px 14px rgba(43, 54, 116, 0.3);
    }
    .ck-progress-step.completed .ck-progress-step__circle {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }
    .ck-progress-step__label {
        font-family: var(--font-sans);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        color: #666;
    }
    .ck-progress-step.active .ck-progress-step__label,
    .ck-progress-step.completed .ck-progress-step__label {
        color: #222;
    }
    .ck-progress-line {
        flex: 1;
        height: 2px;
        background: var(--ink-100);
        margin: 0 -8px;
        position: relative;
        top: -19px;
        min-width: 40px;
    }
    .ck-progress-line.active {
        background: var(--dv-navy);
    }
</style>
@endpush
