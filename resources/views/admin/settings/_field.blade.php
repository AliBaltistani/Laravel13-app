{{-- Single settings field renderer --}}
@php $fieldName = str_replace('.', '__', $setting->key); @endphp
<div class="form-group row">
    <label class="col-md-3 col-form-label font-weight-bold">
        {{ $setting->label ?? $setting->key }}
        @if($setting->description)
        <br><small class="text-muted font-weight-normal">{{ $setting->description }}</small>
        @endif
    </label>
    <div class="col-md-9">
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
            <div class="d-flex align-items-center" style="gap: 10px;">
                <input type="color" name="{{ $fieldName }}" class="form-control" value="{{ $setting->value ?? '#0d6efd' }}" style="height:40px;width:60px;padding:2px;cursor:pointer;border-radius:6px;">
                <input type="text" class="form-control" value="{{ $setting->value ?? '#0d6efd' }}" style="width:110px;font-family:monospace;font-size:13px;" readonly
                       onclick="this.select()"
                       id="hex_{{ $fieldName }}">
                <div style="width:32px;height:32px;border-radius:6px;border:1px solid #ddd;background:{{ $setting->value ?? '#0d6efd' }};" id="preview_{{ $fieldName }}"></div>
            </div>
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
