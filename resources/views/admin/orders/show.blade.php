@extends('layouts.admin')
@section('title', 'Order #' . $order->order_number)
@section('breadcrumb')
<li><a href="{{ route('admin.orders.index') }}">Orders</a></li>
<li class="active">#{{ $order->order_number }}</li>
@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Order #{{ $order->order_number }}</h4>
    <div>
        <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="fas fa-print mr-1"></i> Invoice</a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-arrow-left mr-1"></i> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        {{-- Items --}}
        <div class="admin-card mb-3">
            <div class="card-header"><h5>Order Items</h5></div>
            <div class="card-body p-0">
                <table class="admin-table">
                    <thead><tr><th>Product</th><th>SKU</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->product_image)
                                    <img src="{{ Storage::url($item->product_image) }}" width="40" height="40" class="mr-2 rounded" style="object-fit:cover;">
                                    @endif
                                    <div>
                                        <strong>{{ $item->product_name }}</strong>
                                        @if($item->variant_name)<br><small class="text-muted">{{ $item->variant_name }}</small>@endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $item->product_sku }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="font-weight-bold">${{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="4" class="text-right">Subtotal:</td><td class="font-weight-bold">${{ number_format($order->subtotal, 2) }}</td></tr>
                        @if($order->discount_amount > 0)
                        <tr><td colspan="4" class="text-right">Discount ({{ $order->coupon_code }}):</td><td class="text-success">-${{ number_format($order->discount_amount, 2) }}</td></tr>
                        @endif
                        @if($order->shipping_amount > 0)
                        <tr><td colspan="4" class="text-right">Shipping ({{ $order->shipping_method_name }}):</td><td>${{ number_format($order->shipping_amount, 2) }}</td></tr>
                        @endif
                        @if($order->tax_amount > 0)
                        <tr><td colspan="4" class="text-right">Tax:</td><td>${{ number_format($order->tax_amount, 2) }}</td></tr>
                        @endif
                        <tr><td colspan="4" class="text-right font-weight-bold" style="font-size:16px;">Total:</td><td class="font-weight-bold" style="font-size:16px;">${{ number_format($order->total, 2) }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Addresses --}}
        <div class="row">
            <div class="col-md-6">
                <div class="admin-card mb-3">
                    <div class="card-header"><h5>Billing Address</h5></div>
                    <div class="card-body">
                        <p class="mb-1"><strong>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</strong></p>
                        @if($order->billing_company)<p class="mb-1 text-muted">{{ $order->billing_company }}</p>@endif
                        <p class="mb-1">{{ $order->billing_address_line1 }}</p>
                        @if($order->billing_address_line2)<p class="mb-1">{{ $order->billing_address_line2 }}</p>@endif
                        <p class="mb-1">{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}</p>
                        <p class="mb-0">{{ $order->billing_phone }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="admin-card mb-3">
                    <div class="card-header"><h5>Shipping Address</h5></div>
                    <div class="card-body">
                        <p class="mb-1"><strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong></p>
                        <p class="mb-1">{{ $order->shipping_address_line1 }}</p>
                        @if($order->shipping_address_line2)<p class="mb-1">{{ $order->shipping_address_line2 }}</p>@endif
                        <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
                        <p class="mb-0">{{ $order->shipping_phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status History --}}
        <div class="admin-card mb-3">
            <div class="card-header"><h5>Status History</h5></div>
            <div class="card-body">
                @forelse($order->statusHistories as $history)
                <div class="d-flex align-items-start mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <span class="badge-status badge-{{ $history->status }} mr-3" style="min-width:90px;text-align:center;">{{ ucfirst($history->status) }}</span>
                    <div>
                        @if($history->comment)<p class="mb-1">{{ $history->comment }}</p>@endif
                        <small class="text-muted">
                            {{ $history->created_at->format('M d, Y H:i') }}
                            @if($history->creator) — by {{ $history->creator->name }} @endif
                            @if($history->is_customer_notified) <span class="badge badge-info">Notified</span> @endif
                        </small>
                    </div>
                </div>
                @empty
                <p class="text-muted">No status changes recorded.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Update Status --}}
        <div class="admin-card mb-3">
            <div class="card-header"><h5>Update Status</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Order Status</label>
                        <select name="status" class="form-control">
                            @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Comment (optional)</label>
                        <textarea name="comment" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="notify_customer" name="notify_customer" value="1">
                        <label class="custom-control-label" for="notify_customer">Notify Customer</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                </form>
            </div>
        </div>

        {{-- Tracking --}}
        <div class="admin-card mb-3">
            <div class="card-header"><h5>Tracking</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.tracking', $order) }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Tracking Number</label>
                        <input type="text" name="tracking_number" class="form-control" value="{{ $order->shipments->first()?->tracking_number }}">
                    </div>
                    <div class="form-group">
                        <label>Carrier</label>
                        <select name="carrier" class="form-control">
                            <option value="">Select...</option>
                            @foreach(['FedEx','UPS','USPS','DHL','Other'] as $c)
                            <option value="{{ $c }}" {{ $order->shipments->first()?->carrier === $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tracking URL</label>
                        <input type="url" name="tracking_url" class="form-control" value="{{ $order->shipments->first()?->tracking_url }}">
                    </div>
                    <button type="submit" class="btn btn-dark btn-block">Save Tracking</button>
                </form>
            </div>
        </div>

        {{-- Admin Notes --}}
        <div class="admin-card mb-3">
            <div class="card-header"><h5>Order Info</h5></div>
            <div class="card-body">
                <p class="mb-1"><small class="text-muted">Payment Method:</small><br><strong>{{ ucfirst($order->payment_method ?? 'N/A') }}</strong></p>
                <p class="mb-1"><small class="text-muted">Transaction ID:</small><br>{{ $order->payment_transaction_id ?? '—' }}</p>
                <p class="mb-1"><small class="text-muted">IP Address:</small><br>{{ $order->ip_address ?? '—' }}</p>
                <p class="mb-0"><small class="text-muted">Placed:</small><br>{{ $order->created_at->format('M d, Y \a\t H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
