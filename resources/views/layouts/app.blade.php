<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Head — Phase 9-A: Dynamic title, meta, OG, Twitter, JSON-LD, GA --}}
    <x-seo.head />
    <meta name="author" content="{{ Setting::get('general.site_name', 'Porto Shop') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('themes/porto/images/icons/favicon.png') }}">

    {{-- Google Fonts --}}
    <script>
        WebFontConfig = {
            google: {
                families: ['Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700', 'Oswald:300,400,500,600,700,800', 'Playfair+Display:900', 'Shadows+Into+Light:400']
            }
        };
        (function(d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = '{{ asset("themes/porto/js/webfont.js") }}';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    {{-- Plugins CSS --}}
    <link rel="stylesheet" href="{{ asset('themes/porto/css/bootstrap.min.css') }}">

    {{-- Main CSS --}}
    @if(View::hasSection('is_home'))
        <link rel="stylesheet" href="{{ asset('themes/porto/css/demo1.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('themes/porto/css/style.min.css') }}">
    @endif

    {{-- Icons --}}
    <link rel="stylesheet" href="{{ asset('themes/porto/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/porto/vendor/simple-line-icons/css/simple-line-icons.min.css') }}">

    {{-- Livewire Styles --}}
    @livewireStyles

    @stack('styles')
</head>

<body>
    <div class="page-wrapper">
        {{-- Top Notice / Promo Bar --}}
        @include('partials.top-notice')

        {{-- Header --}}
        @include('partials.header')

        {{-- Main Content --}}
        <main class="main @yield('main-class')">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('partials.footer')
    </div><!-- End .page-wrapper -->

    {{-- Loading Overlay --}}
    <div class="loading-overlay">
        <div class="bounce-loader">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div class="mobile-menu-overlay"></div>
    @include('partials.mobile-menu')

    {{-- Sticky Navbar (Mobile Bottom) --}}
    @include('partials.sticky-navbar')

    {{-- Scroll Top --}}
    <a id="scroll-top" href="#top" title="Top" role="button" class="btn-scroll"><i class="icon-angle-up"></i></a>

    {{-- Core JS --}}
    <script src="{{ asset('themes/porto/js/jquery.min.js') }}"></script>
    <script src="{{ asset('themes/porto/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('themes/porto/js/optional/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('themes/porto/js/plugins.min.js') }}"></script>
    <script src="{{ asset('themes/porto/js/jquery.appear.min.js') }}"></script>

    {{-- Main JS --}}
    <script src="{{ asset('themes/porto/js/main.min.js') }}"></script>

    {{-- Livewire Scripts --}}
    @livewireScripts

    @stack('scripts')
</body>
</html>
