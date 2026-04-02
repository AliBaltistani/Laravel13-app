@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Blog Category' : 'Add Blog Category')
@section('breadcrumb')<li><a href="{{ route('admin.post-categories.index') }}">Blog Categories</a></li><li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>@endsection
@section('admin-content')
<div class="row"><div class="col-lg-6"><div class="admin-card"><div class="card-header"><h5>{{ $isEdit ? 'Edit' : 'New' }} Blog Category</h5></div><div class="card-body">
<form method="POST" action="{{ $isEdit ? route('admin.post-categories.update', $category) : route('admin.post-categories.store') }}">@csrf @if($isEdit) @method('PUT') @endif
    <div class="form-group"><label>Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required></div>
    <div class="form-group"><label>Slug</label><input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}"></div>
    <div class="form-group"><label>Parent</label><select name="parent_id" class="form-control"><option value="">None</option>@foreach($parents as $p)<option value="{{ $p->id }}" {{ old('parent_id',$category->parent_id)==$p->id?'selected':'' }}>{{ $p->name }}</option>@endforeach</select></div>
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea></div>
    <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}"></div>
    <div class="custom-control custom-checkbox mb-3"><input type="hidden" name="is_active" value="0"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Create' }}</button>
    <a href="{{ route('admin.post-categories.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
</form></div></div></div></div>
@endsection
