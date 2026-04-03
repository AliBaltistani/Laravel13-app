@extends('layouts.admin')
@section('title', 'Customers')
@section('breadcrumb')<li class="active">Customers</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Customers</h4></div>
<div class="admin-card mb-3"><div class="card-body py-3">
<form method="GET" class="row align-items-end">
    <div class="col-md-4 mb-2"><label class="small font-weight-bold">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Name or email..." value="{{ request('search') }}"></div>
    <div class="col-md-3 mb-2"><label class="small font-weight-bold">Status</label><select name="status" class="form-control form-control-sm"><option value="">All</option><option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option><option value="banned" {{ request('status')==='banned'?'selected':'' }}>Banned</option></select></div>
    <div class="col-md-2 mb-2"><button type="submit" class="btn btn-dark btn-sm btn-block">Filter</button></div>
</form></div></div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Name</th><th>Email</th><th>Orders</th><th>Total Spent</th><th>Joined</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($customers as $c)
<tr>
    <td><a href="{{ route('admin.customers.show', $c) }}" class="font-weight-bold text-dark">{{ $c->name }}</a></td>
    <td>{{ $c->email }}</td>
    <td>{{ $c->orders_count }}</td>
    <td class="font-weight-bold">@price($c->orders_sum_total ?? 0)</td>
    <td>{{ $c->created_at->format('M d, Y') }}</td>
    <td>{!! $c->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Banned</span>' !!}</td>
    <td>
        <a href="{{ route('admin.customers.show', $c) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
        <form method="POST" action="{{ route('admin.customers.toggle', $c) }}" class="d-inline">@csrf @method('PUT')
            <button class="btn btn-sm btn-outline-{{ $c->is_active ? 'warning' : 'success' }}" title="{{ $c->is_active ? 'Ban' : 'Activate' }}"><i class="fas fa-{{ $c->is_active ? 'ban' : 'check' }}"></i></button>
        </form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">No customers.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $customers->links() }}</div>
@endsection
