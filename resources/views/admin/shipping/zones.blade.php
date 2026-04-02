@extends('layouts.admin')
@section('title', 'Shipping Zones')
@section('breadcrumb')<li class="active">Shipping</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Shipping Zones</h4>
    <a href="{{ route('admin.shipping-zones.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Zone</a>
</div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Zone Name</th><th>Methods</th><th>Actions</th></tr></thead>
<tbody>
@forelse($zones as $z)
<tr>
    <td><strong>{{ $z->name }}</strong></td>
    <td>{{ $z->methods_count }} method(s)</td>
    <td>
        <a href="{{ route('admin.shipping-zones.show', $z) }}" class="btn btn-sm btn-outline-info" title="Manage Methods"><i class="fas fa-truck"></i></a>
        <a href="{{ route('admin.shipping-zones.edit', $z) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
        <form method="POST" action="{{ route('admin.shipping-zones.destroy', $z) }}" class="d-inline" onsubmit="return confirm('Delete zone and all methods?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="3" class="text-center text-muted py-4">No shipping zones.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
