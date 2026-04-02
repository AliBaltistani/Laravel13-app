@extends('layouts.account')

@section('meta_title', 'Dashboard - ' . Setting::get('general.site_name', 'Porto Shop'))

@php $title = 'My Account'; @endphp

@section('account-content')
<div class="tab-pane fade show active">
    <div class="mb-4">
        <p>Hello <strong>{{ $user->full_name }}</strong> (not {{ $user->full_name }}? <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('account-logout-form').submit();">Log out</a>)</p>
        <p>From your account dashboard you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.</p>
    </div>

    @if($recentOrders->count())
    <h4 class="mb-3">Recent Orders</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td>#{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td><span class="badge badge-{{ $order->status_badge_color }}">{{ ucfirst($order->status) }}</span></td>
                    <td>${{ number_format($order->total, 2) }}</td>
                    <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-muted">No orders yet.</p>
    @endif
</div>
@endsection
