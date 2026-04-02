@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Brand' : 'Add Brand')
@section('breadcrumb')<li><a href="{{ route('admin.brands.index') }}">Brands</a></li><li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>@endsection
@section('admin-content')
<div class="row"><div class="col-lg-8">
<div class="admin-card"><div class="card-header"><h5>{{ $isEdit ? 'Edit Brand' : 'New Brand' }}</h5></div><div class="card-body">
<form method="POST" action="{{ $isEdit ? route('admin.brands.update', $brand) : route('admin.brands.store') }}" enctype="multipart/form-data">
    @csrf @if($isEdit) @method('PUT') @endif
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <div class="row">
        <div class="col-md-6"><div class="form-group"><label>Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name', $brand->name) }}" required></div></div>
        <div class="col-md-6"><div class="form-group"><label>Slug</label><input type="text" name="slug" class="form-control" value="{{ old('slug', $brand->slug) }}"></div></div>
    </div>
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', $brand->description) }}</textarea></div>
    <div class="row">
        <div class="col-md-6"><div class="form-group"><label>Website URL</label><input type="url" name="website" class="form-control" value="{{ old('website', $brand->website) }}"></div></div>
        <div class="col-md-3"><div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $brand->sort_order ?? 0) }}"></div></div>
        <div class="col-md-3"><div class="form-group"><label>Logo</label><input type="file" name="logo" class="form-control-file" accept="image/*">
            @if($brand->logo)<img src="{{ Storage::url($brand->logo) }}" class="mt-1" style="max-height:40px;">@endif</div></div>
    </div>
    <div class="d-flex">
        <div class="custom-control custom-checkbox mr-4"><input type="hidden" name="is_active" value="0"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $brand->is_active ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label></div>
        <div class="custom-control custom-checkbox"><input type="hidden" name="is_featured" value="0"><input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}><label class="custom-control-label" for="is_featured">Featured</label></div>
    </div>
    <hr><button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Create' }}</button>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
</form>
</div></div></div></div>
@endsection
