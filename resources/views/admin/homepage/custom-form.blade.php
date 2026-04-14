@extends('layouts.admin')
@section('title', 'Add Custom Section')
@section('breadcrumb')<li><a href="{{ route('admin.homepage.index') }}">Homepage Builder</a></li><li class="active">Add Custom Section</li>@endsection

@section('admin-content')
<form method="POST" action="{{ route('admin.homepage.custom.store') }}">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="card-header"><h5>New Custom HTML Section</h5></div>
                <div class="card-body">
                    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

                    <div class="form-group">
                        <label>Section Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. Promotional Banner, Custom CTA">
                    </div>
                    <div class="form-group">
                        <label>HTML Content <span class="text-danger">*</span></label>
                        <textarea name="settings[custom_html]" class="form-control" rows="14" style="font-family:monospace;font-size:13px;" required placeholder="<div class='container'>&#10;  <div class='row'>&#10;    <div class='col-md-6'>Your content here</div>&#10;  </div>&#10;</div>">{{ old('settings.custom_html') }}</textarea>
                        <small class="text-muted">Full HTML supported. Use Bootstrap grid classes for layout.</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label>Container Class</label><input type="text" name="settings[container_class]" class="form-control" value="container" placeholder="container or container-fluid"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"><label>Extra CSS Class</label><input type="text" name="settings[css_class]" class="form-control" value="" placeholder="e.g. py-5 bg-light"></div>
                        </div>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-plus mr-1"></i> Add Section</button>
                    <a href="{{ route('admin.homepage.index') }}" class="btn btn-outline-secondary btn-block mt-2">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
