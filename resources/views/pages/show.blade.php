@extends('layouts.app')

@section('content')
{{-- Banner --}}
@if($page->banner_image)
<div class="page-banner" style="position:relative;overflow:hidden;max-height:350px;">
    <img src="{{ Storage::url($page->banner_image) }}" alt="{{ $page->title }}" class="w-100" style="object-fit:cover;max-height:350px;">
    <div style="position:absolute;bottom:0;left:0;right:0;padding:30px;background:linear-gradient(transparent,rgba(0,0,0,0.6));">
        <div class="container"><h1 class="text-white mb-0">{{ $page->title }}</h1></div>
    </div>
</div>
@endif

<div class="{{ $page->layout === 'full-width' ? 'container-fluid' : 'container' }}">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Main Content --}}
        <div class="{{ $page->show_sidebar ? 'col-lg-9' : 'col-12' }}">
            @if(!$page->banner_image)
            <h1>{{ $page->title }}</h1>
            @endif

            @if($page->excerpt)
            <p class="lead text-muted">{{ $page->excerpt }}</p>
            @endif

            {{-- Video --}}
            @if($page->video_url)
            <div class="mb-4">
                @php
                    $videoId = null;
                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $page->video_url, $m)) $videoId = $m[1];
                @endphp
                @if($videoId)
                <div class="ratio ratio-16x9" style="max-width:800px;">
                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen style="border-radius:8px;"></iframe>
                </div>
                @else
                <video controls class="w-100" style="border-radius:8px;max-width:800px;">
                    <source src="{{ $page->video_url }}" type="video/mp4">
                </video>
                @endif
            </div>
            @elseif($page->video_file)
            <div class="mb-4">
                <video controls class="w-100" style="border-radius:8px;max-width:800px;">
                    <source src="{{ Storage::url($page->video_file) }}" type="video/mp4">
                </video>
            </div>
            @endif

            {{-- Main Content --}}
            @if($page->content)
            <div class="page-content">
                {!! $page->content !!}
            </div>
            @endif

            {{-- Content Sections --}}
            @if($page->activeSections->count())
            <div class="page-sections mt-4">
                @foreach($page->activeSections as $section)
                <div class="page-section {{ $section->css_class }}" @if($section->bg_color && $section->bg_color !== '#ffffff') style="background:{{ $section->bg_color }};padding:20px;border-radius:8px;margin-bottom:20px;" @else style="margin-bottom:20px;" @endif>
                    @if($section->title)
                    <h3>{{ $section->title }}</h3>
                    @endif

                    @switch($section->type)
                        @case('text')
                        @case('html')
                            {!! $section->content !!}
                            @break
                        @case('image')
                            @if($section->image)
                            <img src="{{ Storage::url($section->image) }}" alt="{{ $section->title }}" class="img-fluid rounded">
                            @endif
                            @if($section->content)<p class="mt-2">{!! $section->content !!}</p>@endif
                            @break
                        @case('video')
                            @if($section->video_url)
                            @php
                                $svId = null;
                                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $section->video_url, $sm)) $svId = $sm[1];
                            @endphp
                            @if($svId)
                            <div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/{{ $svId }}" frameborder="0" allowfullscreen style="border-radius:8px;"></iframe></div>
                            @else
                            <video controls class="w-100" style="border-radius:8px;"><source src="{{ $section->video_url }}" type="video/mp4"></video>
                            @endif
                            @endif
                            @break
                        @case('banner')
                            @if($section->image)
                            <div class="banner position-relative" style="border-radius:8px;overflow:hidden;">
                                <img src="{{ Storage::url($section->image) }}" alt="{{ $section->title }}" class="w-100">
                                @if($section->content)
                                <div class="banner-layer" style="position:absolute;bottom:20px;left:20px;right:20px;">
                                    {!! $section->content !!}
                                </div>
                                @endif
                            </div>
                            @endif
                            @break
                    @endswitch
                </div>
                @endforeach
            </div>
            @endif

            {{-- Gallery --}}
            @if($page->images->count())
            <div class="page-gallery mt-4">
                <h3>Gallery</h3>
                <div class="row">
                    @foreach($page->images as $img)
                    <div class="col-md-3 col-6 mb-3">
                        <a href="{{ Storage::url($img->image) }}" class="d-block" target="_blank">
                            <img src="{{ Storage::url($img->image) }}" alt="{{ $img->alt_text ?? $page->title }}" class="img-fluid rounded" style="height:160px;object-fit:cover;width:100%;">
                        </a>
                        @if($img->caption)<small class="text-muted">{{ $img->caption }}</small>@endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        @if($page->show_sidebar)
        <aside class="col-lg-3 sidebar-page">
            {!! $page->sidebar_content !!}
        </aside>
        @endif
    </div>
</div>

{{-- Per-page Custom CSS --}}
@if($page->custom_css)
@push('styles')
<style>{!! $page->custom_css !!}</style>
@endpush
@endif

{{-- Per-page Custom JS --}}
@if($page->custom_js)
@push('scripts')
<script>{!! $page->custom_js !!}</script>
@endpush
@endif
@endsection
