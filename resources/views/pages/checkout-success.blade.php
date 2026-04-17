@extends('layouts.app')

@section('meta_title', 'Order Confirmed - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', ['title' => 'Order Confirmed'])

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
            <a href="{{ url('/checkout') }}" class="ck-progress-step completed">
                <span class="ck-progress-step__circle">
                    <i class="fas fa-check"></i>
                </span>
                <span class="ck-progress-step__label">Checkout</span>
            </a>
            <div class="ck-progress-line active"></div>
            <div class="ck-progress-step active">
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
                <i class="fas fa-check-circle text-success" style="font-size: 72px;"></i>
            </div>
            <h2>Thank You for Your Order!</h2>
            <p class="text-muted mb-1">Your order has been placed successfully.</p>
            <p class="mb-4"><strong>Order Number: {{ $order->order_number }}</strong></p>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Order Summary</h5>
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product_name }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-right">@price($item->total)</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><strong>Subtotal</strong></td>
                                        <td class="text-right">@price($order->subtotal)</td>
                                    </tr>
                                    @if($order->discount_amount > 0)
                                    <tr>
                                        <td colspan="2">Discount {{ $order->coupon_code ? '('.$order->coupon_code.')' : '' }}</td>
                                        <td class="text-right text-success">-@price($order->discount_amount)</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2">Shipping ({{ $order->shipping_method_name }})</td>
                                        <td class="text-right">@price($order->shipping_amount)</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td class="text-right"><strong>@price($order->total)</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                @if($order->payment_method === 'bank_transfer')
                <div class="alert alert-info mb-3">
                    <strong>Bank Transfer Instructions:</strong><br>
                    Please transfer <strong>@price($order->total)</strong> to our bank account.<br>
                    Use your order number <strong>{{ $order->order_number }}</strong> as payment reference.<br>
                    Your order will be processed once payment is confirmed.
                </div>
                @endif

                @auth
                    <a href="{{ route('account.orders') }}" class="btn btn-dark mr-2">View My Orders</a>
                @endauth
                <a href="{{ url('/shop') }}" class="btn btn-outline-dark">Continue Shopping</a>
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
