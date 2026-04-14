{{--
    Reusable banner zone partial.
    Usage: @include('partials.banner-zone', ['position' => 'shop-top', 'limit' => 3, 'colClass' => 'col-md-4'])
--}}
@php
    $zoneBanners = \App\Models\Banner::active()
        ->position($position)
        ->orderBy('sort_order')
        ->take($limit ?? 5)
        ->get();
@endphp
@if($zoneBanners->count())
<div class="banner-zone banner-zone-{{ $position }}">
    @if($zoneBanners->count() === 1)
        {{-- Single banner: full width --}}
        @php $banner = $zoneBanners->first(); @endphp
        <div class="banner banner-image mb-3">
            <a href="{{ $banner->button_url ?? '#' }}">
                @if($banner->image)
                <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title ?? '' }}" class="w-100" style="border-radius:4px;">
                @endif
            </a>
            @if($banner->title || $banner->button_text)
            <div class="banner-layer" style="position:absolute;bottom:15px;left:20px;">
                @if($banner->title)<h4 class="text-white mb-1" style="text-shadow:1px 1px 3px rgba(0,0,0,0.5);">{{ $banner->title }}</h4>@endif
                @if($banner->subtitle)<p class="text-white mb-1" style="text-shadow:1px 1px 2px rgba(0,0,0,0.4);">{{ $banner->subtitle }}</p>@endif
                @if($banner->button_text)<a href="{{ $banner->button_url ?? '#' }}" class="btn btn-sm btn-primary">{{ $banner->button_text }}</a>@endif
            </div>
            @endif
        </div>
    @else
        {{-- Multiple banners: grid --}}
        <div class="row mb-3">
            @foreach($zoneBanners as $banner)
            <div class="{{ $colClass ?? 'col-md-4 col-sm-6' }} mb-3">
                <div class="banner banner-image" style="position:relative;">
                    <a href="{{ $banner->button_url ?? '#' }}">
                        @if($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title ?? '' }}" class="w-100" style="border-radius:4px;">
                        @endif
                    </a>
                    @if($banner->title)
                    <div class="banner-layer" style="position:absolute;bottom:10px;left:15px;">
                        <h5 class="text-white mb-0" style="text-shadow:1px 1px 3px rgba(0,0,0,0.5);">{{ $banner->title }}</h5>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endif
