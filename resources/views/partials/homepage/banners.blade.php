@php $data = $sectionData[$section->key] ?? []; $banners = $data['banners'] ?? collect(); $s = $section->settings ?? []; @endphp
@if($banners->count())
<div class="{{ $s['container_class'] ?? 'container' }} banner-container">
    <div class="row justify-content-center">
        @foreach($banners as $index => $banner)
        <div class="{{ !empty($s['col_class']) ? $s['col_class'] : 'col-md-4 col-sm-6' }} mt-3 mt-md-0 appear-animate"
            @if($index === 0) data-animation-name="fadeInRightShorter" @elseif($index === 2) data-animation-name="fadeInLeftShorter" @endif
            data-animation-duration="1500">
            @if($banner->title)
            <h3 class="subtitle">{{ strtoupper($banner->title) }}</h3>
            <div class="heading-spacer"></div>
            @endif
            <div class="banner banner-image">
                <a href="{{ $banner->button_url ?? url('/shop') }}">
                    @if($banner->image)
                    <img src="{{ asset('storage/' . $banner->image) }}" style="background-color: #e4ddd7;" width="360" height="270" alt="{{ $banner->title }}" />
                    @endif
                </a>
                <div class="banner-meta">
                    <a href="{{ $banner->button_url ?? url('/shop') }}">{{ strtoupper($banner->title ?? '') }}</a>
                    @if($banner->subtitle)<span class="banner-price">{{ $banner->subtitle }}</span>@endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
