@php $data = $sectionData[$section->key] ?? []; $brands = $data['brands'] ?? collect(); $s = $section->settings ?? []; @endphp
@if($brands->count())
<div class="{{ $s['container_class'] ?? 'container' }}">
    <div class="brands-section appear-animate" data-animation-delay="200" data-animation-name="fadeIn" data-animation-duration="1000">
        <div class="brands-slider images-center owl-carousel owl-theme nav-outer show-nav-hover" data-owl-options="{
            'margin': 0,
            'nav': true
        }">
            @foreach($brands as $brand)
                @if($brand->logo)
                <a href="{{ url('/shop?brand=' . $brand->slug) }}">
                    <img src="{{ asset('storage/' . $brand->logo) }}" width="140" height="60" alt="{{ $brand->name }}">
                </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif
