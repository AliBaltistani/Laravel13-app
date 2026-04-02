@extends('layouts.admin')

@section('title', 'Dashboard')

@section('admin-content')
{{-- Stat Cards --}}
<div class="row mb-4">
    <div class="col-sm-6 col-xl-3 mb-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary-soft"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-info">
                <h3>${{ number_format($todayRevenue, 2) }}</h3>
                <p>Today's Revenue</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-3">
        <div class="stat-card">
            <div class="stat-icon bg-success-soft"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-info">
                <h3>{{ $todayOrders }}</h3>
                <p>New Orders Today</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-3">
        <div class="stat-card">
            <div class="stat-icon bg-warning-soft"><i class="fas fa-user-plus"></i></div>
            <div class="stat-info">
                <h3>{{ $todayCustomers }}</h3>
                <p>New Customers Today</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-3">
        <div class="stat-card">
            <div class="stat-icon bg-danger-soft"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-info">
                <h3>{{ $lowStockCount }}</h3>
                <p>Low Stock Items</p>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row mb-4">
    <div class="col-lg-8 mb-3">
        <div class="admin-card">
            <div class="card-header">
                <h5>Revenue — Last 30 Days</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="admin-card">
            <div class="card-header">
                <h5>Orders by Status</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tables Row --}}
<div class="row">
    <div class="col-lg-8 mb-3">
        <div class="admin-card">
            <div class="card-header">
                <h5>Recent Orders</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="font-weight-bold text-primary">#{{ $order->order_number }}</a></td>
                                <td>{{ $order->user?->name ?? 'Guest' }}</td>
                                <td><span class="badge-status badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                                <td><span class="badge-status badge-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span></td>
                                <td class="font-weight-bold">${{ number_format($order->total, 2) }}</td>
                                <td>{{ $order->created_at->format('M d, H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No orders yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="admin-card">
            <div class="card-header">
                <h5>Low Stock Alert</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-dark">
                                        {{ Str::limit($product->name, 25) }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $product->sku }}</td>
                                <td>
                                    <span class="font-weight-bold {{ $product->stock_quantity <= 0 ? 'text-danger' : 'text-warning' }}">
                                        {{ $product->stock_quantity }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">All products stocked.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueData = @json($revenueChart);
    const labels = revenueData.map(d => d.date);
    const revenues = revenueData.map(d => parseFloat(d.revenue));

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue ($)',
                data: revenues,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.08)',
                fill: true,
                tension: 0.3,
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => '$' + v.toLocaleString() }
                },
                x: {
                    ticks: { maxTicksLimit: 10 }
                }
            }
        }
    });

    // Orders by Status Chart
    const statusData = @json($ordersByStatus);
    const statusLabels = Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1));
    const statusValues = Object.values(statusData);
    const statusColors = {
        'Pending': '#ffc107', 'Processing': '#0d6efd', 'Shipped': '#17a2b8',
        'Delivered': '#28a745', 'Cancelled': '#dc3545', 'Refunded': '#6c757d'
    };

    new Chart(document.getElementById('orderStatusChart'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: statusLabels.map(l => statusColors[l] || '#adb5bd'),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } }
            },
            cutout: '65%'
        }
    });
});
</script>
@endpush
