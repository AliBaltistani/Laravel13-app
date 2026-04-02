@extends('layouts.admin')
@section('title', 'Newsletter')
@section('breadcrumb')<li class="active">Newsletter</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Newsletter Subscribers</h4>
    <div>
        <a href="{{ route('admin.newsletter.export') }}" class="btn btn-outline-secondary btn-sm mr-1"><i class="fas fa-download mr-1"></i> Export CSV</a>
        <a href="{{ route('admin.newsletter.broadcast') }}" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane mr-1"></i> Broadcast</a>
    </div>
</div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Email</th><th>Name</th><th>Status</th><th>Subscribed</th><th>Actions</th></tr></thead>
<tbody>
@forelse($subscribers as $s)
<tr>
    <td>{{ $s->email }}</td><td>{{ $s->name ?? '—' }}</td>
    <td>{!! $s->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Unsubscribed</span>' !!}</td>
    <td>{{ $s->created_at->format('M d, Y') }}</td>
    <td><form method="POST" action="{{ route('admin.newsletter.destroy', $s) }}" class="d-inline" onsubmit="return confirm('Remove?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form></td>
</tr>
@empty<tr><td colspan="5" class="text-center text-muted py-4">No subscribers.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $subscribers->links() }}</div>
@endsection
