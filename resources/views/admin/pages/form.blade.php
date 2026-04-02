@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Page' : 'Add Page')
@section('breadcrumb')<li><a href="{{ route('admin.pages.index') }}">Pages</a></li><li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>@endsection
@section('admin-content')
<form method="POST" action="{{ $isEdit ? route('admin.pages.update', $page) : route('admin.pages.store') }}" enctype="multipart/form-data">@csrf @if($isEdit) @method('PUT') @endif
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row"><div class="col-lg-8">
    <div class="admin-card mb-3"><div class="card-header"><h5>Page Content</h5></div><div class="card-body">
        <div class="form-group"><label>Title <span class="text-danger">*</span></label><input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required></div>
        <div class="form-group"><label>Slug</label><input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" placeholder="Auto-generated"></div>
        <div class="form-group"><label>Excerpt</label><textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $page->excerpt) }}</textarea></div>
        <div class="form-group"><label>Content</label><textarea name="content" class="form-control" rows="12">{{ old('content', $page->content) }}</textarea></div>
    </div></div>
    <div class="admin-card mb-3"><div class="card-header"><h5>SEO</h5></div><div class="card-body">
        <div class="form-group"><label>Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}"></div>
        <div class="form-group"><label>Meta Description</label><textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea></div>
    </div></div>
</div><div class="col-lg-4">
    <div class="admin-card mb-3"><div class="card-header"><h5>Settings</h5></div><div class="card-body">
        <div class="form-group"><label>Template</label><select name="template" class="form-control"><option value="default" {{ old('template',$page->template)==='default'?'selected':'' }}>Default</option><option value="full-width" {{ old('template',$page->template)==='full-width'?'selected':'' }}>Full Width</option></select></div>
        <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $page->sort_order ?? 0) }}"></div>
        <div class="custom-control custom-checkbox mb-3"><input type="hidden" name="is_active" value="0"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label></div>
        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Create' }}</button>
    </div></div>
    <div class="admin-card mb-3"><div class="card-header"><h5>Image</h5></div><div class="card-body">
        @if($page->image)<img src="{{ Storage::url($page->image) }}" class="img-fluid rounded mb-2">@endif
        <input type="file" name="image" class="form-control-file" accept="image/*">
    </div></div>
</div></div></form>
@endsection
