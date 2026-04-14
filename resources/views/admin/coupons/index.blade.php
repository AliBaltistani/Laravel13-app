@extends('layouts.admin')
@section('title', 'Coupons')
@section('breadcrumb')<li class="active">Coupons</li>@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Coupon Management</h4>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Create Coupon</a>
</div>

{{-- Stats --}}
<div class="row mb-3">
    <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-icon bg-primary-soft"><i class="fas fa-ticket-alt"></i></div><div class="stat-info"><h3>{{ $stats['total'] }}</h3><p>Total Coupons</p></div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-icon bg-success-soft"><i class="fas fa-check-circle"></i></div><div class="stat-info"><h3>{{ $stats['active'] }}</h3><p>Active</p></div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-icon bg-danger-soft"><i class="fas fa-clock"></i></div><div class="stat-info"><h3>{{ $stats['expired'] }}</h3><p>Expired</p></div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-icon bg-warning-soft"><i class="fas fa-chart-bar"></i></div><div class="stat-info"><h3>{{ $stats['total_usage'] }}</h3><p>Total Uses</p></div></div></div>
</div>

{{-- Search & Filter --}}
<div class="admin-card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex flex-wrap align-items-center" style="gap:10px;">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search code or name..." value="{{ request('search') }}" style="max-width:200px;">
            <select name="status" class="form-control form-control-sm" style="max-width:140px;">
                <option value="">All Status</option>
                <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                <option value="expired" {{ request('status')==='expired'?'selected':'' }}>Expired</option>
                <option value="scheduled" {{ request('status')==='scheduled'?'selected':'' }}>Scheduled</option>
                <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
            </select>
            <select name="type" class="form-control form-control-sm" style="max-width:140px;">
                <option value="">All Types</option>
                <option value="percent" {{ request('type')==='percent'?'selected':'' }}>Percentage</option>
                <option value="fixed" {{ request('type')==='fixed'?'selected':'' }}>Fixed Amount</option>
                <option value="free_shipping" {{ request('type')==='free_shipping'?'selected':'' }}>Free Shipping</option>
            </select>
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
            @if(request()->hasAny(['search','status','type']))
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Bulk Actions --}}
<form method="POST" action="{{ route('admin.coupons.bulk') }}" id="bulkForm">
    @csrf
    <div class="d-flex align-items-center mb-2" style="gap:8px;">
        <select name="action" class="form-control form-control-sm" style="max-width:160px;">
            <option value="">Bulk Actions</option>
            <option value="activate">Activate Selected</option>
            <option value="deactivate">Deactivate Selected</option>
            <option value="delete">Delete Selected</option>
        </select>
        <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Apply action to selected coupons?')">Apply</button>
    </div>

    <div class="admin-card"><div class="card-body p-0"><table class="admin-table">
    <thead><tr>
        <th><input type="checkbox" id="selectAll" onchange="document.querySelectorAll('.coupon-check').forEach(c=>c.checked=this.checked)"></th>
        <th>Code</th><th>Name</th><th>Type</th><th>Value</th><th>Usage</th><th>Status</th><th>Expires</th><th>Actions</th>
    </tr></thead>
    <tbody>
    @forelse($coupons as $coupon)
    <tr>
        <td><input type="checkbox" name="ids[]" value="{{ $coupon->id }}" class="coupon-check"></td>
        <td>
            <code class="font-weight-bold" style="font-size:14px;cursor:pointer;" onclick="navigator.clipboard.writeText('{{ $coupon->code }}');this.title='Copied!'" title="Click to copy">{{ $coupon->code }}</code>
        </td>
        <td>{{ $coupon->name ?: '-' }}</td>
        <td>
            @switch($coupon->type)
                @case('percent') <span class="badge badge-info">{{ $coupon->value }}% OFF</span> @break
                @case('fixed') <span class="badge badge-success">${{ number_format($coupon->value,2) }} OFF</span> @break
                @case('free_shipping') <span class="badge badge-warning">Free Shipping</span> @break
            @endswitch
        </td>
        <td>
            <span class="{{ $coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit ? 'text-danger' : '' }}">
                {{ $coupon->used_count }}{{ $coupon->usage_limit ? '/' . $coupon->usage_limit : '' }}
            </span>
        </td>
        <td>
            @switch($coupon->status)
                @case('active') <span class="badge badge-success">Active</span> @break
                @case('expired') <span class="badge badge-danger">Expired</span> @break
                @case('scheduled') <span class="badge badge-info">Scheduled</span> @break
                @case('exhausted') <span class="badge badge-warning">Exhausted</span> @break
                @case('inactive') <span class="badge badge-secondary">Inactive</span> @break
            @endswitch
        </td>
        <td class="text-muted" style="font-size:12px;">{{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}</td>
        <td style="white-space:nowrap;">
            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.coupons.duplicate', $coupon) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-secondary" title="Duplicate"><i class="fas fa-copy"></i></button></form>
            <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}" class="d-inline">@csrf @method('PUT')<button class="btn btn-sm btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }}" title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}"><i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i></button></form>
            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
        </td>
    </tr>
    @empty<tr><td colspan="9" class="text-center text-muted py-4">No coupons found.</td></tr>@endforelse
    </tbody></table></div></div>
</form>
<div class="mt-3">{{ $coupons->links() }}</div>
@endsection
