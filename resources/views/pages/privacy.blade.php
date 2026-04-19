@extends('layouts.app')

@section('meta_title', 'Privacy Policy - ' . Setting::get('general.site_name', 'Porto Shop'))
@section('meta_description', 'Read our privacy policy to understand how we collect, use, and protect your personal data.')

@section('content')
    @include('partials.breadcrumb', ['title' => 'Privacy Policy', 'items' => [['label' => 'Home', 'url' => url('/')]]])

    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto py-4">
                <div class="mb-4">
                    @php
                        $updatedAt = Setting::get('legal.privacy_updated_at');
                    @endphp
                    @if($updatedAt)
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="fas fa-clock mr-1"></i> Last updated: {{ \Carbon\Carbon::parse($updatedAt)->format('F d, Y') }}
                        </p>
                    @endif
                </div>

                <div class="legal-content">
                    {!! Setting::get('legal.privacy_content', '<p>Privacy Policy content has not been configured yet. Please contact the administrator.</p>') !!}
                </div>

                <div class="mt-4 pt-3 border-top">
                    <p class="text-muted" style="font-size: 13px;">
                        If you have any questions about this Privacy Policy, please
                        <a href="{{ route('contact') }}" class="text-primary">contact us</a>.
                    </p>
                    <a href="{{ route('terms') }}" class="text-primary">
                        <i class="fas fa-arrow-right mr-1"></i> Read our Terms & Conditions
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
