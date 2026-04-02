@extends('layouts.admin')
@section('title', 'Inventory Report')
@section('breadcrumb')<li class="active">Inventory</li>@endsection
@section('admin-content')
<h4 class="mb-3">Inventory Report</h4>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Product</th><th>SKU</th><th>Stock</th><th>Threshold</th><th>Status</th></tr></thead>
<tbody>
@forelse($products as $p)
<tr>
    <td><a href="{{ route('admin.products.edit', $p) }}" class="text-dark font-weight-bold">{{ Str::limit($p->name, 40) }}</a></td>
    <td class="text-muted">{{ $p->sku }}</td>
    <td class="font-weight-bold {{ $p->stock_quantity <= 0 ? 'text-danger' : ($p->stock_quantity <= $p->low_stock_threshold ? 'text-warning' : 'text-success') }}">{{ $p->stock_quantity }}</td>
    <td>{{ $p->low_stock_threshold }}</td>
    <td>
        @if($p->stock_quantity <= 0)<span class="badge badge-danger">Out of Stock</span>
        @elseif($p->stock_quantity <= $p->low_stock_threshold)<span class="badge badge-warning">Low Stock</span>
        @else<span class="badge badge-success">In Stock</span>@endif
    </td>
</tr>
@empty<tr><td colspan="5" class="text-center text-muted py-4">No tracked products.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
