@extends('layouts.admin')
@section('title', 'Settings')
@section('breadcrumb')<li class="active">Settings</li>@endsection

@section('admin-content')
<h4 class="mb-3">Site Settings</h4>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf

    {{-- Tabs Navigation --}}
    <ul class="nav nav-tabs mb-0" role="tablist">
        @php $groups = ['general','appearance','seo','social','payment','shipping','mail','auth','promo','custom_code']; @endphp
        @foreach($groups as $i => $group)
        <li class="nav-item">
            <a class="nav-link {{ $i === 0 ? 'active' : '' }}" data-toggle="tab" href="#tab-{{ $group }}">
                @switch($group)
                    @case('auth')
                        <i class="fas fa-user-shield mr-1"></i>
                        @break
                    @case('legal')
                        <i class="fas fa-gavel mr-1"></i>
                        @break
                    @case('mail')
                        <i class="fas fa-envelope mr-1"></i>
                        @break
                    @case('custom_code')
                        <i class="fas fa-code mr-1"></i>
                        @break
                    @default
                @endswitch
                {{ $group === 'custom_code' ? 'Custom Code' : ucfirst($group) }}
            </a>
        </li>
        @endforeach
    </ul>

    <div class="admin-card" style="border-top-left-radius:0;">
        <div class="card-body">
            <div class="tab-content">
                @foreach($groups as $i => $group)
                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab-{{ $group }}">

                    {{-- Appearance tab special header --}}
                    @if($group === 'appearance')
                    <div class="mb-4 p-3" style="background: rgba(156,39,176,0.05); border-radius: 8px; border: 1px solid rgba(156,39,176,0.12);">
                        <h6 class="mb-1"><i class="fas fa-palette mr-1" style="color:#9c27b0;"></i> Appearance & Colors</h6>
                        <p class="text-muted mb-0" style="font-size: 13px;">Customize colors for the frontend store and admin panel. Changes apply instantly after saving.</p>
                    </div>
                    @endif

                    {{-- Auth tab special header --}}
                    @if($group === 'auth')
                    <div class="mb-4 p-3" style="background: rgba(13,110,253,0.05); border-radius: 8px; border: 1px solid rgba(13,110,253,0.1);">
                        <h6 class="mb-1"><i class="fas fa-shield-alt text-primary mr-1"></i> Authentication Settings</h6>
                        <p class="text-muted mb-0" style="font-size: 13px;">Configure registration, login security, OTP settings, and password policies.</p>
                    </div>
                    @endif

                    {{-- Mail tab special header --}}
                    @if($group === 'mail')
                    <div class="mb-4 p-3" style="background: rgba(255,193,7,0.05); border-radius: 8px; border: 1px solid rgba(255,193,7,0.15);">
                        <h6 class="mb-1"><i class="fas fa-envelope text-warning mr-1"></i> SMTP Email Configuration</h6>
                        <p class="text-muted mb-0" style="font-size: 13px;">Configure your SMTP server for sending transactional emails (order confirmations, password resets, etc). Leave empty to use .env defaults.</p>
                    </div>
                    @endif

                    @if($group === 'custom_code')
                    <div class="mb-4 p-3" style="background: rgba(0,0,0,0.05); border-radius: 8px; border: 1px solid rgba(0,0,0,0.1);">
                        <h6 class="mb-1"><i class="fas fa-code mr-1"></i> Custom CSS & JS</h6>
                        <p class="text-muted mb-0" style="font-size: 13px;">Add custom CSS and Javascript to be loaded on both the storefront and admin panel. This CSS and JS has the highest priority.</p>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label font-weight-bold">
                            Custom CSS
                            <br><small class="text-muted font-weight-normal">Without &lt;style&gt; tags.</small>
                        </label>
                        <div class="col-md-9">
                            <textarea name="custom_code__css" class="form-control" rows="12" style="font-family: monospace; background: #2b2b2b; color: #eee;" placeholder="body { background-color: #000; }">{{ \App\Models\Setting::get('custom_code.css') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label font-weight-bold">
                            Custom JS
                            <br><small class="text-muted font-weight-normal">Without &lt;script&gt; tags.</small>
                        </label>
                        <div class="col-md-9">
                            <textarea name="custom_code__js" class="form-control" rows="12" style="font-family: monospace; background: #2b2b2b; color: #eee;" placeholder="console.log('Hello World');">{{ \App\Models\Setting::get('custom_code.js') }}</textarea>
                        </div>
                    </div>

                    @elseif(isset($settings[$group]))
                        @if($group === 'appearance')
                            {{-- Organized Appearance sections with dividers --}}
                            @php
                                $sections = [
                                    'Branding'         => ['appearance.logo', 'appearance.favicon', 'appearance.asset_version'],
                                    'Core Colors'      => ['appearance.primary_color', 'appearance.secondary_color', 'appearance.link_color', 'appearance.body_bg_color', 'appearance.body_text_color', 'appearance.heading_color'],
                                    'Header'           => ['appearance.header_top_bg', 'appearance.header_top_text', 'appearance.header_bg', 'header.top_message', 'header.show_special_offer', 'header.special_offer_text', 'header.special_offer_url'],
                                    'Navigation Bar'   => ['appearance.nav_bg', 'appearance.nav_text_color', 'appearance.nav_hover_color'],
                                    'Promo Bar'        => ['appearance.promo_bar_bg', 'appearance.promo_bar_text'],
                                    'Buttons'          => ['appearance.btn_primary_bg', 'appearance.btn_primary_text'],
                                    'Sale & Price'     => ['appearance.sale_price_color', 'appearance.sale_badge_bg'],
                                    'Footer'           => ['appearance.footer_bg', 'appearance.footer_text_color', 'appearance.footer_heading_color', 'appearance.footer_link_color', 'appearance.footer_bottom_bg'],
                                    'Admin Panel'      => ['appearance.admin_primary', 'appearance.admin_sidebar_bg', 'appearance.admin_sidebar_text', 'appearance.admin_topbar_bg'],
                                ];
                                $settingsByKey = $settings[$group]->keyBy('key');
                                $renderedKeys = [];
                            @endphp

                            @foreach($sections as $sectionTitle => $sectionKeys)
                                <div class="mb-3 mt-4 pb-1" style="border-bottom: 2px solid #eef0f3;">
                                    <h6 class="text-uppercase font-weight-bold" style="font-size: 12px; letter-spacing: 1px; color: #6c757d;">
                                        @switch($sectionTitle)
                                            @case('Branding') <i class="fas fa-image mr-1"></i> @break
                                            @case('Core Colors') <i class="fas fa-palette mr-1"></i> @break
                                            @case('Header') <i class="fas fa-heading mr-1"></i> @break
                                            @case('Navigation Bar') <i class="fas fa-bars mr-1"></i> @break
                                            @case('Promo Bar') <i class="fas fa-bullhorn mr-1"></i> @break
                                            @case('Buttons') <i class="fas fa-mouse-pointer mr-1"></i> @break
                                            @case('Sale & Price') <i class="fas fa-tag mr-1"></i> @break
                                            @case('Footer') <i class="fas fa-shoe-prints mr-1"></i> @break
                                            @case('Admin Panel') <i class="fas fa-cogs mr-1"></i> @break
                                        @endswitch
                                        {{ $sectionTitle }}
                                    </h6>
                                </div>
                                @foreach($sectionKeys as $sKey)
                                    @if(isset($settingsByKey[$sKey]))
                                        @php $setting = $settingsByKey[$sKey]; $renderedKeys[] = $sKey; @endphp
                                        @include('admin.settings._field', ['setting' => $setting])
                                    @endif
                                @endforeach
                            @endforeach

                            {{-- Render any remaining appearance settings not in sections --}}
                            @foreach($settings[$group] as $setting)
                                @if(!in_array($setting->key, $renderedKeys))
                                    @include('admin.settings._field', ['setting' => $setting])
                                @endif
                            @endforeach
                        @else
                            @foreach($settings[$group] as $setting)
                                @if(in_array($setting->key, ['about.heading', 'about.description']))
                                    @continue
                                @endif
                                @include('admin.settings._field', ['setting' => $setting])
                            @endforeach
                        @endif
                    @else
                        <p class="text-muted py-3">No settings configured for this group yet.</p>
                    @endif
                </div>
                @endforeach
            </div>

            <hr>
            <div class="text-right">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Settings</button>
            </div>
        </div>
    </div>
</form>

{{-- Test Email --}}
<div class="admin-card mt-3">
    <div class="card-header"><h5>Test Email</h5></div>
    <div class="card-body">
        <p class="text-muted">Send a test email to your admin address to verify mail configuration.</p>
        <form method="POST" action="{{ route('admin.settings.test-email') }}">
            @csrf
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-paper-plane mr-1"></i> Send Test Email</button>
        </form>
    </div>
</div>

@push('styles')
<style>
    .richtext-editor {
        font-family: inherit;
        font-size: 13px;
        line-height: 1.5;
        min-height: 300px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Color picker: sync hex text + preview swatch on change
    document.querySelectorAll('input[type="color"]').forEach(function(picker) {
        picker.addEventListener('input', function() {
            var fieldName = this.name;
            var hexEl = document.getElementById('hex_' + fieldName);
            var previewEl = document.getElementById('preview_' + fieldName);
            if (hexEl) hexEl.value = this.value;
            if (previewEl) previewEl.style.background = this.value;
        });
    });
</script>
@endpush
@endsection
