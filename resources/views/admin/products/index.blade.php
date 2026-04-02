@extends('layouts.admin')

@section('title', 'Products')
@section('breadcrumb')
<li class="active">Products</li>
@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Products ({{ $products->total() }})</h4>
    <div>
        <a href="{{ route('admin.products.export') }}" class="btn btn-outline-secondary btn-sm mr-1">
            <i class="fas fa-download mr-1"></i> Export CSV
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Product
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="admin-card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row align-items-end gx-2">
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or SKU..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">Category</label>
                <select name="category_id" class="form-control form-control-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">Brand</label>
                <select name="brand_id" class="form-control form-control-sm">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">Status</label>
                <select name="status" class="form-control form-control-sm">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold">Stock</label>
                <select name="stock" class="form-control form-control-sm">
                    <option value="">All</option>
                    <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-1 mb-2">
                <button type="submit" class="btn btn-dark btn-sm btn-block">Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Bulk Actions --}}
<form method="POST" action="{{ route('admin.products.bulk') }}" id="bulkForm">
    @csrf
    <div class="admin-card">
        <div class="card-header py-2">
            <div class="d-flex align-items-center">
                <select name="action" class="form-control form-control-sm mr-2" style="width:160px;">
                    <option value="">Bulk Actions</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-dark" onclick="return confirm('Apply bulk action?')">Apply</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th width="30"><input type="checkbox" id="selectAll"></th>
                            <th width="60">Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $product->id }}" class="row-check"></td>
                            <td>
                                @if($product->mainImage)
                                <img src="{{ Storage::url($product->mainImage->image_path) }}" alt="" width="40" height="40" style="object-fit:cover;border-radius:4px;">
                                @else
                                <div style="width:40px;height:40px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-dark font-weight-bold">{{ Str::limit($product->name, 40) }}</a>
                                @if($product->is_featured)<span class="badge badge-warning ml-1">Featured</span>@endif
                                @if($product->is_new)<span class="badge badge-info ml-1">New</span>@endif
                            </td>
                            <td class="text-muted">{{ $product->sku ?? '—' }}</td>
                            <td>{{ $product->category?->name ?? '—' }}</td>
                            <td class="font-weight-bold">
                                ${{ number_format($product->price, 2) }}
                                @if($product->compare_price)
                                <br><small class="text-muted text-decoration-line-through">${{ number_format($product->compare_price, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                @if(!$product->manage_stock)
                                <span class="text-muted">—</span>
                                @elseif($product->stock_quantity <= 0)
                                <span class="text-danger font-weight-bold">{{ $product->stock_quantity }}</span>
                                @elseif($product->stock_quantity <= $product->low_stock_threshold)
                                <span class="text-warning font-weight-bold">{{ $product->stock_quantity }}</span>
                                @else
                                <span class="text-success">{{ $product->stock_quantity }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary mr-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                No products found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

<div class="mt-3">{{ $products->links() }}</div>
@endsection

@push('scripts')
<script>
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
});
</script>
@endpush
