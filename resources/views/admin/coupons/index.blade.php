@extends('layouts.admin')
@section('title', 'Coupons')
@section('breadcrumb')<li class="active">Coupons</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Coupons</h4>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Coupon</a>
</div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Code</th><th>Name</th><th>Type</th><th>Value</th><th>Usage</th><th>Period</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($coupons as $c)
<tr>
    <td><code class="font-weight-bold">{{ $c->code }}</code></td>
    <td>{{ $c->name ?? '—' }}</td>
    <td>{{ ucfirst($c->type) }}</td>
    <td>{{ $c->type === 'percent' ? $c->value.'%' : '$'.number_format($c->value,2) }}</td>
    <td>{{ $c->used_count ?? 0 }}{{ $c->usage_limit ? '/'.$c->usage_limit : '' }}</td>
    <td><small>{{ $c->starts_at ? $c->starts_at->format('M d') : '—' }} → {{ $c->expires_at ? $c->expires_at->format('M d') : '∞' }}</small></td>
    <td>
        @if(!$c->is_active)<span class="badge badge-secondary">Inactive</span>
        @elseif($c->expires_at && $c->expires_at->isPast())<span class="badge badge-danger">Expired</span>
        @elseif($c->starts_at && $c->starts_at->isFuture())<span class="badge badge-info">Scheduled</span>
        @else<span class="badge badge-success">Active</span>@endif
    </td>
    <td>
        <a href="{{ route('admin.coupons.edit', $c) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
        <form method="POST" action="{{ route('admin.coupons.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="8" class="text-center text-muted py-4">No coupons.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $coupons->links() }}</div>
@endsection
