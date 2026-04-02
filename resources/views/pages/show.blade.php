@extends('layouts.app')

@section('meta_title', ($page->meta_title ?? $page->title) . ' - ' . Setting::get('general.site_name', 'Porto Shop'))
@section('meta_description', $page->meta_description ?? '')

@section('content')
    @include('partials.breadcrumb', ['title' => $page->title])

    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto py-4">
                {!! $page->content !!}
            </div>
        </div>
    </div>
@endsection
