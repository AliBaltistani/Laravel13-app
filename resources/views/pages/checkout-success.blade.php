@extends('layouts.app')

@section('meta_title', 'Order Confirmed - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', ['title' => 'Order Confirmed'])

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
