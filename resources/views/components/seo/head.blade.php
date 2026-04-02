{{-- SEO Head Component — Phase 9-A / 2-G --}}
@php
    $seo = app(\App\Services\SeoService::class);
@endphp

<title>{{ $seo->getTitle() }}</title>
<meta name="description" content="{{ $seo->getDescription() }}">
<link rel="canonical" href="{{ $seo->getCanonical() }}">

@if($seo->isNoIndex())
    <meta name="robots" content="noindex, nofollow">
@endif

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $seo->getTitle() }}">
<meta property="og:description" content="{{ $seo->getDescription() }}">
<meta property="og:url" content="{{ $seo->getCanonical() }}">
<meta property="og:site_name" content="{{ $seo->getSiteName() }}">
@if($seo->getImage())
    <meta property="og:image" content="{{ $seo->getImage() }}">
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo->getTitle() }}">
<meta name="twitter:description" content="{{ $seo->getDescription() }}">
@if($seo->getImage())
    <meta name="twitter:image" content="{{ $seo->getImage() }}">
@endif

{{-- JSON-LD Structured Data --}}
@if($seo->getJsonLd())
    <script type="application/ld+json">{!! $seo->getJsonLd() !!}</script>
@endif

{{-- Google Analytics (from settings) --}}
@php $gaId = \App\Models\Setting::get('seo.google_analytics_id'); @endphp
@if($gaId)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');
    </script>
@endif

{{-- Google Search Console Verification --}}
@php $gscMeta = \App\Models\Setting::get('seo.google_verification'); @endphp
@if($gscMeta)
    <meta name="google-site-verification" content="{{ $gscMeta }}">
@endif
