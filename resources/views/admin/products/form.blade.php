@extends('layouts.admin')

@section('title', $isEdit ? 'Edit Product' : 'Add Product')
@section('breadcrumb')
<li><a href="{{ route('admin.products.index') }}">Products</a></li>
<li class="active">{{ $isEdit ? 'Edit' : 'Create' }}</li>
@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $isEdit ? 'Edit Product: ' . $product->name : 'Add New Product' }}</h4>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to List
    </a>
</div>

<form method="POST" action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- General Tab --}}
            <div class="admin-card mb-3">
                <div class="card-header"><h5>General Information</h5></div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $product->slug) }}" placeholder="Auto-generated from name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku">SKU</label>
                                <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand_id">Brand</label>
                                <select name="brand_id" id="brand_id" class="form-control">
                                    <option value="">No Brand</option>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type">Product Type <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="simple" {{ old('type', $product->type) === 'simple' ? 'selected' : '' }}>Simple</option>
                            <option value="variable" {{ old('type', $product->type) === 'variable' ? 'selected' : '' }}>Variable</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="admin-card mb-3">
                <div class="card-header"><h5>Description</h5></div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="short_description">Short Description</label>
                        <textarea name="short_description" id="short_description" class="form-control" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label for="description">Full Description</label>
                        <textarea name="description" id="description" class="form-control" rows="8">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="admin-card mb-3">
                <div class="card-header"><h5>Pricing</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price">Price ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="compare_price">Compare Price ($)</label>
                                <input type="number" step="0.01" name="compare_price" id="compare_price" class="form-control" value="{{ old('compare_price', $product->compare_price) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cost_price">Cost Price ($)</label>
                                <input type="number" step="0.01" name="cost_price" id="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Inventory --}}
            <div class="admin-card mb-3">
                <div class="card-header"><h5>Inventory</h5></div>
                <div class="card-body">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="manage_stock" value="0">
                        <input type="checkbox" class="custom-control-input" id="manage_stock" name="manage_stock" value="1" {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="manage_stock">Track stock quantity</label>
                    </div>
                    <div class="row" id="stockFields">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stock_quantity">Stock Quantity</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="low_stock_threshold">Low Stock Threshold</label>
                                <input type="number" name="low_stock_threshold" id="low_stock_threshold" class="form-control" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="weight">Weight (kg)</label>
                                <input type="number" step="0.01" name="weight" id="weight" class="form-control" value="{{ old('weight', $product->weight) }}">
                            </div>
                        </div>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="hidden" name="allow_backorder" value="0">
                        <input type="checkbox" class="custom-control-input" id="allow_backorder" name="allow_backorder" value="1" {{ old('allow_backorder', $product->allow_backorder) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="allow_backorder">Allow backorders</label>
                    </div>
                </div>
            </div>

            {{-- Images --}}
            <div class="admin-card mb-3">
                <div class="card-header"><h5>Images</h5></div>
                <div class="card-body">
                    @if($isEdit && $product->images->count())
                    <div class="row mb-3">
                        @foreach($product->images as $img)
                        <div class="col-md-3 col-sm-4 mb-2">
                            <div class="border rounded p-2 text-center position-relative">
                                <img src="{{ Storage::url($img->image_path) }}" alt="" class="img-fluid mb-2" style="max-height:120px;object-fit:cover;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="small mb-0">
                                        <input type="radio" name="primary_image_id" value="{{ $img->id }}" {{ $img->is_primary ? 'checked' : '' }}> Primary
                                    </label>
                                    <label class="small mb-0 text-danger">
                                        <input type="checkbox" name="delete_images[]" value="{{ $img->id }}"> Delete
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <div class="form-group mb-0">
                        <label>Upload New Images</label>
                        <input type="file" name="images[]" class="form-control-file" multiple accept="image/*">
                        <small class="text-muted">Max 2MB per image. JPG, PNG, WebP, GIF.</small>
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="admin-card mb-3">
                <div class="card-header"><h5>SEO</h5></div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title) }}" maxlength="60">
                        <small class="text-muted"><span id="metaTitleCount">0</span>/60 characters</small>
                    </div>
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" class="form-control" rows="3" maxlength="160">{{ old('meta_description', $product->meta_description) }}</textarea>
                        <small class="text-muted"><span id="metaDescCount">0</span>/160 characters</small>
                    </div>
                    <div class="form-group mb-0">
                        <label for="meta_keywords">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="{{ old('meta_keywords', $product->meta_keywords) }}" placeholder="comma, separated, keywords">
                    </div>

                    {{-- Google Preview --}}
                    <div class="mt-3 p-3 bg-light rounded">
                        <small class="text-muted d-block mb-1">Google Preview:</small>
                        <div style="font-family:Arial,sans-serif;">
                            <div style="color:#1a0dab;font-size:18px;" id="seoPreviewTitle">{{ $product->meta_title ?? $product->name ?? 'Product Title' }}</div>
                            <div style="color:#006621;font-size:13px;">{{ url('/product/') }}/<span id="seoPreviewSlug">{{ $product->slug ?? 'product-slug' }}</span></div>
                            <div style="color:#545454;font-size:13px;" id="seoPreviewDesc">{{ $product->meta_description ?? $product->short_description ?? 'Product description appears here...' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Publish --}}
            <div class="admin-card mb-3">
                <div class="card-header"><h5>Publish</h5></div>
                <div class="card-body">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Active (visible on store)</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_featured">Featured Product</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="is_new" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_new" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_new">Mark as New</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update Product' : 'Create Product' }}
                    </button>
                </div>
            </div>

            {{-- Tags --}}
            <div class="admin-card mb-3">
                <div class="card-header"><h5>Tags</h5></div>
                <div class="card-body">
                    <select name="tags[]" id="tags" class="form-control" multiple size="6">
                        @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $isEdit ? $product->tags->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
                </div>
            </div>

            {{-- Homepage Sections --}}
            @if($homepageSections->count())
            <div class="admin-card mb-3">
                <div class="card-header"><h5><i class="fas fa-home mr-1"></i> Homepage Placement</h5></div>
                <div class="card-body">
                    <p class="text-muted mb-2" style="font-size:12px;">Assign this product to specific homepage sections. Products assigned manually take priority over auto-generated lists.</p>
                    @php
                        $assignedSections = $isEdit ? $product->homepageSections->pluck('id')->toArray() : [];
                    @endphp
                    @foreach($homepageSections as $hSection)
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox"
                               class="custom-control-input"
                               id="hp_section_{{ $hSection->id }}"
                               name="homepage_sections[]"
                               value="{{ $hSection->id }}"
                               {{ in_array($hSection->id, old('homepage_sections', $assignedSections)) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="hp_section_{{ $hSection->id }}">
                            {{ $hSection->title }}
                            <br><small class="text-muted">{{ ucfirst($hSection->type) }} · {{ $hSection->is_active ? 'Active' : 'Hidden' }}</small>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// SEO character counters
['meta_title', 'meta_description'].forEach(function(field) {
    var el = document.getElementById(field);
    var counter = document.getElementById(field === 'meta_title' ? 'metaTitleCount' : 'metaDescCount');
    if (el && counter) {
        counter.textContent = el.value.length;
        el.addEventListener('input', function() { counter.textContent = this.value.length; });
    }
});

// SEO live preview
var titleEl = document.getElementById('meta_title');
var nameEl = document.getElementById('name');
var slugEl = document.getElementById('slug');
var descEl = document.getElementById('meta_description');
if (titleEl) titleEl.addEventListener('input', function() { document.getElementById('seoPreviewTitle').textContent = this.value || nameEl.value || 'Product Title'; });
if (nameEl) nameEl.addEventListener('input', function() { if (!titleEl.value) document.getElementById('seoPreviewTitle').textContent = this.value || 'Product Title'; });
if (descEl) descEl.addEventListener('input', function() { document.getElementById('seoPreviewDesc').textContent = this.value || 'Product description appears here...'; });
</script>
@endpush
