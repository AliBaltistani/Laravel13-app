@extends('layouts.account')

@section('meta_title', 'Order #' . $order->order_number . ' - ' . Setting::get('general.site_name', 'Porto Shop'))

@php $title = 'Order Details'; @endphp

@section('account-content')
<div class="tab-pane fade show active">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="account-sub-title mb-0">Order #{{ $order->order_number }}</h3>
        <div>
            <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-secondary mr-1"><i class="fa fa-arrow-left"></i> Back</a>
            <a href="{{ route('account.orders.invoice', $order) }}" class="btn btn-sm btn-outline-dark"><i class="fa fa-download"></i> Invoice</a>
        </div>
    </div>

    {{-- Order Meta --}}
    <div class="row mb-3">
        <div class="col-sm-6 col-md-3 mb-2">
            <strong>Date:</strong><br>{{ $order->created_at->format('M d, Y h:i A') }}
        </div>
        <div class="col-sm-6 col-md-3 mb-2">
            <strong>Status:</strong><br>
            @php
                $badgeColor = match($order->status) {
                    'pending' => 'warning', 'processing' => 'info', 'shipped' => 'primary',
                    'delivered' => 'success', 'cancelled' => 'danger', default => 'secondary',
                };
            @endphp
            <span class="badge badge-{{ $badgeColor }}">{{ ucfirst($order->status) }}</span>
        </div>
        <div class="col-sm-6 col-md-3 mb-2">
            <strong>Payment:</strong><br>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
            <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span>
        </div>
        <div class="col-sm-6 col-md-3 mb-2">
            <strong>Shipping:</strong><br>{{ $order->shipping_method_name }}
        </div>
    </div>

    {{-- Order Items --}}
    <div class="table-responsive mb-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width:60px"></th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        @if($item->product_image)
                            <img src="{{ asset('storage/' . $item->product_image) }}" width="50" height="50" alt="{{ $item->product_name }}">
                        @else
                            <img src="{{ asset('images/no-image.svg') }}" width="50" height="50" alt="product">
                        @endif
                    </td>
                    <td>
                        {{ $item->product_name }}
                        @if($item->variant_name) <small class="text-muted d-block">{{ $item->variant_name }}</small> @endif
                        <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                    </td>
                    <td>@price($item->unit_price)</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">@price($item->total)</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Subtotal</strong></td>
                    <td class="text-right">@price($order->subtotal)</td>
                </tr>
                @if($order->discount_amount > 0)
                <tr>
                    <td colspan="4" class="text-right">Discount {{ $order->coupon_code ? '('.$order->coupon_code.')' : '' }}</td>
                    <td class="text-right text-success">-@price($order->discount_amount)</td>
                </tr>
                @endif
                <tr>
                    <td colspan="4" class="text-right">Shipping</td>
                    <td class="text-right">@price($order->shipping_amount)</td>
                </tr>
                @if($order->tax_amount > 0)
                <tr>
                    <td colspan="4" class="text-right">Tax</td>
                    <td class="text-right">@price($order->tax_amount)</td>
                </tr>
                @endif
                <tr>
                    <td colspan="4" class="text-right"><strong>Total</strong></td>
                    <td class="text-right"><strong>@price($order->total)</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Addresses --}}
    <div class="row mb-3">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Billing Address</h5>
                    <p class="mb-0">
                        {{ $order->billing_first_name }} {{ $order->billing_last_name }}<br>
                        {{ $order->billing_address_line1 }}<br>
                        @if($order->billing_address_line2) {{ $order->billing_address_line2 }}<br> @endif
                        {{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}<br>
                        @if($order->billing_phone) Phone: {{ $order->billing_phone }} @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Shipping Address</h5>
                    <p class="mb-0">
                        {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}<br>
                        {{ $order->shipping_address_line1 }}<br>
                        @if($order->shipping_address_line2) {{ $order->shipping_address_line2 }}<br> @endif
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                        @if($order->shipping_phone) Phone: {{ $order->shipping_phone }} @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tracking --}}
    @if($order->shipments->count())
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Tracking Information</h5>
            @foreach($order->shipments as $shipment)
                <p class="mb-1">
                    <strong>{{ $shipment->carrier }}</strong> — Tracking: 
                    @if($shipment->tracking_url)
                        <a href="{{ $shipment->tracking_url }}" target="_blank">{{ $shipment->tracking_number }}</a>
                    @else
                        {{ $shipment->tracking_number }}
                    @endif
                    <br>
                    <small class="text-muted">Shipped: {{ $shipment->shipped_at?->format('M d, Y') }}
                    @if($shipment->estimated_delivery) | Est. Delivery: {{ $shipment->estimated_delivery->format('M d, Y') }} @endif
                    </small>
                </p>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Status History --}}
    @if($order->statusHistory->count())
    <h5>Order History</h5>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead><tr><th>Date</th><th>Status</th><th>Note</th></tr></thead>
            <tbody>
                @foreach($order->statusHistory->sortByDesc('created_at') as $history)
                <tr>
                    <td>{{ $history->created_at->format('M d, Y h:i A') }}</td>
                    <td><span class="badge badge-secondary">{{ ucfirst($history->status) }}</span></td>
                    <td>{{ $history->comment }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($order->customer_notes)
    <div class="mt-3">
        <strong>Your Notes:</strong>
        <p class="text-muted">{{ $order->customer_notes }}</p>
    </div>
    @endif
</div>
@endsection
