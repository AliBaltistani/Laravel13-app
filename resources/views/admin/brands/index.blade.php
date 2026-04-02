@extends('layouts.admin')
@section('title', 'Brands')
@section('breadcrumb')<li class="active">Brands</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Brands</h4>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Brand</a>
</div>
<div class="admin-card">
    <div class="card-body p-0">
        <table class="admin-table">
            <thead><tr><th>Logo</th><th>Name</th><th>Products</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($brands as $b)
                <tr>
                    <td>@if($b->logo)<img src="{{ Storage::url($b->logo) }}" style="max-height:30px;">@else <span class="text-muted">—</span> @endif</td>
                    <td><strong>{{ $b->name }}</strong>@if($b->is_featured)<span class="badge badge-warning ml-1">Featured</span>@endif</td>
                    <td>{{ $b->products_count }}</td>
                    <td>{!! $b->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>' !!}</td>
                    <td>
                        <a href="{{ route('admin.brands.edit', $b) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.brands.destroy', $b) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </td>
                </tr>
                @empty<tr><td colspan="5" class="text-center text-muted py-4">No brands.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $brands->links() }}</div>
@endsection
