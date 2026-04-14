@php $data = $sectionData[$section->key] ?? []; $s = $section->settings ?? []; @endphp
@if(!empty($data['html']))
<div class="{{ $s['css_class'] ?? '' }}">
    <div class="{{ $s['container_class'] ?? 'container' }}">
        {!! $data['html'] !!}
    </div>
</div>
@endif
