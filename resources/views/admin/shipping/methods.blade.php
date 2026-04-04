@extends('layouts.admin')
@section('title', 'Shipping Methods - ' . $shippingZone->name)
@section('breadcrumb')
<li><a href="{{ route('admin.shipping-zones.index') }}">Shipping</a></li>
<li class="active">{{ $shippingZone->name }} — Methods</li>
@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $shippingZone->name }} — Shipping Methods</h4>
    <div>
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addMethodModal">
            <i class="fas fa-plus mr-1"></i> Add Method
        </button>
        <a href="{{ route('admin.shipping-zones.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>
</div>

@if($shippingZone->countries)
<div class="alert alert-info py-2">
    <i class="fas fa-globe mr-1"></i>
    <strong>Countries:</strong>
    @php
        $countries = is_array($shippingZone->countries) ? $shippingZone->countries : json_decode($shippingZone->countries, true) ?? [];
    @endphp
    {{ implode(', ', $countries) }}
</div>
@endif

<div class="admin-card">
    <div class="card-body p-0">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Method Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Min Order</th>
                    <th>Est. Days</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($methods as $method)
                <tr>
                    <td><strong>{{ $method->name }}</strong></td>
                    <td><span class="badge badge-{{ $method->type === 'free' ? 'success' : ($method->type === 'flat_rate' ? 'primary' : 'info') }}">{{ ucfirst(str_replace('_', ' ', $method->type)) }}</span></td>
                    <td>
                        @if($method->type === 'free')
                            <span class="text-success font-weight-bold">Free</span>
                        @else
                            @price($method->price)
                        @endif
                    </td>
                    <td>{{ $method->min_order_amount ? '$' . number_format($method->min_order_amount, 2) : '—' }}</td>
                    <td>{{ $method->estimated_days ? $method->estimated_days . ' day(s)' : '—' }}</td>
                    <td>
                        @if($method->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $method->sort_order }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary edit-method-btn"
                            data-id="{{ $method->id }}"
                            data-name="{{ $method->name }}"
                            data-type="{{ $method->type }}"
                            data-price="{{ $method->price }}"
                            data-min-order="{{ $method->min_order_amount }}"
                            data-max-order="{{ $method->max_order_amount }}"
                            data-estimated-days="{{ $method->estimated_days }}"
                            data-is-active="{{ $method->is_active }}"
                            data-sort-order="{{ $method->sort_order }}"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.shipping-methods.destroy', $method) }}" class="d-inline" onsubmit="return confirm('Delete this method?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-truck fa-2x mb-2 d-block"></i>
                        No shipping methods in this zone. Add one above.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Add Method Modal --}}
<div class="modal fade" id="addMethodModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.shipping-methods.store') }}">
            @csrf
            <input type="hidden" name="shipping_zone_id" value="{{ $shippingZone->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Shipping Method</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Standard Shipping">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" class="form-control">
                                    <option value="flat_rate">Flat Rate</option>
                                    <option value="free">Free Shipping</option>
                                    <option value="weight_based">Weight Based</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Price ($)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Min Order Amount</label>
                                <input type="number" step="0.01" name="min_order_amount" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Est. Delivery Days</label>
                                <input type="number" name="estimated_days" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="custom-control-input" id="is_active_add" name="is_active" value="1" checked>
                                <label class="custom-control-label" for="is_active_add">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Method</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.edit-method-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        // For simplicity, redirect to an inline edit — could be a modal too
        var id = this.dataset.id;
        var name = prompt('Method Name:', this.dataset.name);
        if (name === null) return;
        var price = prompt('Price ($):', this.dataset.price);
        if (price === null) return;

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/shipping-methods/' + id;
        form.innerHTML = '@csrf @method("PUT")' +
            '<input type="hidden" name="name" value="' + name + '">' +
            '<input type="hidden" name="price" value="' + price + '">' +
            '<input type="hidden" name="type" value="' + this.dataset.type + '">' +
            '<input type="hidden" name="is_active" value="' + this.dataset.isActive + '">' +
            '<input type="hidden" name="sort_order" value="' + this.dataset.sortOrder + '">' +
            '<input type="hidden" name="shipping_zone_id" value="{{ $shippingZone->id }}">';
        document.body.appendChild(form);
        form.submit();
    });
});
</script>
@endpush
