@extends('layouts.admin')
@section('title', 'Comments')
@section('breadcrumb')<li class="active">Comments</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Blog Comments</h4></div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Post</th><th>Author</th><th>Comment</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
<tbody>
@forelse($comments as $c)
<tr>
    <td>{{ Str::limit($c->post?->title, 30) }}</td>
    <td>{{ $c->user?->name ?? $c->author_name ?? 'Anonymous' }}</td>
    <td>{{ Str::limit($c->content, 60) }}</td>
    <td>{!! $c->is_approved ? '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-warning">Pending</span>' !!}</td>
    <td>{{ $c->created_at->format('M d, Y') }}</td>
    <td>
        <form method="POST" action="{{ route('admin.comments.toggle', $c) }}" class="d-inline">@csrf @method('PUT')<button class="btn btn-sm btn-outline-{{ $c->is_approved ? 'warning' : 'success' }}"><i class="fas fa-{{ $c->is_approved ? 'times' : 'check' }}"></i></button></form>
        <form method="POST" action="{{ route('admin.comments.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="6" class="text-center text-muted py-4">No comments.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $comments->links() }}</div>
@endsection
