@extends('layouts.admin')
@section('title', 'Settings')
@section('breadcrumb')<li class="active">Settings</li>@endsection

@section('admin-content')
<h4 class="mb-3">Site Settings</h4>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf

    {{-- Tabs Navigation --}}
    <ul class="nav nav-tabs mb-0" role="tablist">
        @php $groups = ['general','contact','appearance','seo','social','payment','shipping','mail','promo']; @endphp
        @foreach($groups as $i => $group)
        <li class="nav-item">
            <a class="nav-link {{ $i === 0 ? 'active' : '' }}" data-toggle="tab" href="#tab-{{ $group }}">{{ ucfirst($group) }}</a>
        </li>
        @endforeach
    </ul>

    <div class="admin-card" style="border-top-left-radius:0;">
        <div class="card-body">
            <div class="tab-content">
                @foreach($groups as $i => $group)
                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab-{{ $group }}">
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
                                    <input type="text" name="{{ $fieldName }}" class="form-control" value="{{ $setting->value }}">
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
@endsection
