@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Banner' : 'Add Banner')
@section('breadcrumb')<li><a href="{{ route('admin.banners.index') }}">Banners</a></li><li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>@endsection
@section('admin-content')
<div class="row"><div class="col-lg-8"><div class="admin-card"><div class="card-header"><h5>{{ $isEdit ? 'Edit' : 'New' }} Banner</h5></div><div class="card-body">
<form method="POST" action="{{ $isEdit ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" enctype="multipart/form-data">@csrf @if($isEdit) @method('PUT') @endif
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <div class="row">
        <div class="col-md-6"><div class="form-group"><label>Title</label><input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}"></div></div>
        <div class="col-md-6"><div class="form-group"><label>Subtitle</label><input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $banner->subtitle) }}"></div></div>
    </div>
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Button Text</label><input type="text" name="button_text" class="form-control" value="{{ old('button_text', $banner->button_text) }}"></div></div>
        <div class="col-md-4"><div class="form-group"><label>Button URL</label><input type="text" name="button_url" class="form-control" value="{{ old('button_url', $banner->button_url) }}"></div></div>
        <div class="col-md-4"><div class="form-group"><label>Position <span class="text-danger">*</span></label>
            <select name="position" class="form-control" required><option value="homepage_top" {{ old('position',$banner->position)==='homepage_top'?'selected':'' }}>Homepage Top</option><option value="homepage_middle" {{ old('position',$banner->position)==='homepage_middle'?'selected':'' }}>Homepage Middle</option><option value="homepage_bottom" {{ old('position',$banner->position)==='homepage_bottom'?'selected':'' }}>Homepage Bottom</option><option value="sidebar" {{ old('position',$banner->position)==='sidebar'?'selected':'' }}>Sidebar</option><option value="category_top" {{ old('position',$banner->position)==='category_top'?'selected':'' }}>Category Top</option></select>
        </div></div>
    </div>
    <div class="row">
        <div class="col-md-3"><div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order ?? 0) }}"></div></div>
        <div class="col-md-4"><div class="form-group"><label>Start Date</label><input type="date" name="starts_at" class="form-control" value="{{ old('starts_at', $banner->starts_at?->format('Y-m-d')) }}"></div></div>
        <div class="col-md-4"><div class="form-group"><label>End Date</label><input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $banner->expires_at?->format('Y-m-d')) }}"></div></div>
    </div>
    <div class="form-group"><label>Image</label><input type="file" name="image" class="form-control-file" accept="image/*">
        @if($banner->image)<img src="{{ Storage::url($banner->image) }}" class="mt-2" style="max-height:80px;">@endif</div>
    <div class="custom-control custom-checkbox mb-3"><input type="hidden" name="is_active" value="0"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Create' }}</button>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
</form></div></div></div></div>
@endsection
