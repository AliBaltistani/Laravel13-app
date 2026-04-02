@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Slider' : 'Add Slider')
@section('breadcrumb')<li><a href="{{ route('admin.sliders.index') }}">Sliders</a></li><li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>@endsection
@section('admin-content')
<div class="row"><div class="col-lg-6"><div class="admin-card"><div class="card-header"><h5>{{ $isEdit ? 'Edit' : 'New' }} Slider</h5></div><div class="card-body">
<form method="POST" action="{{ $isEdit ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}">@csrf @if($isEdit) @method('PUT') @endif
    <div class="form-group"><label>Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name', $slider->name) }}" required></div>
    <div class="form-group"><label>Position <span class="text-danger">*</span></label><select name="position" class="form-control" required><option value="homepage" {{ old('position',$slider->position)==='homepage'?'selected':'' }}>Homepage</option><option value="category" {{ old('position',$slider->position)==='category'?'selected':'' }}>Category</option></select></div>
    <div class="custom-control custom-checkbox mb-3"><input type="hidden" name="is_active" value="0"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $slider->is_active ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Create' }}</button>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
</form></div></div></div></div>
@endsection
