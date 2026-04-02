@extends('layouts.admin')
@section('title', 'Sales Report')
@section('breadcrumb')<li class="active">Sales Report</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Sales Report</h4>
    <a href="{{ route('admin.reports.export', ['type' => 'sales', 'from' => $from, 'to' => $to]) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-download mr-1"></i> Export CSV</a>
</div>
<div class="admin-card mb-3"><div class="card-body py-3">
<form method="GET" class="row align-items-end">
    <div class="col-md-3 mb-2"><label class="small font-weight-bold">From</label><input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}"></div>
    <div class="col-md-3 mb-2"><label class="small font-weight-bold">To</label><input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}"></div>
    <div class="col-md-2 mb-2"><button type="submit" class="btn btn-dark btn-sm btn-block">Apply</button></div>
</form></div></div>

<div class="row mb-4">
    <div class="col-sm-6 col-xl-3 mb-3"><div class="stat-card"><div class="stat-icon bg-primary-soft"><i class="fas fa-shopping-bag"></i></div><div class="stat-info"><h3>{{ $stats->total_orders ?? 0 }}</h3><p>Total Orders</p></div></div></div>
    <div class="col-sm-6 col-xl-3 mb-3"><div class="stat-card"><div class="stat-icon bg-success-soft"><i class="fas fa-dollar-sign"></i></div><div class="stat-info"><h3>${{ number_format($stats->total_revenue ?? 0, 2) }}</h3><p>Total Revenue</p></div></div></div>
    <div class="col-sm-6 col-xl-3 mb-3"><div class="stat-card"><div class="stat-icon bg-warning-soft"><i class="fas fa-receipt"></i></div><div class="stat-info"><h3>${{ number_format($stats->avg_order ?? 0, 2) }}</h3><p>Avg Order Value</p></div></div></div>
    <div class="col-sm-6 col-xl-3 mb-3"><div class="stat-card"><div class="stat-icon bg-danger-soft"><i class="fas fa-users"></i></div><div class="stat-info"><h3>{{ $stats->unique_customers ?? 0 }}</h3><p>Unique Customers</p></div></div></div>
</div>

<div class="admin-card"><div class="card-header"><h5>Daily Breakdown</h5></div><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Date</th><th>Orders</th><th>Revenue</th></tr></thead>
<tbody>
@forelse($dailyData as $d)
<tr><td>{{ $d->date }}</td><td>{{ $d->orders }}</td><td class="font-weight-bold">${{ number_format($d->revenue, 2) }}</td></tr>
@empty<tr><td colspan="3" class="text-center text-muted py-4">No data for this period.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
