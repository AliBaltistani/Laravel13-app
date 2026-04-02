@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Coupon' : 'Add Coupon')
@section('breadcrumb')<li><a href="{{ route('admin.coupons.index') }}">Coupons</a></li><li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>@endsection
@section('admin-content')
<div class="row"><div class="col-lg-8"><div class="admin-card"><div class="card-header"><h5>{{ $isEdit ? 'Edit' : 'New' }} Coupon</h5></div><div class="card-body">
<form method="POST" action="{{ $isEdit ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}">@csrf @if($isEdit) @method('PUT') @endif
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <div class="row">
        <div class="col-md-6"><div class="form-group"><label>Code <span class="text-danger">*</span></label>
            <div class="input-group"><input type="text" name="code" class="form-control text-uppercase" value="{{ old('code', $coupon->code) }}" required>
            <div class="input-group-append"><button type="button" class="btn btn-outline-secondary" onclick="this.form.code.value=Math.random().toString(36).substring(2,10).toUpperCase()">Generate</button></div></div></div></div>
        <div class="col-md-6"><div class="form-group"><label>Name</label><input type="text" name="name" class="form-control" value="{{ old('name', $coupon->name) }}"></div></div>
    </div>
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Type <span class="text-danger">*</span></label><select name="type" class="form-control" required>
            <option value="percent" {{ old('type',$coupon->type)==='percent'?'selected':'' }}>Percentage</option>
            <option value="fixed" {{ old('type',$coupon->type)==='fixed'?'selected':'' }}>Fixed Amount</option>
            <option value="free_shipping" {{ old('type',$coupon->type)==='free_shipping'?'selected':'' }}>Free Shipping</option>
        </select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Value <span class="text-danger">*</span></label><input type="number" step="0.01" name="value" class="form-control" value="{{ old('value', $coupon->value) }}" required></div></div>
        <div class="col-md-4"><div class="form-group"><label>Max Discount ($)</label><input type="number" step="0.01" name="max_discount" class="form-control" value="{{ old('max_discount', $coupon->max_discount) }}"></div></div>
    </div>
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Min Order ($)</label><input type="number" step="0.01" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $coupon->min_order_amount) }}"></div></div>
        <div class="col-md-4"><div class="form-group"><label>Total Usage Limit</label><input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}"></div></div>
        <div class="col-md-4"><div class="form-group"><label>Per User Limit</label><input type="number" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}"></div></div>
    </div>
    <div class="row">
        <div class="col-md-6"><div class="form-group"><label>Start Date</label><input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}"></div></div>
        <div class="col-md-6"><div class="form-group"><label>Expiry Date</label><input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}"></div></div>
    </div>
    <div class="custom-control custom-checkbox mb-3"><input type="hidden" name="is_active" value="0"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Create' }}</button>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
</form></div></div></div></div>
@endsection
