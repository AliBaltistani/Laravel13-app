@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Post' : 'Add Post')
@section('breadcrumb')<li><a href="{{ route('admin.posts.index') }}">Posts</a></li><li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>@endsection
@section('admin-content')
<form method="POST" action="{{ $isEdit ? route('admin.posts.update', $post) : route('admin.posts.store') }}" enctype="multipart/form-data">@csrf @if($isEdit) @method('PUT') @endif
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row">
<div class="col-lg-8">
    <div class="admin-card mb-3"><div class="card-header"><h5>Post Content</h5></div><div class="card-body">
        <div class="form-group"><label>Title <span class="text-danger">*</span></label><input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required></div>
        <div class="form-group"><label>Slug</label><input type="text" name="slug" class="form-control" value="{{ old('slug', $post->slug) }}" placeholder="Auto-generated"></div>
        <div class="form-group"><label>Excerpt</label><textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $post->excerpt) }}</textarea></div>
        <div class="form-group"><label>Content</label><textarea name="content" class="form-control richtext-editor" rows="12">{{ old('content', $post->content) }}</textarea></div>
    </div></div>
    <div class="admin-card mb-3"><div class="card-header"><h5>SEO</h5></div><div class="card-body">
        <div class="form-group"><label>Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title) }}"></div>
        <div class="form-group"><label>Meta Description</label><textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $post->meta_description) }}</textarea></div>
    </div></div>
</div>
<div class="col-lg-4">
    <div class="admin-card mb-3"><div class="card-header"><h5>Publish</h5></div><div class="card-body">
        <div class="form-group"><label>Category</label><select name="post_category_id" class="form-control"><option value="">None</option>@foreach($categories as $c)<option value="{{ $c->id }}" {{ old('post_category_id',$post->post_category_id)==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Published At</label><input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"></div>
        <div class="custom-control custom-checkbox mb-3"><input type="hidden" name="is_published" value="0"><input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }}><label class="custom-control-label" for="is_published">Published</label></div>
        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Create' }}</button>
    </div></div>
    <div class="admin-card mb-3"><div class="card-header"><h5>Featured Image</h5></div><div class="card-body">
        @if($post->image)<img src="{{ Storage::url($post->image) }}" class="img-fluid rounded mb-2">@endif
        <input type="file" name="image" class="form-control-file" accept="image/*">
    </div></div>
</div></div></form>
@endsection
