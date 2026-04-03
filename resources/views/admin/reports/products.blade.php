@extends('layouts.admin')
@section('title', 'Product Report')
@section('breadcrumb')<li class="active">Product Report</li>@endsection
@section('admin-content')
<h4 class="mb-3">Top Selling Products</h4>
<div class="admin-card mb-3"><div class="card-body py-3">
<form method="GET" class="row align-items-end">
    <div class="col-md-3 mb-2"><label class="small font-weight-bold">From</label><input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}"></div>
    <div class="col-md-3 mb-2"><label class="small font-weight-bold">To</label><input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}"></div>
    <div class="col-md-2 mb-2"><button type="submit" class="btn btn-dark btn-sm btn-block">Apply</button></div>
</form></div></div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>#</th><th>Product</th><th>SKU</th><th>Units Sold</th><th>Revenue</th></tr></thead>
<tbody>
@forelse($topProducts as $i => $p)
<tr><td>{{ $i+1 }}</td><td><strong>{{ $p->name }}</strong></td><td class="text-muted">{{ $p->sku }}</td><td>{{ $p->units_sold }}</td><td class="font-weight-bold">@price($p->total_revenue)</td></tr>
@empty<tr><td colspan="5" class="text-center text-muted py-4">No sales data.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
