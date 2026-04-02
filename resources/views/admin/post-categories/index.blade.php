@extends('layouts.admin')
@section('title', 'Blog Categories')
@section('breadcrumb')<li class="active">Blog Categories</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Blog Categories</h4>
    <a href="{{ route('admin.post-categories.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Category</a>
</div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Name</th><th>Slug</th><th>Posts</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($categories as $c)
<tr>
    <td><strong>{{ $c->name }}</strong></td><td class="text-muted">/{{ $c->slug }}</td><td>{{ $c->posts_count }}</td>
    <td>{!! $c->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>' !!}</td>
    <td>
        <a href="{{ route('admin.post-categories.edit', $c) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
        <form method="POST" action="{{ route('admin.post-categories.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="5" class="text-center text-muted py-4">No blog categories.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
