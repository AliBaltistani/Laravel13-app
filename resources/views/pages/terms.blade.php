@extends('layouts.app')

@section('meta_title', ($page->meta_title ?: 'Terms & Conditions') . ' - ' . Setting::get('general.site_name', 'Porto Shop'))
@section('meta_description', $page->meta_description ?: 'Read our terms and conditions before using our website and services.')

@section('content')
    @include('partials.breadcrumb', ['title' => $page->title, 'items' => [['label' => 'Home', 'url' => url('/')]]])

    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto py-4">
                <div class="legal-content">
                    {!! $page->content !!}
                </div>

                <div class="mt-4 pt-3 border-top">
                    <p class="text-muted" style="font-size: 13px;">
                        If you have any questions about these Terms & Conditions, please
                        <a href="{{ route('contact') }}" class="text-primary">contact us</a>.
                    </p>
                    <a href="{{ route('privacy') }}" class="text-primary">
                        <i class="fas fa-arrow-right mr-1"></i> Read our Privacy Policy
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
