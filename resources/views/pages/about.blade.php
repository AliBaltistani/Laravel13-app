@extends('layouts.app')

@section('meta_title', 'About Us - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', ['title' => 'About Us'])

    <div class="container about-section">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h2 class="subtitle">{{ Setting::get('about.heading', 'About Us') }}</h2>
                <p>{{ Setting::get('about.description', 'We are a team of passionate individuals dedicated to bringing you the best online shopping experience.') }}</p>
            </div>
        </div>
    </div>
@endsection
