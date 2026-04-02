@extends('layouts.admin')
@section('title', 'Slides — ' . $slider->name)
@section('breadcrumb')<li><a href="{{ route('admin.sliders.index') }}">Sliders</a></li><li class="active">{{ $slider->name }}</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Slides for: {{ $slider->name }}</h4>
</div>

{{-- Add Slide Form --}}
<div class="admin-card mb-3"><div class="card-header"><h5>Add Slide</h5></div><div class="card-body">
<form method="POST" action="{{ route('admin.sliders.slides.store', $slider) }}" enctype="multipart/form-data">@csrf
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Title</label><input type="text" name="title" class="form-control form-control-sm"></div></div>
        <div class="col-md-4"><div class="form-group"><label>Subtitle</label><input type="text" name="subtitle" class="form-control form-control-sm"></div></div>
        <div class="col-md-2"><div class="form-group"><label>Sort</label><input type="number" name="sort_order" class="form-control form-control-sm" value="0"></div></div>
        <div class="col-md-2"><div class="form-group"><label>Text Color</label><input type="color" name="text_color" class="form-control form-control-sm" value="#ffffff" style="height:31px;"></div></div>
    </div>
    <div class="row">
        <div class="col-md-3"><div class="form-group"><label>Button Text</label><input type="text" name="button_text" class="form-control form-control-sm"></div></div>
        <div class="col-md-3"><div class="form-group"><label>Button URL</label><input type="text" name="button_url" class="form-control form-control-sm"></div></div>
        <div class="col-md-3"><div class="form-group"><label>Desktop Image</label><input type="file" name="image_desktop" class="form-control-file" accept="image/*"></div></div>
        <div class="col-md-3"><div class="form-group"><label>Mobile Image</label><input type="file" name="image_mobile" class="form-control-file" accept="image/*"></div></div>
    </div>
    <div class="custom-control custom-checkbox d-inline-block mr-3"><input type="hidden" name="is_active" value="0"><input type="checkbox" class="custom-control-input" id="is_active_new" name="is_active" value="1" checked><label class="custom-control-label" for="is_active_new">Active</label></div>
    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Slide</button>
</form></div></div>

{{-- Existing Slides --}}
<div class="admin-card"><div class="card-header"><h5>Slides ({{ $slides->count() }})</h5></div><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Image</th><th>Title</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($slides as $slide)
<tr>
    <td>@if($slide->image_desktop)<img src="{{ Storage::url($slide->image_desktop) }}" style="max-height:50px;border-radius:4px;">@else —@endif</td>
    <td><strong>{{ $slide->title ?? 'Untitled' }}</strong>@if($slide->subtitle)<br><small class="text-muted">{{ $slide->subtitle }}</small>@endif</td>
    <td>{{ $slide->sort_order }}</td>
    <td>{!! $slide->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>' !!}</td>
    <td><form method="POST" action="{{ route('admin.sliders.slides.destroy', [$slider, $slide]) }}" class="d-inline" onsubmit="return confirm('Delete slide?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form></td>
</tr>
@empty<tr><td colspan="5" class="text-center text-muted py-4">No slides yet.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
