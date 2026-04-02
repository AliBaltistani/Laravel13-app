@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Flash Sale' : 'Add Flash Sale')
@section('breadcrumb')<li><a href="{{ route('admin.flash-sales.index') }}">Flash Sales</a></li><li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>@endsection
@section('admin-content')
<div class="row"><div class="col-lg-8"><div class="admin-card"><div class="card-header"><h5>{{ $isEdit ? 'Edit' : 'New' }} Flash Sale</h5></div><div class="card-body">
<form method="POST" action="{{ $isEdit ? route('admin.flash-sales.update', $flashSale) : route('admin.flash-sales.store') }}">@csrf @if($isEdit) @method('PUT') @endif
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <div class="row">
        <div class="col-md-8"><div class="form-group"><label>Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name', $flashSale->name) }}" required></div></div>
        <div class="col-md-4"><div class="form-group"><label>Label</label><input type="text" name="label" class="form-control" value="{{ old('label', $flashSale->label) }}" placeholder="e.g. SALE"></div></div>
    </div>
    <div class="row">
        <div class="col-md-6"><div class="form-group"><label>Start Date <span class="text-danger">*</span></label><input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', $flashSale->starts_at?->format('Y-m-d\TH:i')) }}" required></div></div>
        <div class="col-md-6"><div class="form-group"><label>End Date <span class="text-danger">*</span></label><input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $flashSale->expires_at?->format('Y-m-d\TH:i')) }}" required></div></div>
    </div>
    <div class="custom-control custom-checkbox mb-3"><input type="hidden" name="is_active" value="0"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $flashSale->is_active ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label></div>
    <hr>
    <h6>Sale Products</h6>
    <div id="saleProducts">
        @if($isEdit)
        @foreach($products as $i => $sp)
        <div class="row mb-2 sale-product-row">
            <div class="col-md-5"><input type="number" name="sale_products[{{ $i }}][product_id]" class="form-control form-control-sm" value="{{ $sp->product_id }}" placeholder="Product ID"></div>
            <div class="col-md-3"><input type="number" step="0.01" name="sale_products[{{ $i }}][sale_price]" class="form-control form-control-sm" value="{{ $sp->sale_price }}" placeholder="Sale Price"></div>
            <div class="col-md-3"><input type="number" name="sale_products[{{ $i }}][sale_quantity]" class="form-control form-control-sm" value="{{ $sp->sale_quantity }}" placeholder="Qty Limit"></div>
            <div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.sale-product-row').remove()"><i class="fas fa-times"></i></button></div>
        </div>
        @endforeach
        @endif
    </div>
    <button type="button" class="btn btn-sm btn-outline-secondary mb-3" onclick="addProductRow()"><i class="fas fa-plus mr-1"></i> Add Product</button>
    <hr>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Create' }}</button>
    <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
</form></div></div></div></div>
@endsection
@push('scripts')
<script>
let rowIdx = {{ $isEdit ? $products->count() : 0 }};
function addProductRow() {
    document.getElementById('saleProducts').insertAdjacentHTML('beforeend',
        `<div class="row mb-2 sale-product-row"><div class="col-md-5"><input type="number" name="sale_products[${rowIdx}][product_id]" class="form-control form-control-sm" placeholder="Product ID"></div><div class="col-md-3"><input type="number" step="0.01" name="sale_products[${rowIdx}][sale_price]" class="form-control form-control-sm" placeholder="Sale Price"></div><div class="col-md-3"><input type="number" name="sale_products[${rowIdx}][sale_quantity]" class="form-control form-control-sm" placeholder="Qty Limit"></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.sale-product-row').remove()"><i class="fas fa-times"></i></button></div></div>`);
    rowIdx++;
}
</script>
@endpush
