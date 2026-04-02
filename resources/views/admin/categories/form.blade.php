@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Category' : 'Add Category')
@section('breadcrumb')
<li><a href="{{ route('admin.categories.index') }}">Categories</a></li>
<li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header"><h5>{{ $isEdit ? 'Edit Category' : 'New Category' }}</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}" enctype="multipart/form-data">
                    @csrf @if($isEdit) @method('PUT') @endif
                    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label>Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"><label>Slug</label><input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}" placeholder="Auto-generated"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label>Parent Category</label>
                                <select name="parent_id" class="form-control"><option value="">None (Root)</option>
                                @foreach($parents as $p)<option value="{{ $p->id }}" {{ old('parent_id', $category->parent_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label>Icon CSS Class</label><input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon) }}" placeholder="fas fa-..."></div>
                        </div>
                    </div>
                    <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea></div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Category Image</label><input type="file" name="image" class="form-control-file" accept="image/*">
                            @if($category->image)<img src="{{ Storage::url($category->image) }}" class="mt-1" style="max-height:60px;">@endif</div></div>
                        <div class="col-md-6"><div class="form-group"><label>Banner Image</label><input type="file" name="banner_image" class="form-control-file" accept="image/*">
                            @if($category->banner_image)<img src="{{ Storage::url($category->banner_image) }}" class="mt-1" style="max-height:60px;">@endif</div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $category->meta_title) }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Meta Description</label><input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $category->meta_description) }}"></div></div>
                    </div>
                    <div class="d-flex">
                        <div class="custom-control custom-checkbox mr-4">
                            <input type="hidden" name="is_active" value="0"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="hidden" name="is_featured" value="0"><input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}><label class="custom-control-label" for="is_featured">Featured</label>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Create' }} Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
