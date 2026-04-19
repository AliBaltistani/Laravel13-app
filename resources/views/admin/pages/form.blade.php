@extends('layouts.admin')
@section('title', $isEdit ? 'Edit Page' : 'Add Page')
@section('breadcrumb')<li><a href="{{ route('admin.pages.index') }}">Pages</a></li><li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>@endsection

@section('admin-content')
<form method="POST" action="{{ $isEdit ? route('admin.pages.update', $page) : route('admin.pages.store') }}" enctype="multipart/form-data" id="pageForm">
    @csrf @if($isEdit) @method('PUT') @endif

    @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-0" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-content"><i class="fas fa-edit mr-1"></i> Content</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-media"><i class="fas fa-photo-video mr-1"></i> Media</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-sections"><i class="fas fa-th-large mr-1"></i> Sections</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-code"><i class="fas fa-code mr-1"></i> Custom Code</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-seo"><i class="fas fa-search mr-1"></i> SEO</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-settings"><i class="fas fa-cog mr-1"></i> Settings</a></li>
    </ul>

    <div class="admin-card" style="border-top-left-radius:0;">
        <div class="card-body">
            <div class="tab-content">

                {{-- Content Tab --}}
                <div class="tab-pane fade show active" id="tab-content">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" placeholder="Auto-generated from title">
                    </div>
                    <div class="form-group">
                        <label>Excerpt</label>
                        <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $page->excerpt) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" class="form-control richtext-editor" rows="15" id="editor_content">{{ old('content', $page->content) }}</textarea>
                    </div>
                </div>

                {{-- Media Tab --}}
                <div class="tab-pane fade" id="tab-media">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-image mr-1"></i> Featured Image</label>
                                @if($page->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($page->image) }}" class="img-fluid rounded" style="max-height:150px;">
                                    <div class="custom-control custom-checkbox mt-1">
                                        <input type="checkbox" class="custom-control-input" id="remove_image" name="remove_image" value="1">
                                        <label class="custom-control-label text-danger" for="remove_image"><small>Remove image</small></label>
                                    </div>
                                </div>
                                @endif
                                <input type="file" name="image" class="form-control-file" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-flag mr-1"></i> Banner Image</label>
                                @if($page->banner_image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($page->banner_image) }}" class="img-fluid rounded" style="max-height:150px;">
                                    <div class="custom-control custom-checkbox mt-1">
                                        <input type="checkbox" class="custom-control-input" id="remove_banner" name="remove_banner" value="1">
                                        <label class="custom-control-label text-danger" for="remove_banner"><small>Remove banner</small></label>
                                    </div>
                                </div>
                                @endif
                                <input type="file" name="banner_image" class="form-control-file" accept="image/*">
                                <small class="text-muted">Full-width banner displayed at the top of the page</small>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold mb-3"><i class="fas fa-video mr-1"></i> Video</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Video URL <small class="text-muted">(YouTube, Vimeo)</small></label>
                                <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $page->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Video File Upload <small class="text-muted">(MP4, WebM, max 50MB)</small></label>
                                @if($page->video_file)
                                <div class="mb-2">
                                    <span class="badge badge-info"><i class="fas fa-file-video mr-1"></i> {{ basename($page->video_file) }}</span>
                                    <div class="custom-control custom-checkbox mt-1">
                                        <input type="checkbox" class="custom-control-input" id="remove_video_file" name="remove_video_file" value="1">
                                        <label class="custom-control-label text-danger" for="remove_video_file"><small>Remove video</small></label>
                                    </div>
                                </div>
                                @endif
                                <input type="file" name="video_file" class="form-control-file" accept="video/mp4,video/webm,video/ogg">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold mb-3"><i class="fas fa-images mr-1"></i> Gallery Images</h6>
                    @if($isEdit && $page->images->count())
                    <div class="row mb-3" id="gallery-existing">
                        @foreach($page->images as $img)
                        <div class="col-md-3 col-6 mb-3" id="gallery-item-{{ $img->id }}">
                            <div class="border rounded p-2 text-center position-relative">
                                <img src="{{ Storage::url($img->image) }}" class="img-fluid rounded" style="max-height:120px;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" style="top:5px;right:10px;" onclick="deleteGalleryImage({{ $page->id }}, {{ $img->id }})">
                                    <i class="fas fa-times"></i>
                                </button>
                                <small class="d-block text-muted mt-1">{{ $img->alt_text ?: 'No alt text' }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <input type="file" name="gallery_images[]" class="form-control-file" accept="image/*" multiple>
                    <small class="text-muted">Select multiple images to upload to the gallery</small>
                </div>

                {{-- Sections Tab --}}
                <div class="tab-pane fade" id="tab-sections">
                    <div class="mb-3 p-3" style="background:rgba(13,110,253,0.05);border-radius:8px;border:1px solid rgba(13,110,253,0.1);">
                        <h6 class="mb-1"><i class="fas fa-th-large text-primary mr-1"></i> Content Sections</h6>
                        <p class="text-muted mb-0" style="font-size:13px;">Add modular content blocks to your page. Sections are rendered in order below the main content.</p>
                    </div>

                    <div id="sections-container">
                        @if($isEdit)
                            @foreach($page->sections as $i => $section)
                            <div class="admin-card mb-3 section-block" data-index="{{ $i }}">
                                <div class="card-header d-flex align-items-center">
                                    <i class="fas fa-grip-vertical text-muted mr-2"></i>
                                    <h6 class="mb-0">Section #{{ $i + 1 }}</h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger ml-auto" onclick="this.closest('.section-block').remove()"><i class="fas fa-trash"></i></button>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="sections[{{ $i }}][id]" value="{{ $section->id }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Type</label>
                                                <select name="sections[{{ $i }}][type]" class="form-control">
                                                    <option value="text" {{ $section->type === 'text' ? 'selected' : '' }}>Text / HTML</option>
                                                    <option value="image" {{ $section->type === 'image' ? 'selected' : '' }}>Image</option>
                                                    <option value="video" {{ $section->type === 'video' ? 'selected' : '' }}>Video</option>
                                                    <option value="banner" {{ $section->type === 'banner' ? 'selected' : '' }}>Banner</option>
                                                    <option value="html" {{ $section->type === 'html' ? 'selected' : '' }}>Raw HTML</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group"><label>Title</label><input type="text" name="sections[{{ $i }}][title]" class="form-control" value="{{ $section->title }}"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group"><label>CSS Class</label><input type="text" name="sections[{{ $i }}][css_class]" class="form-control" value="{{ $section->css_class }}" placeholder="e.g. col-md-6"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group"><label>BG Color</label><input type="color" name="sections[{{ $i }}][bg_color]" class="form-control" value="{{ $section->bg_color ?? '#ffffff' }}" style="height:38px;"></div>
                                        </div>
                                    </div>
                                    <div class="form-group"><label>Content</label><textarea name="sections[{{ $i }}][content]" class="form-control" rows="4">{{ $section->content }}</textarea></div>
                                    <div class="row">
                                        <div class="col-md-6"><div class="form-group"><label>Image</label>
                                            @if($section->image)<img src="{{ Storage::url($section->image) }}" class="img-fluid rounded mb-2" style="max-height:80px;">@endif
                                            <input type="file" name="sections[{{ $i }}][image]" class="form-control-file" accept="image/*"></div></div>
                                        <div class="col-md-6"><div class="form-group"><label>Video URL</label><input type="url" name="sections[{{ $i }}][video_url]" class="form-control" value="{{ $section->video_url }}"></div></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"><label>Sort Order</label><input type="number" name="sections[{{ $i }}][sort_order]" class="form-control" value="{{ $section->sort_order }}"></div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end pb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="sections[{{ $i }}][is_active]" value="0">
                                                <input type="checkbox" class="custom-control-input" id="sec_active_{{ $i }}" name="sections[{{ $i }}][is_active]" value="1" {{ $section->is_active ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sec_active_{{ $i }}">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" class="btn btn-outline-primary" onclick="addSection()">
                        <i class="fas fa-plus mr-1"></i> Add Section
                    </button>
                </div>

                {{-- Custom Code Tab --}}
                <div class="tab-pane fade" id="tab-code">
                    <div class="mb-3 p-3" style="background:rgba(255,193,7,0.06);border-radius:8px;border:1px solid rgba(255,193,7,0.15);">
                        <h6 class="mb-1"><i class="fas fa-exclamation-triangle text-warning mr-1"></i> Advanced: Custom Code</h6>
                        <p class="text-muted mb-0" style="font-size:13px;">Add custom CSS styles and JavaScript for this specific page only. Use with caution.</p>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-css3-alt mr-1"></i> Custom CSS</label>
                        <textarea name="custom_css" class="form-control" rows="10" style="font-family:monospace;font-size:13px;" placeholder="/* Custom styles for this page */&#10;.my-class { color: red; }">{{ old('custom_css', $page->custom_css) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label><i class="fab fa-js-square mr-1"></i> Custom JavaScript</label>
                        <textarea name="custom_js" class="form-control" rows="10" style="font-family:monospace;font-size:13px;" placeholder="// Custom scripts for this page&#10;console.log('Page loaded');">{{ old('custom_js', $page->custom_js) }}</textarea>
                    </div>
                </div>

                {{-- SEO Tab --}}
                <div class="tab-pane fade" id="tab-seo">
                    <div class="form-group"><label>Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}" placeholder="Leave empty to use page title"><small class="text-muted">Recommended: 50-60 characters</small></div>
                    <div class="form-group"><label>Meta Description</label><textarea name="meta_description" class="form-control" rows="3" placeholder="Brief description for search engines">{{ old('meta_description', $page->meta_description) }}</textarea><small class="text-muted">Recommended: 150-160 characters</small></div>
                </div>

                {{-- Settings Tab --}}
                <div class="tab-pane fade" id="tab-settings">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label>Template</label>
                                <select name="template" class="form-control">
                                    <option value="default" {{ old('template',$page->template)==='default'?'selected':'' }}>Default</option>
                                    <option value="full-width" {{ old('template',$page->template)==='full-width'?'selected':'' }}>Full Width</option>
                                    <option value="with-sidebar" {{ old('template',$page->template)==='with-sidebar'?'selected':'' }}>With Sidebar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $page->sort_order ?? 0) }}"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Layout</label>
                                <select name="layout" class="form-control">
                                    <option value="default" {{ old('layout',$page->layout)==='default'?'selected':'' }}>Default (Container)</option>
                                    <option value="full-width" {{ old('layout',$page->layout)==='full-width'?'selected':'' }}>Full Width (No Container)</option>
                                    <option value="with-sidebar" {{ old('layout',$page->layout)==='with-sidebar'?'selected':'' }}>With Sidebar</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Active (Published)</label>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold mb-3"><i class="fas fa-map-signs mr-1"></i> Display Location</h6>
                    <p class="text-muted mb-3" style="font-size: 13px;">Choose where this page link appears on the frontend storefront.</p>

                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="show_in_header" value="0">
                        <input type="checkbox" class="custom-control-input" id="show_in_header" name="show_in_header" value="1" {{ old('show_in_header', $page->show_in_header) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="show_in_header">
                            <i class="fas fa-arrow-up text-primary mr-1"></i> Show in Header Navigation
                            <br><small class="text-muted font-weight-normal">Page link will appear in the main navigation bar</small>
                        </label>
                    </div>

                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="show_in_footer" value="0">
                        <input type="checkbox" class="custom-control-input" id="show_in_footer" name="show_in_footer" value="1" {{ old('show_in_footer', $page->show_in_footer) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="show_in_footer">
                            <i class="fas fa-arrow-down text-success mr-1"></i> Show in Footer Links
                            <br><small class="text-muted font-weight-normal">Page link will appear in the footer pages column</small>
                        </label>
                    </div>

                    <hr>

                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="show_sidebar" value="0">
                        <input type="checkbox" class="custom-control-input" id="show_sidebar" name="show_sidebar" value="1" {{ old('show_sidebar', $page->show_sidebar) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="show_sidebar">Show Sidebar</label>
                    </div>

                    <div class="form-group" id="sidebar-content-group" style="{{ old('show_sidebar', $page->show_sidebar) ? '' : 'display:none;' }}">
                        <label>Sidebar Content (HTML)</label>
                        <textarea name="sidebar_content" class="form-control" rows="6" style="font-family:monospace;font-size:13px;">{{ old('sidebar_content', $page->sidebar_content) }}</textarea>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update Page' : 'Create Page' }}</button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
var sectionIndex = {{ $isEdit ? $page->sections->count() : 0 }};

function addSection() {
    var idx = sectionIndex++;
    var html = `<div class="admin-card mb-3 section-block" data-index="${idx}">
        <div class="card-header d-flex align-items-center">
            <i class="fas fa-grip-vertical text-muted mr-2"></i>
            <h6 class="mb-0">New Section</h6>
            <button type="button" class="btn btn-sm btn-outline-danger ml-auto" onclick="this.closest('.section-block').remove()"><i class="fas fa-trash"></i></button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><div class="form-group"><label>Type</label><select name="sections[${idx}][type]" class="form-control"><option value="text">Text / HTML</option><option value="image">Image</option><option value="video">Video</option><option value="banner">Banner</option><option value="html">Raw HTML</option></select></div></div>
                <div class="col-md-5"><div class="form-group"><label>Title</label><input type="text" name="sections[${idx}][title]" class="form-control"></div></div>
                <div class="col-md-2"><div class="form-group"><label>CSS Class</label><input type="text" name="sections[${idx}][css_class]" class="form-control" placeholder="e.g. col-md-6"></div></div>
                <div class="col-md-2"><div class="form-group"><label>BG Color</label><input type="color" name="sections[${idx}][bg_color]" class="form-control" value="#ffffff" style="height:38px;"></div></div>
            </div>
            <div class="form-group"><label>Content</label><textarea name="sections[${idx}][content]" class="form-control" rows="4"></textarea></div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Image</label><input type="file" name="sections[${idx}][image]" class="form-control-file" accept="image/*"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Video URL</label><input type="url" name="sections[${idx}][video_url]" class="form-control"></div></div>
            </div>
            <div class="row">
                <div class="col-md-4"><div class="form-group"><label>Sort Order</label><input type="number" name="sections[${idx}][sort_order]" class="form-control" value="${idx}"></div></div>
                <div class="col-md-4 d-flex align-items-end pb-3">
                    <div class="custom-control custom-checkbox"><input type="hidden" name="sections[${idx}][is_active]" value="0"><input type="checkbox" class="custom-control-input" id="sec_active_${idx}" name="sections[${idx}][is_active]" value="1" checked><label class="custom-control-label" for="sec_active_${idx}">Active</label></div>
                </div>
            </div>
        </div>
    </div>`;
    document.getElementById('sections-container').insertAdjacentHTML('beforeend', html);
}

function deleteGalleryImage(pageId, imageId) {
    if (!confirm('Delete this image?')) return;
    fetch(`/admin/pages/${pageId}/images/${imageId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
        .then(r => r.json()).then(d => { if (d.success) document.getElementById('gallery-item-' + imageId).remove(); });
}

document.getElementById('show_sidebar').addEventListener('change', function() {
    document.getElementById('sidebar-content-group').style.display = this.checked ? '' : 'none';
});
</script>
@endpush
@endsection
