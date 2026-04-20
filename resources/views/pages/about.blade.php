@extends('layouts.app')

@section('meta_title', 'About Us - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', ['title' => 'About Us'])

    <div class="container about-section">
        <div class="row">
            <div class="col-lg-10 mx-auto py-4">
                @if($page)
                    <h2 class="subtitle">{{ $page->title }}</h2>

                    @if($page->image)
                        <div class="mb-4">
                            <img src="{{ Storage::url($page->image) }}" alt="{{ $page->title }}" class="img-fluid rounded">
                        </div>
                    @endif

                    <div class="about-content">
                        {!! $page->content !!}
                    </div>
                @else
                    <h2 class="subtitle">About Us</h2>
                    <p>We are a team of passionate individuals dedicated to bringing you the best online shopping experience.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
