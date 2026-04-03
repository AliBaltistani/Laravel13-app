@extends('layouts.account')

@section('meta_title', 'My Orders - ' . Setting::get('general.site_name', 'Porto Shop'))

@php $title = 'My Orders'; @endphp

@section('account-content')
<div class="tab-pane fade show active">
    <h3 class="account-sub-title d-none d-md-block mb-3">
        <i class="sicon-social-dropbox align-middle mr-3"></i>Orders
    </h3>

    @if($orders->count())
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
                @foreach($orders as $order)
                <tr>
                    <td><a href="{{ route('account.orders.show', $order->order_number) }}">#{{ $order->order_number }}</a></td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        @php
                            $badgeColor = match($order->status) {
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                                'refunded' => 'secondary',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-{{ $badgeColor }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td>@price($order->total) <small class="text-muted">({{ $order->items_count ?? $order->items()->count() }} items)</small></td>
                    <td>
                        <a href="{{ route('account.orders.show', $order->order_number) }}" class="btn btn-sm btn-outline-primary">View</a>
                        @if($order->status === 'pending')
                            <form action="{{ route('account.orders.cancel', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
    @else
    <p class="text-muted mb-3">No orders have been made yet.</p>
    <a href="{{ url('/shop') }}" class="btn btn-dark">Browse Products <i class="fa fa-arrow-right"></i></a>
    @endif
</div>
@endsection
