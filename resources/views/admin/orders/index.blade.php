@extends('layouts.admin')
@section('title', 'Orders')
@section('breadcrumb')<li class="active">Orders</li>@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Orders</h4>
    <a href="{{ route('admin.orders.export') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-download mr-1"></i> Export CSV</a>
</div>

<div class="admin-card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Order #, customer..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">Status</label>
                <select name="status" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">Payment</label>
                <select name="payment_status" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach(['paid','unpaid','failed','refunded'] as $s)
                    <option value="{{ $s }}" {{ request('payment_status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">From</label>
                <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">To</label>
                <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
            </div>
            <div class="col-md-1 mb-2">
                <button type="submit" class="btn btn-dark btn-sm btn-block">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Method</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="font-weight-bold text-primary">#{{ $order->order_number }}</a></td>
                        <td>{{ $order->user?->name ?? 'Guest' }}<br><small class="text-muted">{{ $order->user?->email }}</small></td>
                        <td><span class="badge-status badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        <td><span class="badge-status badge-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span></td>
                        <td>{{ ucfirst($order->payment_method ?? '—') }}</td>
                        <td class="font-weight-bold">@price($order->total)</td>
                        <td>{{ $order->created_at->format('M d, Y') }}<br><small class="text-muted">{{ $order->created_at->format('H:i') }}</small></td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5"><i class="fas fa-shopping-bag fa-2x mb-2 d-block"></i>No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
