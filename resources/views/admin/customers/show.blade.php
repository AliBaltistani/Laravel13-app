@extends('layouts.admin')
@section('title', $user->name)
@section('breadcrumb')<li><a href="{{ route('admin.customers.index') }}">Customers</a></li><li class="active">{{ $user->name }}</li>@endsection
@section('admin-content')
<div class="row">
    <div class="col-lg-4">
        <div class="admin-card mb-3"><div class="card-header"><h5>Customer Info</h5></div><div class="card-body text-center">
            <div class="admin-avatar mx-auto mb-3" style="width:60px;height:60px;font-size:24px;">{{ strtoupper(substr($user->name,0,1)) }}</div>
            <h5>{{ $user->name }}</h5>
            <p class="text-muted mb-1">{{ $user->email }}</p>
            <p class="text-muted mb-3">{{ $user->phone ?? 'No phone' }}</p>
            <div class="row text-center">
                <div class="col-4"><h5 class="mb-0">{{ $user->orders_count ?? $user->orders->count() }}</h5><small class="text-muted">Orders</small></div>
                <div class="col-4"><h5 class="mb-0">@price($totalSpent)</h5><small class="text-muted">Spent</small></div>
                <div class="col-4"><h5 class="mb-0">{{ $user->created_at->format('M Y') }}</h5><small class="text-muted">Joined</small></div>
            </div>
            <hr>
            <form method="POST" action="{{ route('admin.customers.toggle', $user) }}">@csrf @method('PUT')
                <button class="btn btn-sm btn-{{ $user->is_active ? 'warning' : 'success' }} btn-block">{{ $user->is_active ? 'Ban Customer' : 'Activate Customer' }}</button>
            </form>
        </div></div>
        {{-- Send Email --}}
        <div class="admin-card mb-3"><div class="card-header"><h5>Send Email</h5></div><div class="card-body">
            <form method="POST" action="{{ route('admin.customers.email', $user) }}">@csrf
                <div class="form-group"><label>Subject</label><input type="text" name="subject" class="form-control" required></div>
                <div class="form-group"><label>Message</label><textarea name="message" class="form-control" rows="4" required></textarea></div>
                <button type="submit" class="btn btn-dark btn-block"><i class="fas fa-paper-plane mr-1"></i> Send</button>
            </form>
        </div></div>
    </div>
    <div class="col-lg-8">
        <div class="admin-card"><div class="card-header"><h5>Order History</h5></div><div class="card-body p-0">
            <table class="admin-table"><thead><tr><th>Order#</th><th>Status</th><th>Payment</th><th>Total</th><th>Date</th></tr></thead><tbody>
            @forelse($user->orders as $o)
            <tr>
                <td><a href="{{ route('admin.orders.show', $o) }}" class="text-primary font-weight-bold">#{{ $o->order_number }}</a></td>
                <td><span class="badge-status badge-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                <td><span class="badge-status badge-{{ $o->payment_status }}">{{ ucfirst($o->payment_status) }}</span></td>
                <td class="font-weight-bold">@price($o->total)</td>
                <td>{{ $o->created_at->format('M d, Y') }}</td>
            </tr>
            @empty<tr><td colspan="5" class="text-center text-muted py-4">No orders.</td></tr>@endforelse
            </tbody></table>
        </div></div>
    </div>
</div>
@endsection
