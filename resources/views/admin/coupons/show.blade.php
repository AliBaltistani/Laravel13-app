@extends('layouts.admin')
@section('title', 'Coupon: ' . $coupon->code)
@section('breadcrumb')<li><a href="{{ route('admin.coupons.index') }}">Coupons</a></li><li class="active">{{ $coupon->code }}</li>@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-4">
        <div class="admin-card mb-3">
            <div class="card-body text-center py-4">
                <code style="font-size:28px;font-weight:700;letter-spacing:2px;">{{ $coupon->code }}</code>
                <div class="mt-2">
                    @switch($coupon->status)
                        @case('active') <span class="badge badge-success" style="font-size:14px;">Active</span> @break
                        @case('expired') <span class="badge badge-danger" style="font-size:14px;">Expired</span> @break
                        @case('scheduled') <span class="badge badge-info" style="font-size:14px;">Scheduled</span> @break
                        @case('inactive') <span class="badge badge-secondary" style="font-size:14px;">Inactive</span> @break
                        @case('exhausted') <span class="badge badge-warning" style="font-size:14px;">Exhausted</span> @break
                    @endswitch
                </div>
            </div>
        </div>

        <div class="admin-card mb-3">
            <div class="card-header"><h5>Details</h5></div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">Name</td><td class="font-weight-bold">{{ $coupon->name ?: '-' }}</td></tr>
                    <tr><td class="text-muted">Type</td><td>
                        @if($coupon->type === 'percent') {{ $coupon->value }}% Off
                        @elseif($coupon->type === 'fixed') ${{ number_format($coupon->value, 2) }} Off
                        @else Free Shipping @endif
                    </td></tr>
                    <tr><td class="text-muted">Min Order</td><td>{{ $coupon->min_order_amount ? '$' . number_format($coupon->min_order_amount, 2) : 'None' }}</td></tr>
                    <tr><td class="text-muted">Max Discount</td><td>{{ $coupon->max_discount ? '$' . number_format($coupon->max_discount, 2) : 'None' }}</td></tr>
                    <tr><td class="text-muted">Starts</td><td>{{ $coupon->starts_at ? $coupon->starts_at->format('M d, Y H:i') : 'Immediate' }}</td></tr>
                    <tr><td class="text-muted">Expires</td><td>{{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y H:i') : 'Never' }}</td></tr>
                    <tr><td class="text-muted">Created</td><td>{{ $coupon->created_at->format('M d, Y') }}</td></tr>
                </table>
            </div>
        </div>

        <div class="admin-card mb-3">
            <div class="card-header"><h5>Usage Stats</h5></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Times Used</span>
                    <strong>{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}</strong>
                </div>
                @if($coupon->usage_limit)
                <div class="progress mb-3" style="height:8px;">
                    <div class="progress-bar {{ $coupon->used_count >= $coupon->usage_limit ? 'bg-danger' : 'bg-success' }}" style="width:{{ min(100, ($coupon->used_count / $coupon->usage_limit) * 100) }}%"></div>
                </div>
                @endif
                <div class="d-flex justify-content-between mb-2">
                    <span>Revenue Generated</span>
                    <strong class="text-success">${{ number_format($totalRevenue, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Total Discount Given</span>
                    <strong class="text-danger">${{ number_format($totalDiscount, 2) }}</strong>
                </div>
            </div>
        </div>

        <div class="d-flex" style="gap:8px;">
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-primary btn-sm flex-fill"><i class="fas fa-edit mr-1"></i> Edit</a>
            <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}" class="flex-fill">@csrf @method('PUT')
                <button class="btn btn-{{ $coupon->is_active ? 'warning' : 'success' }} btn-sm btn-block"><i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }} mr-1"></i> {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header">
                <h5>Orders Using This Coupon</h5>
                <span class="badge badge-primary">{{ $orders->total() }} orders</span>
            </div>
            <div class="card-body p-0">
                <table class="admin-table">
                    <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Discount</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->order_number }}</a></td>
                        <td>{{ $order->user->full_name ?? $order->billing_name ?? 'Guest' }}</td>
                        <td class="font-weight-bold">${{ number_format($order->total, 2) }}</td>
                        <td class="text-danger">${{ number_format($order->discount, 2) }}</td>
                        <td class="text-muted" style="font-size:12px;">{{ $order->created_at->format('M d, Y') }}</td>
                        <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No orders have used this coupon yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
