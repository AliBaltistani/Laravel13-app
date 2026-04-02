@extends('layouts.admin')
@section('title', 'Flash Sales')
@section('breadcrumb')<li class="active">Flash Sales</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Flash Sales</h4>
    <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Flash Sale</a>
</div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Name</th><th>Products</th><th>Start</th><th>End</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($flashSales as $fs)
<tr>
    <td><strong>{{ $fs->name }}</strong>@if($fs->label)<br><small class="text-muted">{{ $fs->label }}</small>@endif</td>
    <td>{{ $fs->products_count }}</td>
    <td>{{ $fs->starts_at->format('M d, Y H:i') }}</td>
    <td>{{ $fs->expires_at->format('M d, Y H:i') }}</td>
    <td>
        @if(!$fs->is_active)<span class="badge badge-secondary">Inactive</span>
        @elseif($fs->expires_at->isPast())<span class="badge badge-danger">Ended</span>
        @elseif($fs->starts_at->isFuture())<span class="badge badge-info">Scheduled</span>
        @else<span class="badge badge-success">Live</span>@endif
    </td>
    <td>
        <a href="{{ route('admin.flash-sales.edit', $fs) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
        <form method="POST" action="{{ route('admin.flash-sales.destroy', $fs) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="6" class="text-center text-muted py-4">No flash sales.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $flashSales->links() }}</div>
@endsection
