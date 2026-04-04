@extends('layouts.admin')
@section('title', 'Settings')
@section('breadcrumb')<li class="active">Settings</li>@endsection

@section('admin-content')
<h4 class="mb-3">Site Settings</h4>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf

    {{-- Tabs Navigation --}}
    <ul class="nav nav-tabs mb-0" role="tablist">
        @php $groups = ['general','contact','appearance','home','seo','social','payment','shipping','mail','auth','legal','promo']; @endphp
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
                    @default
                @endswitch
                {{ ucfirst($group) }}
            </a>
        </li>
        @endforeach
    </ul>

    <div class="admin-card" style="border-top-left-radius:0;">
        <div class="card-body">
            <div class="tab-content">
                @foreach($groups as $i => $group)
                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab-{{ $group }}">

                    {{-- Auth tab special header --}}
                    @if($group === 'auth')
                    <div class="mb-4 p-3" style="background: rgba(13,110,253,0.05); border-radius: 8px; border: 1px solid rgba(13,110,253,0.1);">
                        <h6 class="mb-1"><i class="fas fa-shield-alt text-primary mr-1"></i> Authentication Settings</h6>
                        <p class="text-muted mb-0" style="font-size: 13px;">Configure registration, login security, OTP settings, and password policies.</p>
                    </div>
                    @endif

                    {{-- Legal tab special header --}}
                    @if($group === 'legal')
                    <div class="mb-4 p-3" style="background: rgba(25,135,84,0.05); border-radius: 8px; border: 1px solid rgba(25,135,84,0.1);">
                        <h6 class="mb-1"><i class="fas fa-gavel text-success mr-1"></i> Legal Pages Content</h6>
                        <p class="text-muted mb-0" style="font-size: 13px;">Manage Terms & Conditions, Privacy Policy, and Cookie Consent content. These are displayed on your storefront.</p>
                    </div>
                    @endif

                    {{-- Mail tab special header --}}
                    @if($group === 'mail')
                    <div class="mb-4 p-3" style="background: rgba(255,193,7,0.05); border-radius: 8px; border: 1px solid rgba(255,193,7,0.15);">
                        <h6 class="mb-1"><i class="fas fa-envelope text-warning mr-1"></i> SMTP Email Configuration</h6>
                        <p class="text-muted mb-0" style="font-size: 13px;">Configure your SMTP server for sending transactional emails (order confirmations, password resets, etc). Leave empty to use .env defaults.</p>
                    </div>
                    @endif

                    @if(isset($settings[$group]))
                        @foreach($settings[$group] as $setting)
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label font-weight-bold">
                                {{ $setting->label ?? $setting->key }}
                                @if($setting->description)
                                <br><small class="text-muted font-weight-normal">{{ $setting->description }}</small>
                                @endif
                            </label>
                            <div class="col-md-9">
                                @php $fieldName = str_replace('.', '__', $setting->key); @endphp

                                @if($setting->type === 'boolean')
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="hidden" name="{{ $fieldName }}" value="0">
                                        <input type="checkbox" class="custom-control-input" id="s_{{ $fieldName }}" name="{{ $fieldName }}" value="1" {{ $setting->value ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="s_{{ $fieldName }}">Enabled</label>
                                    </div>
                                @elseif($setting->type === 'richtext')
                                    <textarea name="{{ $fieldName }}" class="form-control richtext-editor" rows="12" id="editor_{{ $fieldName }}">{{ $setting->value }}</textarea>
                                @elseif($setting->type === 'textarea' || $setting->type === 'json')
                                    <textarea name="{{ $fieldName }}" class="form-control" rows="4">{{ $setting->value }}</textarea>
                                @elseif($setting->type === 'number')
                                    <input type="number" name="{{ $fieldName }}" class="form-control" value="{{ $setting->value }}">
                                @elseif($setting->type === 'color')
                                    <input type="color" name="{{ $fieldName }}" class="form-control" value="{{ $setting->value ?? '#0d6efd' }}" style="height:38px;width:100px;">
                                @elseif($setting->type === 'image')
                                    @if($setting->value)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($setting->value) }}" alt="" style="max-height:60px;" class="border rounded p-1">
                                    </div>
                                    @endif
                                    <input type="file" name="{{ $fieldName }}" class="form-control-file" accept="image/*">
                                @elseif($setting->type === 'select')
                                    @if($setting->key === 'mail.driver')
                                        <select name="{{ $fieldName }}" class="form-control">
                                            <option value="log" {{ $setting->value === 'log' ? 'selected' : '' }}>Log (Development)</option>
                                            <option value="smtp" {{ $setting->value === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                            <option value="sendmail" {{ $setting->value === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        </select>
                                    @elseif($setting->key === 'mail.encryption')
                                        <select name="{{ $fieldName }}" class="form-control">
                                            <option value="tls" {{ $setting->value === 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ $setting->value === 'ssl' ? 'selected' : '' }}>SSL</option>
                                            <option value="" {{ empty($setting->value) ? 'selected' : '' }}>None</option>
                                        </select>
                                    @else
                                        <input type="text" name="{{ $fieldName }}" class="form-control" value="{{ $setting->value }}">
                                    @endif
                                @elseif($setting->key === 'mail.password')
                                    <input type="password" name="{{ $fieldName }}" class="form-control" value="{{ $setting->value }}" autocomplete="off">
                                @else
                                    <input type="text" name="{{ $fieldName }}" class="form-control" value="{{ $setting->value }}">
                                @endif
                            </div>
                        </div>
                        @endforeach
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
        font-family: monospace;
        font-size: 13px;
        line-height: 1.5;
        min-height: 300px;
    }
</style>
@endpush

@push('scripts')
{{-- TinyMCE CDN for rich text editing --}}
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '.richtext-editor',
            height: 400,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
                'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 14px; }',
            promotion: false,
            branding: false,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    }
</script>
@endpush
@endsection
