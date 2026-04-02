@extends('layouts.admin')
@section('title', 'Reviews')
@section('breadcrumb')<li class="active">Reviews</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Reviews</h4></div>
<div class="admin-card mb-3"><div class="card-body py-3">
<form method="GET" class="row align-items-end">
    <div class="col-md-3 mb-2"><label class="small font-weight-bold">Status</label><select name="status" class="form-control form-control-sm"><option value="">All</option><option value="approved" {{ request('status')==='approved'?'selected':'' }}>Approved</option><option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option></select></div>
    <div class="col-md-2 mb-2"><label class="small font-weight-bold">Rating</label><select name="rating" class="form-control form-control-sm"><option value="">All</option>@for($i=5;$i>=1;$i--)<option value="{{ $i }}" {{ request('rating')==$i?'selected':'' }}>{{ $i }} ★</option>@endfor</select></div>
    <div class="col-md-1 mb-2"><button type="submit" class="btn btn-dark btn-sm btn-block">Filter</button></div>
</form></div></div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Product</th><th>Customer</th><th>Rating</th><th>Title</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
<tbody>
@forelse($reviews as $r)
<tr>
    <td>{{ Str::limit($r->product?->name, 30) }}</td>
    <td>{{ $r->user?->name ?? 'Guest' }}</td>
    <td><span class="text-warning">@for($i=1;$i<=5;$i++)<i class="fas fa-star{{ $i<=$r->rating?'':'-half-alt' }}"></i>@endfor</span> {{ $r->rating }}/5</td>
    <td>{{ Str::limit($r->title, 30) }}</td>
    <td>{!! $r->is_approved ? '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-warning">Pending</span>' !!}</td>
    <td>{{ $r->created_at->format('M d, Y') }}</td>
    <td>
        <form method="POST" action="{{ route('admin.reviews.toggle', $r) }}" class="d-inline">@csrf @method('PUT')<button class="btn btn-sm btn-outline-{{ $r->is_approved ? 'warning' : 'success' }}" title="{{ $r->is_approved ? 'Reject' : 'Approve' }}"><i class="fas fa-{{ $r->is_approved ? 'times' : 'check' }}"></i></button></form>
        <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">No reviews.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $reviews->links() }}</div>
@endsection
