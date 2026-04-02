@extends('layouts.app')

@section('meta_title', $title . ' - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', ['title' => $title])

    <div class="container py-5">
        <div class="text-center">
            <i class="fas fa-tools fa-3x text-muted mb-3 d-block"></i>
            <h2 class="mb-2">{{ $title }}</h2>
            <p class="text-muted">This page is under construction and will be available soon.</p>
            <a href="{{ url('/') }}" class="btn btn-primary mt-3">Back to Home</a>
        </div>
    </div>
@endsection
