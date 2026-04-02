@extends('layouts.admin')
@section('title', 'Blog Posts')
@section('breadcrumb')<li class="active">Posts</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Blog Posts</h4>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Post</a>
</div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
<tbody>
@forelse($posts as $p)
<tr>
    <td><a href="{{ route('admin.posts.edit', $p) }}" class="text-dark font-weight-bold">{{ Str::limit($p->title, 50) }}</a></td>
    <td>{{ $p->category?->name ?? '—' }}</td>
    <td>{{ $p->author?->name ?? '—' }}</td>
    <td>{!! $p->is_published ? '<span class="badge badge-success">Published</span>' : '<span class="badge badge-warning">Draft</span>' !!}</td>
    <td>{{ $p->published_at?->format('M d, Y') ?? $p->created_at->format('M d, Y') }}</td>
    <td>
        <a href="{{ route('admin.posts.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
        <form method="POST" action="{{ route('admin.posts.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="6" class="text-center text-muted py-4">No posts.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $posts->links() }}</div>
@endsection
