@extends('layouts.admin')
@section('title', 'Categories')
@section('breadcrumb')<li class="active">Categories</li>@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Categories ({{ $categories->count() }})</h4>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Category</a>
</div>
<div class="admin-card">
    <div class="card-body p-0">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Parent</th><th>Products</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>
                        @if($cat->parent_id) <span class="text-muted mr-1">└</span> @endif
                        <strong>{{ $cat->name }}</strong>
                        @if($cat->is_featured)<span class="badge badge-warning ml-1">Featured</span>@endif
                    </td>
                    <td>{{ $cat->parent?->name ?? '—' }}</td>
                    <td>{{ $cat->products_count }}</td>
                    <td>{!! $cat->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>' !!}</td>
                    <td>{{ $cat->sort_order }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No categories.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
