@extends('layouts.admin')
@section('title', 'Edit Section: ' . $section->title)
@section('breadcrumb')<li><a href="{{ route('admin.homepage.index') }}">Homepage Builder</a></li><li class="active">{{ $section->title }}</li>@endsection

@section('admin-content')
<form method="POST" action="{{ route('admin.homepage.update', $section) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <div class="admin-card mb-3">
                <div class="card-header"><h5>Section Settings — {{ $section->title }}</h5></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Section Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $section->title) }}" required>
                    </div>

                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ $section->is_active ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Active (Visible on homepage)</label>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold mb-3">Type-Specific Settings</h6>

                    @php $s = $section->settings ?? []; @endphp

                    @if($section->type === 'banners')
                        <div class="alert alert-info mb-3" style="font-size:13px;">
                            <i class="fas fa-info-circle mr-1"></i> This section displays banners from <strong>Banners Management</strong>.
                            <a href="{{ route('admin.banners.index') }}" class="alert-link">Manage Banners →</a>
                            <br>Make sure banners have position "<strong>{{ $s['banner_position'] ?? 'home-mid' }}</strong>" to appear here.
                        </div>
                        <div class="row">
                            <div class="col-md-4"><div class="form-group"><label>Banner Position</label><input type="text" name="settings[banner_position]" class="form-control" value="{{ $s['banner_position'] ?? 'home-mid' }}"></div></div>
                            <div class="col-md-4"><div class="form-group"><label>Max Banners</label><input type="number" name="settings[max_banners]" class="form-control" value="{{ $s['max_banners'] ?? 3 }}"></div></div>
                            <div class="col-md-4"><div class="form-group"><label>Column Class</label><input type="text" name="settings[col_class]" class="form-control" value="{{ !empty($s['col_class']) ? $s['col_class'] : 'col-md-4 col-sm-6' }}" placeholder="e.g. col-md-4 col-sm-6"></div></div>
                        </div>
                        <div class="form-group"><label>Container Class</label><input type="text" name="settings[container_class]" class="form-control" value="{{ !empty($s['container_class']) ? $s['container_class'] : 'container' }}"></div>

                    @elseif($section->type === 'products')
                        <div class="row">
                            <div class="col-md-4"><div class="form-group"><label>Product Type</label>
                                <select name="settings[product_type]" class="form-control">
                                    <option value="featured" {{ ($s['product_type'] ?? '') === 'featured' ? 'selected' : '' }}>Featured</option>
                                    <option value="new_arrivals" {{ ($s['product_type'] ?? '') === 'new_arrivals' ? 'selected' : '' }}>New Arrivals</option>
                                    <option value="best_selling" {{ ($s['product_type'] ?? '') === 'best_selling' ? 'selected' : '' }}>Best Selling</option>
                                </select>
                            </div></div>
                            <div class="col-md-4"><div class="form-group"><label>Limit</label><input type="number" name="settings[limit]" class="form-control" value="{{ $s['limit'] ?? 8 }}"></div></div>
                            <div class="col-md-4"><div class="form-group"><label>Column Class</label><input type="text" name="settings[col_class]" class="form-control" value="{{ !empty($s['col_class']) ? $s['col_class'] : 'col-6 col-sm-4 col-md-3' }}"></div></div>
                        </div>
                        <div class="form-group"><label>Section Title</label><input type="text" name="settings[section_title]" class="form-control" value="{{ $s['section_title'] ?? 'Featured Products' }}"></div>
                        <div class="form-group"><label>Container Class</label><input type="text" name="settings[container_class]" class="form-control" value="{{ !empty($s['container_class']) ? $s['container_class'] : 'container' }}"></div>

                        {{-- Product Source --}}
                        <hr>
                        <h6 class="font-weight-bold mb-3"><i class="fas fa-boxes mr-1"></i> Product Source</h6>
                        <div class="form-group">
                            <select name="settings[product_source]" class="form-control" id="productSource" onchange="toggleProductPicker()">
                                <option value="auto" {{ ($s['product_source'] ?? 'auto') === 'auto' ? 'selected' : '' }}>Automatic (based on type above)</option>
                                <option value="manual" {{ ($s['product_source'] ?? 'auto') === 'manual' ? 'selected' : '' }}>Manual (hand-pick products)</option>
                            </select>
                            <small class="text-muted">Auto uses filters above. Manual lets you choose exact products.</small>
                        </div>

                        <div id="manualProductPicker" style="{{ ($s['product_source'] ?? 'auto') === 'manual' ? '' : 'display:none;' }}">
                            @include('admin.homepage._product_picker', ['section' => $section, 'allProducts' => $allProducts ?? collect()])
                        </div>

                    @elseif($section->type === 'sale_banner')
                        {{-- Banner Image Upload --}}
                        <div class="form-group">
                            <label><i class="fas fa-image mr-1"></i> Banner Image <small class="text-muted">(optional — overrides text-only layout)</small></label>
                            @if(!empty($s['banner_image']))
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $s['banner_image']) }}" class="img-fluid rounded" style="max-height:150px;">
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="remove_banner_image" name="remove_banner_image" value="1">
                                    <label class="custom-control-label text-danger" for="remove_banner_image"><small>Remove image (revert to text-only)</small></label>
                                </div>
                            </div>
                            @endif
                            <input type="file" name="banner_image" class="form-control-file" accept="image/*">
                            <small class="text-muted">Upload a full-width banner image. If set, the image is shown instead of the text-based layout.</small>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label>Title</label><input type="text" name="settings[title]" class="form-control" value="{{ $s['title'] ?? '' }}"></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Subtitle</label><input type="text" name="settings[subtitle]" class="form-control" value="{{ $s['subtitle'] ?? '' }}"></div></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label>Discount %</label><input type="text" name="settings[discount]" class="form-control" value="{{ $s['discount'] ?? '50' }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Button Text</label><input type="text" name="settings[button_text]" class="form-control" value="{{ $s['button_text'] ?? 'Shop Now!' }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Button URL</label><input type="text" name="settings[button_url]" class="form-control" value="{{ $s['button_url'] ?? '/shop' }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>BG Class <small>(text mode)</small></label><input type="text" name="settings[bg_class]" class="form-control" value="{{ $s['bg_class'] ?? 'bg-secondary' }}"></div></div>
                        </div>

                    @elseif($section->type === 'widgets')
                        <div class="form-group"><label>Column Class</label><input type="text" name="settings[col_class]" class="form-control" value="{{ !empty($s['col_class']) ? $s['col_class'] : 'col-md-4 col-sm-6' }}"></div>
                        <div class="form-group"><label>Widget Limit (per column)</label><input type="number" name="settings[widget_limit]" class="form-control" value="{{ $s['widget_limit'] ?? 3 }}"></div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox mb-2"><input type="hidden" name="settings[show_top_rated]" value="0"><input type="checkbox" class="custom-control-input" id="show_tr" name="settings[show_top_rated]" value="1" {{ ($s['show_top_rated'] ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="show_tr">Show Top Rated</label></div>
                                <input type="text" name="settings[top_rated_title]" class="form-control form-control-sm" value="{{ $s['top_rated_title'] ?? 'Top Rated Products' }}">
                            </div>
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox mb-2"><input type="hidden" name="settings[show_best_selling]" value="0"><input type="checkbox" class="custom-control-input" id="show_bs" name="settings[show_best_selling]" value="1" {{ ($s['show_best_selling'] ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="show_bs">Show Best Selling</label></div>
                                <input type="text" name="settings[best_selling_title]" class="form-control form-control-sm" value="{{ $s['best_selling_title'] ?? 'Best Selling Products' }}">
                            </div>
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox mb-2"><input type="hidden" name="settings[show_latest]" value="0"><input type="checkbox" class="custom-control-input" id="show_lt" name="settings[show_latest]" value="1" {{ ($s['show_latest'] ?? true) ? 'checked' : '' }}><label class="custom-control-label" for="show_lt">Show Latest</label></div>
                                <input type="text" name="settings[latest_title]" class="form-control form-control-sm" value="{{ $s['latest_title'] ?? 'Latest Products' }}">
                            </div>
                        </div>

                        {{-- Product Source for widgets too --}}
                        <hr>
                        <h6 class="font-weight-bold mb-3"><i class="fas fa-boxes mr-1"></i> Product Source</h6>
                        <div class="form-group">
                            <select name="settings[product_source]" class="form-control" id="productSource" onchange="toggleProductPicker()">
                                <option value="auto" {{ ($s['product_source'] ?? 'auto') === 'auto' ? 'selected' : '' }}>Automatic (based on ratings/sales/date)</option>
                                <option value="manual" {{ ($s['product_source'] ?? 'auto') === 'manual' ? 'selected' : '' }}>Manual (hand-pick products)</option>
                            </select>
                        </div>

                        <div id="manualProductPicker" style="{{ ($s['product_source'] ?? 'auto') === 'manual' ? '' : 'display:none;' }}">
                            @include('admin.homepage._product_picker', ['section' => $section, 'allProducts' => $allProducts ?? collect()])
                        </div>

                    @elseif($section->type === 'instagram')
                        <div class="alert alert-info mb-3" style="font-size:13px;">
                            <i class="fab fa-instagram mr-1"></i> This section displays images from <strong>Banners Management</strong>.
                            <a href="{{ route('admin.banners.index') }}" class="alert-link">Manage Banners →</a>
                            <br>Create banners with position "<strong>{{ $s['banner_position'] ?? 'home-instagram' }}</strong>" to display them in this feed.
                        </div>
                        <div class="form-group"><label>Section Title</label><input type="text" name="settings[section_title]" class="form-control" value="{{ $s['section_title'] ?? 'Follow On Instagram' }}"></div>
                        <div class="form-group"><label>Banner Position</label><input type="text" name="settings[banner_position]" class="form-control" value="{{ $s['banner_position'] ?? 'home-instagram' }}"></div>

                    @elseif($section->type === 'slider')
                        <div class="form-group"><label>Slider Position</label><input type="text" name="settings[slider_position]" class="form-control" value="{{ $s['slider_position'] ?? 'hero' }}"></div>
                        <div class="form-group"><label>Container Class</label><input type="text" name="settings[container_class]" class="form-control" value="{{ !empty($s['container_class']) ? $s['container_class'] : 'container' }}"></div>

                    @elseif($section->type === 'brands')
                        <div class="form-group"><label>Container Class</label><input type="text" name="settings[container_class]" class="form-control" value="{{ !empty($s['container_class']) ? $s['container_class'] : 'container' }}"></div>

                    @elseif($section->type === 'custom_html')
                        <div class="form-group"><label>Custom HTML Content</label><textarea name="settings[custom_html]" class="form-control" rows="12" style="font-family:monospace;font-size:13px;">{{ $s['custom_html'] ?? '' }}</textarea></div>
                        <div class="form-group"><label>Container Class</label><input type="text" name="settings[container_class]" class="form-control" value="{{ $s['container_class'] ?? 'container' }}"></div>
                        <div class="form-group"><label>Extra CSS Class</label><input type="text" name="settings[css_class]" class="form-control" value="{{ $s['css_class'] ?? '' }}"></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-3">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> Save Settings</button>
                    <a href="{{ route('admin.homepage.index') }}" class="btn btn-outline-secondary btn-block mt-2">Cancel</a>
                </div>
            </div>
            <div class="admin-card mb-3">
                <div class="card-body">
                    <p class="text-muted mb-1" style="font-size:13px;"><strong>Section Key:</strong> {{ $section->key }}</p>
                    <p class="text-muted mb-1" style="font-size:13px;"><strong>Type:</strong> {{ $section->type }}</p>
                    <p class="text-muted mb-0" style="font-size:13px;"><strong>Sort Order:</strong> {{ $section->sort_order }}</p>
                </div>
            </div>
            @if(in_array($section->type, ['products', 'widgets']))
            <div class="admin-card">
                <div class="card-header"><h5><i class="fas fa-box mr-1"></i> Assigned Products</h5></div>
                <div class="card-body">
                    <p class="mb-0 text-muted" style="font-size:13px;">
                        <strong>{{ $section->products()->count() }}</strong> product(s) manually assigned.
                        <br>Source: <strong>{{ ($section->settings['product_source'] ?? 'auto') === 'manual' ? 'Manual' : 'Automatic' }}</strong>
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>

@push('scripts')
<script>
function toggleProductPicker() {
    var picker = document.getElementById('manualProductPicker');
    var source = document.getElementById('productSource');
    if (picker && source) {
        picker.style.display = source.value === 'manual' ? '' : 'none';
    }
}
</script>
@endpush
@endsection
