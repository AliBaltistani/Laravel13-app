@extends('layouts.admin')
@section('title', 'Pages')
@section('breadcrumb')<li class="active">Pages</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">CMS Pages</h4>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Page</a>
</div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Title</th><th>Slug</th><th>Template</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($pages as $p)
<tr>
    <td><a href="{{ route('admin.pages.edit', $p) }}" class="text-dark font-weight-bold">{{ $p->title }}</a></td>
    <td class="text-muted">/{{ $p->slug }}</td>
    <td>{{ ucfirst($p->template ?? 'default') }}</td>
    <td>{!! $p->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>' !!}</td>
    <td>
        <a href="{{ route('admin.pages.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
        <form method="POST" action="{{ route('admin.pages.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="5" class="text-center text-muted py-4">No pages.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $pages->links() }}</div>
@endsection
