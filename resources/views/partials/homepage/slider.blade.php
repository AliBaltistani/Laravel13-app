@php $data = $sectionData[$section->key] ?? []; $slider = $data['slider'] ?? null; @endphp
@if($slider && $slider->activeSlides->count())
<div class="{{ $section->getSetting('container_class', 'container') }}">
    <div class="home-slider-container">
        <div class="home-slider owl-carousel owl-theme owl-theme-light nav-outer show-nav-hover slide-animate" data-owl-options="{
                'navText': [ '<i class=icon-left-open-big>', '<i class=icon-right-open-big>' ]
                }">
            @foreach($slider->activeSlides as $index => $slide)
            <div class="home-slide {{ $index % 2 !== 0 ? 'home-slide-left' : '' }}">
                <figure style="background-color: {{ $slide->text_color === 'light' ? '#d7b697' : '#ceb49d' }};">
                    @if($slide->image_desktop)
                        <img class="slide-bg" src="{{ asset('storage/' . $slide->image_desktop) }}" width="1180" height="569" alt="{{ $slide->title }}" />
                    @endif
                </figure>
                <div class="home-slide-content {{ $index % 2 !== 0 ? 'slide-content-dark' : '' }} sale-banner" style="display: none; background-color: {{ $slide->text_color === 'light' ? '#d7b697' : '#ceb49d' }};" >
                    <div class="row no-gutter {{ $index % 2 !== 0 ? 'bg-secondary' : 'bg-primary' }} appear-animate" data-animation-name="{{ $index % 2 !== 0 ? 'fadeInRightShorter' : 'fadeInLeftShorter' }}">
                        <div class="col-auto col-md-6 d-flex flex-column justify-content-center col-1">
                            @if($slide->subtitle)<h4 class="align-left text-uppercase mb-0 appear-animate" data-animation-name="slideInRight" data-animation-delay="400">{{ $slide->subtitle }}</h4>@endif
                            @if($slide->title)<h3 class="text-white mb-0 align-left text-uppercase appear-animate" data-animation-name="slideInRight" data-animation-delay="400">{{ $slide->title }}</h3>@endif
                        </div>
                        <div class="col-auto col-md-6 col-2 appear-animate" data-animation-name="slideInLeft" data-animation-delay="400">
                            @if($slide->description)<h2 class="text-white mb-0 position-relative align-left">{!! $slide->description !!}</h2>@endif
                        </div>
                    </div>
                    @if($slide->button_text)
                    <div class="mb-0 {{ $index % 2 !== 0 ? '' : 'text-right' }}">
                        <a href="{{ $slide->button_url ?? url('/shop') }}" class="btn btn-lg {{ $index % 2 !== 0 ? 'btn-primary' : 'btn-dark' }} appear-animate" data-animation-name="fadeInUpShorter" data-animation-delay="600">{{ $slide->button_text }}</a>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
