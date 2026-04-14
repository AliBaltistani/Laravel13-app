@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Coupon' : 'Create Coupon')
@section('breadcrumb')<li><a href="{{ route('admin.coupons.index') }}">Coupons</a></li><li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>@endsection

@section('admin-content')
<form method="POST" action="{{ $isEdit ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}">
    @csrf @if($isEdit) @method('PUT') @endif

    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    <div class="row">
        <div class="col-lg-8">
            <div class="admin-card mb-3">
                <div class="card-header"><h5>Coupon Details</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Coupon Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required id="couponCode" style="text-transform:uppercase;">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('couponCode').value=generateCode()"><i class="fas fa-dice mr-1"></i> Generate</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"><label>Name</label><input type="text" name="name" class="form-control" value="{{ old('name', $coupon->name) }}" placeholder="e.g. Summer Sale 2024"></div>
                        </div>
                    </div>
                    <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="2" placeholder="Internal description...">{{ old('description', $coupon->description) }}</textarea></div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label>Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control">
                                    <option value="percent" {{ old('type',$coupon->type)==='percent'?'selected':'' }}>Percentage Discount</option>
                                    <option value="fixed" {{ old('type',$coupon->type)==='fixed'?'selected':'' }}>Fixed Amount</option>
                                    <option value="free_shipping" {{ old('type',$coupon->type)==='free_shipping'?'selected':'' }}>Free Shipping</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Value <span class="text-danger">*</span></label><input type="number" name="value" class="form-control" value="{{ old('value', $coupon->value) }}" step="0.01" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Max Discount</label><input type="number" name="max_discount" class="form-control" value="{{ old('max_discount', $coupon->max_discount) }}" step="0.01" placeholder="No limit"><small class="text-muted">For percentage coupons</small></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>Min Order Amount</label><input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" step="0.01" placeholder="No minimum"></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Usage Limit (Total)</label><input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Unlimited"></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Limit Per User</label><input type="number" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}" placeholder="Unlimited"></div></div>
                    </div>
                </div>
            </div>

            <div class="admin-card mb-3">
                <div class="card-header"><h5>Schedule & Restrictions</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Start Date</label><input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Expiry Date</label><input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}"></div></div>
                    </div>

                    <div class="form-group"><label>Applies To</label>
                        <select name="applies_to" class="form-control">
                            <option value="all" {{ old('applies_to',$coupon->applies_to ?? 'all')==='all'?'selected':'' }}>All Products</option>
                            <option value="specific_products" {{ old('applies_to',$coupon->applies_to)==='specific_products'?'selected':'' }}>Specific Products</option>
                            <option value="specific_categories" {{ old('applies_to',$coupon->applies_to)==='specific_categories'?'selected':'' }}>Specific Categories</option>
                        </select>
                    </div>

                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="exclude_sale_items" value="0">
                        <input type="checkbox" class="custom-control-input" id="exclude_sale" name="exclude_sale_items" value="1" {{ old('exclude_sale_items', $coupon->exclude_sale_items) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="exclude_sale">Exclude Sale Items</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-3">
                <div class="card-body">
                    <div class="custom-control custom-switch mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Create' }} Coupon</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-block mt-2">Cancel</a>
                </div>
            </div>

            @if($isEdit)
            <div class="admin-card">
                <div class="card-body">
                    <p class="text-muted mb-1" style="font-size:13px;"><strong>Used:</strong> {{ $coupon->used_count }} times</p>
                    <p class="text-muted mb-1" style="font-size:13px;"><strong>Status:</strong> {{ ucfirst($coupon->status) }}</p>
                    <p class="text-muted mb-0" style="font-size:13px;"><strong>Created:</strong> {{ $coupon->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>

@push('scripts')
<script>
function generateCode() {
    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var code = '';
    for (var i = 0; i < 8; i++) code += chars.charAt(Math.floor(Math.random() * chars.length));
    return code;
}
</script>
@endpush
@endsection
