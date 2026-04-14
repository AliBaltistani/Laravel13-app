@php $s = $section->settings ?? []; @endphp
@if(!empty($s['banner_image']))
{{-- Image-based sale banner --}}
<div class="sale-banner banner appear-animate" data-animation-delay="100" data-animation-duration="1500">
    <a href="{{ $s['button_url'] ?? url('/shop') }}">
        <img src="{{ asset('storage/' . $s['banner_image']) }}" alt="{{ $s['title'] ?? 'Sale Banner' }}" class="w-100" style="max-height:400px;object-fit:cover;">
    </a>
    @if(!empty($s['title']) || !empty($s['button_text']))
    <div class="banner-layer" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;">
        @if(!empty($s['title']))
        <h2 class="text-white mb-2" style="text-shadow:1px 1px 4px rgba(0,0,0,0.5);">{{ $s['title'] }}</h2>
        @endif
        @if(!empty($s['subtitle']))
        <h4 class="text-white mb-3" style="text-shadow:1px 1px 3px rgba(0,0,0,0.4);">{{ $s['subtitle'] }}</h4>
        @endif
        @if(!empty($s['button_text']))
        <a href="{{ $s['button_url'] ?? url('/shop') }}" class="btn btn-lg btn-primary">{{ $s['button_text'] }}</a>
        @endif
    </div>
    @endif
</div>
@else
{{-- Text-based sale banner (default) --}}
<div class="sale-banner banner appear-animate" data-animation-delay="100" data-animation-duration="1500">
    <div class="container banner-content">
        <div class="row no-gutter {{ $s['bg_class'] ?? 'bg-secondary' }}">
            <div class="col-auto col-md-4 d-flex flex-column justify-content-center col-1">
                <h4 class="align-left text-uppercase mb-0">{{ $s['title'] ?? 'Furniture & Garden' }}</h4>
                <h3 class="text-white mb-0 align-left text-uppercase">{{ $s['subtitle'] ?? 'Huge Sale' }}</h3>
            </div>
            <div class="col-auto col-md-4 col-2">
                <h5 class="text-white mb-0 position-relative align-left">{{ $s['discount'] ?? '50' }}<small>%<ins>OFF</ins></small></h5>
            </div>
            <div class="mb-0 col-md-4 col-3 col-auto">
                <a href="{{ $s['button_url'] ?? url('/shop') }}" class="btn btn-lg btn-primary">{{ $s['button_text'] ?? 'Shop Now!' }}</a>
            </div>
        </div>
    </div>
</div>
@endif
