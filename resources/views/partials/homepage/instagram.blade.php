@php $data = $sectionData[$section->key] ?? []; $images = $data['images'] ?? collect(); $s = $section->settings ?? []; @endphp
@if($images->count())
<div class="instagram-section appear-animate">
    <h3 class="subtitle text-uppercase">{{ $s['section_title'] ?? 'Follow On Instagram' }}</h3>
    <div class="heading-spacer"></div>
    <div class="owl-carousel owl-theme instagram-feed-carousel instagram-feed-list">
        @foreach($images as $instaImg)
            <a href="{{ $instaImg->button_url ?? '#' }}">
                @if($instaImg->image)
                <img src="{{ asset('storage/' . $instaImg->image) }}" width="197" height="150" alt="{{ $instaImg->title ?? 'Instagram Feed' }}">
                @endif
            </a>
        @endforeach
    </div>
</div>
@endif
