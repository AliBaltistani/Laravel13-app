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

    {{-- Favicon (dynamic from admin settings) --}}
    @if(Setting::get('appearance.favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . Setting::get('appearance.favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('themes/porto/images/icons/favicon.png') }}">
    @endif

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

    {{-- Main CSS (Demo8 site-wide) --}}
    <link rel="stylesheet" href="{{ asset('themes/porto/css/demo8.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/porto/css/animate.min.css') }}">

    {{-- Icons --}}
    <link rel="stylesheet" href="{{ asset('themes/porto/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/porto/vendor/simple-line-icons/css/simple-line-icons.min.css') }}">

    {{-- Livewire Styles --}}
    @livewireStyles

    @stack('styles')

    {{-- Appearance: Dynamic Colors from Admin Settings --}}
    @php
        $clr = [
            'primary'         => Setting::get('appearance.primary_color', '#08C'),
            'secondary'       => Setting::get('appearance.secondary_color', '#e7e7e7'),
            'bodyBg'          => Setting::get('appearance.body_bg_color', '#ffffff'),
            'bodyText'        => Setting::get('appearance.body_text_color', '#777777'),
            'heading'         => Setting::get('appearance.heading_color', '#313131'),
            'link'            => Setting::get('appearance.link_color', '#08C'),
            'headerTopBg'     => Setting::get('appearance.header_top_bg', '#f4f4f4'),
            'headerTopText'   => Setting::get('appearance.header_top_text', '#777777'),
            'headerBg'        => Setting::get('appearance.header_bg', '#ffffff'),
            'navBg'           => Setting::get('appearance.nav_bg', '#08C'),
            'navText'         => Setting::get('appearance.nav_text_color', '#ffffff'),
            'navHover'        => Setting::get('appearance.nav_hover_color', '#ffffff'),
            'footerBg'        => Setting::get('appearance.footer_bg', '#222529'),
            'footerText'      => Setting::get('appearance.footer_text_color', '#aaaaaa'),
            'footerHeading'   => Setting::get('appearance.footer_heading_color', '#ffffff'),
            'footerLink'      => Setting::get('appearance.footer_link_color', '#aaaaaa'),
            'footerBottomBg'  => Setting::get('appearance.footer_bottom_bg', '#1c1e22'),
            'promoBg'         => Setting::get('appearance.promo_bar_bg', '#08C'),
            'promoText'       => Setting::get('appearance.promo_bar_text', '#ffffff'),
            'btnPrimaryBg'    => Setting::get('appearance.btn_primary_bg', '#08C'),
            'btnPrimaryText'  => Setting::get('appearance.btn_primary_text', '#ffffff'),
            'salePrice'       => Setting::get('appearance.sale_price_color', '#e92e05'),
            'saleBadgeBg'     => Setting::get('appearance.sale_badge_bg', '#e92e05'),
        ];
    @endphp
    <style>
        :root {
            --porto-primary: {{ $clr['primary'] }};
            --porto-secondary: {{ $clr['secondary'] }};
            --porto-body-bg: {{ $clr['bodyBg'] }};
            --porto-body-text: {{ $clr['bodyText'] }};
            --porto-heading: {{ $clr['heading'] }};
            --porto-link: {{ $clr['link'] }};
            --porto-header-top-bg: {{ $clr['headerTopBg'] }};
            --porto-header-top-text: {{ $clr['headerTopText'] }};
            --porto-header-bg: {{ $clr['headerBg'] }};
            --porto-nav-bg: {{ $clr['navBg'] }};
            --porto-nav-text: {{ $clr['navText'] }};
            --porto-nav-hover: {{ $clr['navHover'] }};
            --porto-footer-bg: {{ $clr['footerBg'] }};
            --porto-footer-text: {{ $clr['footerText'] }};
            --porto-footer-heading: {{ $clr['footerHeading'] }};
            --porto-footer-link: {{ $clr['footerLink'] }};
            --porto-footer-bottom-bg: {{ $clr['footerBottomBg'] }};
            --porto-promo-bg: {{ $clr['promoBg'] }};
            --porto-promo-text: {{ $clr['promoText'] }};
            --porto-btn-primary-bg: {{ $clr['btnPrimaryBg'] }};
            --porto-btn-primary-text: {{ $clr['btnPrimaryText'] }};
            --porto-sale-price: {{ $clr['salePrice'] }};
            --porto-sale-badge-bg: {{ $clr['saleBadgeBg'] }};
        }

        /* === Body === */
        body { background-color: var(--porto-body-bg); color: var(--porto-body-text); }
        h1, h2, h3, h4, h5, h6 { color: var(--porto-heading); }
        a { color: var(--porto-link); }
        a:hover, a:focus { color: var(--porto-primary); }

        /* === Header Top Bar === */
        .header-top { background: var(--porto-header-top-bg) !important; border-bottom: 1px solid var(--porto-secondary); }
        .header-top, .header-top a, .header-top .wel-msg { color: var(--porto-header-top-text); }
        .header-top a:hover { color: var(--porto-primary); }

        /* === Header Middle === */
        .header-middle { background: var(--porto-header-bg); }

        /* === Header Bottom / Nav Bar === */
        .header-bottom { background: var(--porto-nav-bg) !important; }
        .header-bottom .menu > li > a { color: var(--porto-nav-text); }
        .header-bottom .menu > li:hover > a,
        .header-bottom .menu > li.active > a { color: var(--porto-nav-hover); }

        /* === Promo / Top-Notice Bar === */
        .pre-header, .pre-header > div, .top-notice { background: var(--porto-promo-bg) !important; color: var(--porto-promo-text) !important; }
        .pre-header a, .top-notice a { color: var(--porto-promo-text) !important; text-decoration: underline; }

        /* === Buttons === */
        .btn-primary, .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--porto-btn-primary-bg) !important;
            border-color: var(--porto-btn-primary-bg) !important;
            color: var(--porto-btn-primary-text) !important;
        }
        .btn-primary:hover { filter: brightness(1.08); }

        /* === Product & Price === */
        .price-box .product-price, .price-box .new-price { color: var(--porto-primary); }
        .price-box .old-price { color: #999; }
        .product-default .product-price { color: var(--porto-primary); }
        .product-label.label-sale { background: var(--porto-sale-badge-bg); color: #fff; }
        .product-default .btn-icon:hover,
        .product-default .btn-quickview:hover { background: var(--porto-primary); }

        /* === Accents & Misc === */
        .widget-title::after,
        .section-title::after { background: var(--porto-primary); }
        .nav-tabs .nav-link.active { border-bottom-color: var(--porto-primary); color: var(--porto-primary); }
        .widget-newsletter .btn { background: var(--porto-primary); border-color: var(--porto-primary); color: #fff; }
        .social-icon:hover { background: var(--porto-primary); border-color: var(--porto-primary); color: #fff; }

        /* === Footer === */
        .footer .footer-middle { background: var(--porto-footer-bg); color: var(--porto-footer-text); }
        .footer .widget-title { color: var(--porto-footer-heading) !important; }
        .footer .footer-middle a, .footer .links a { color: var(--porto-footer-link); }
        .footer .footer-middle a:hover, .footer .links a:hover { color: var(--porto-primary); }
        .footer .footer-bottom { background: var(--porto-footer-bottom-bg); border-top: 1px solid rgba(255,255,255,0.06); }
        .footer .footer-copyright { color: var(--porto-footer-text); }
        .footer .contact-info-label { color: var(--porto-footer-heading); }

        /* === Live Search Dropdown === */
        #ls-float-box { border-top-color: var(--porto-primary); }
        .ls-now { color: var(--porto-primary); }
        .ls-loading { color: var(--porto-primary); }
        .ls-spinner { border-top-color: var(--porto-primary); }
        .ls-viewall { color: var(--porto-primary); }
        .ls-row:hover .ls-title { color: var(--porto-primary); }
    </style>

    {{-- Live Search Dropdown + Product Card Hover CSS --}}
    <style>
        #ls-float-box {
            display: none; position: fixed; background: #fff;
            border: 1px solid #ddd; border-top: 3px solid var(--porto-primary);
            border-radius: 0 0 8px 8px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
            z-index: 100000; max-height: 420px; overflow-y: auto;
            min-width: 320px;
        }
        #ls-float-box.active { display: block; animation: lsFadeIn 0.2s ease-out; }
        @keyframes lsFadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .ls-loading {
            display: flex; align-items: center; justify-content: center;
            padding: 24px; gap: 10px; color: var(--porto-primary);
        }
        .ls-spinner {
            width: 20px; height: 20px; border: 3px solid #e0e0e0;
            border-top-color: var(--porto-primary); border-radius: 50%;
            animation: lsSpin 0.7s linear infinite;
        }
        @keyframes lsSpin { to { transform: rotate(360deg); } }
        .ls-row {
            display: flex; flex-direction: row; align-items: center;
            padding: 10px 16px; text-decoration: none !important;
            color: #333; border-bottom: 1px solid #f2f2f2;
            gap: 12px; transition: background 0.15s; cursor: pointer;
        }
        .ls-row:hover { background: #f0f7ff; text-decoration: none !important; }
        .ls-row:last-of-type { border-bottom: none; }
        .ls-row img {
            width: 48px !important; height: 48px !important; min-width: 48px;
            object-fit: cover; border-radius: 6px; border: 1px solid #eee;
            flex-shrink: 0; background: #f9f9f9;
        }
        .ls-detail { flex: 1; display: flex; flex-direction: column; min-width: 0; overflow: hidden; }
        .ls-title {
            font-size: 13px; font-weight: 600; color: #333;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .ls-row:hover .ls-title { color: var(--porto-primary); }
        .ls-catname { font-size: 11px; color: #999; margin-top: 1px; }
        .ls-prices {
            text-align: right; flex-shrink: 0; white-space: nowrap;
            display: flex; flex-direction: column; align-items: flex-end;
        }
        .ls-now { font-size: 14px; font-weight: 700; color: var(--porto-primary); }
        .ls-was { font-size: 11px; color: #bbb; text-decoration: line-through; font-weight: 400; }
        .ls-viewall {
            display: block; text-align: center; padding: 11px;
            color: var(--porto-primary); font-weight: 600; font-size: 13px;
            text-decoration: none !important;
            border-top: 1px solid #eee; background: #fafbfc;
            border-radius: 0 0 8px 8px; transition: background 0.15s;
        }
        .ls-viewall:hover { background: #e8f4fd; color: #0066a2; }
        .ls-empty {
            padding: 24px 16px; text-align: center; color: #999;
            display: flex; flex-direction: column; align-items: center; gap: 6px;
        }
        .ls-empty i { font-size: 26px; color: #ddd; }
        .ls-empty span { font-size: 13px; }
        /* Product Card Hover Fix for Carousels */
        .product-default.inner-icon:hover figure .btn-icon-group,
        .product-default.inner-icon:hover figure .btn-icon {
            visibility: visible !important; opacity: 1 !important;
        }
        .product-default.inner-quickview:hover figure .btn-quickview {
            visibility: visible !important; opacity: 0.85 !important;
        }
        .owl-item .product-default.inner-icon:hover figure .btn-icon-group,
        .owl-item .product-default.inner-icon:hover figure .btn-icon {
            visibility: visible !important; opacity: 1 !important;
        }
        .owl-item .product-default.inner-quickview:hover figure .btn-quickview {
            visibility: visible !important; opacity: 0.85 !important;
        }

        /* Fix: New Arrivals sidebar product widgets overlapping */
        .sidebar-home .product-col .product-default,
        .widget-products .product-col .product-default {
            margin-bottom: 2px !important;
            position: relative !important;
            display: flex !important;
            clear: both !important;
        }
        .sidebar-home .product-col,
        .widget-products .product-col {
            display: flex !important;
            flex-direction: column !important;
        }
        .product-col .product-default.left-details figure {
            flex-shrink: 0;
        }

        /* Fix: Banners appear-animate not triggering — force visible */
        .banners-container .banner.appear-animate,
        .banners-container.appear-animate {
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
        }
        .banners-container .banner {
            min-height: 120px;
            background: #f5f6f8;
            border-radius: 4px;
            overflow: hidden;
        }
        .banners-container .banner .banner-layer {
            position: relative;
            z-index: 2;
            padding: 15px;
        }
    </style>
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

    {{-- Global Toast Notifications (listens for 'notify' event from Livewire components) --}}
    <style>
        .porto-toast-container { position: fixed; top: 20px; right: 20px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; }
        .porto-toast { padding: 14px 24px; border-radius: 6px; color: #fff; font-size: 14px; font-family: 'Open Sans', sans-serif; box-shadow: 0 4px 20px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 10px; animation: portoSlideIn 0.3s ease-out; min-width: 280px; max-width: 400px; }
        .porto-toast.success { background: #2ecc71; }
        .porto-toast.error { background: #e74c3c; }
        .porto-toast.info { background: #3498db; }
        .porto-toast.warning { background: #f39c12; }
        .porto-toast i { font-size: 18px; }
        .porto-toast-close { margin-left: auto; cursor: pointer; opacity: 0.8; background: none; border: none; color: #fff; font-size: 18px; padding: 0; }
        .porto-toast-close:hover { opacity: 1; }
        @keyframes portoSlideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes portoSlideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
    </style>
    <div class="porto-toast-container" id="porto-toast-container"></div>
    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('notify', (data) => {
                const container = document.getElementById('porto-toast-container');
                const toast = document.createElement('div');
                const type = (data[0] && data[0].type) || data.type || 'success';
                const message = (data[0] && data[0].message) || data.message || '';
                const icons = { success: 'fas fa-check-circle', error: 'fas fa-exclamation-circle', info: 'fas fa-info-circle', warning: 'fas fa-exclamation-triangle' };
                toast.className = 'porto-toast ' + type;
                toast.innerHTML = '<i class="' + (icons[type] || icons.success) + '"></i><span>' + message + '</span><button class="porto-toast-close" onclick="this.parentElement.style.animation=\'portoSlideOut 0.3s ease-in forwards\';setTimeout(()=>this.parentElement.remove(),300);">&times;</button>';
                container.appendChild(toast);
                setTimeout(() => { if (toast.parentElement) { toast.style.animation = 'portoSlideOut 0.3s ease-in forwards'; setTimeout(() => toast.remove(), 300); } }, 3500);
            });
        });
    </script>

    {{-- Live Search Dropdown JS (must be in layout, not in Livewire @push) --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Create floating dropdown appended to <body>
        var box = document.createElement('div');
        box.id = 'ls-float-box';
        document.body.appendChild(box);

        function positionBox() {
            var wrapper = document.querySelector('.header-search-wrapper');
            if (!wrapper || !box.classList.contains('active')) return;
            var r = wrapper.getBoundingClientRect();
            box.style.top  = r.bottom + 'px';
            box.style.left = r.left + 'px';
            box.style.width = r.width + 'px';
        }

        // Listen for Livewire search result events
        if (typeof Livewire !== 'undefined') {
            Livewire.on('liveSearchResults', function (payload) {
                var data = Array.isArray(payload) ? payload[0] : payload;
                var results = data.results || [];
                var query   = data.query || '';
                var loading = data.loading || false;

                if (loading) {
                    box.innerHTML = '<div class="ls-loading"><div class="ls-spinner"></div><span>Searching...</span></div>';
                    box.classList.add('active');
                    positionBox();
                    return;
                }

                if (!query || query.length < 2) {
                    box.classList.remove('active');
                    box.innerHTML = '';
                    return;
                }

                var html = '';
                if (results.length > 0) {
                    for (var i = 0; i < results.length; i++) {
                        var item = results[i];
                        var priceHtml = '';
                        if (item.compare_price && item.compare_price > item.price) {
                            priceHtml += '<span class="ls-was">' + item.compare_price_fmt + '</span>';
                        }
                        priceHtml += '<span class="ls-now">' + item.price_fmt + '</span>';
                        html += '<a href="' + item.url + '" class="ls-row">'
                            + '<img src="' + item.image + '" alt="' + item.name + '">'
                            + '<div class="ls-detail">'
                            + '<span class="ls-title">' + item.name + '</span>'
                            + (item.category ? '<span class="ls-catname">in ' + item.category + '</span>' : '')
                            + '</div>'
                            + '<div class="ls-prices">' + priceHtml + '</div>'
                            + '</a>';
                    }
                    html += '<a href="/shop/search?q=' + encodeURIComponent(query) + '" class="ls-viewall">View All Results &rarr;</a>';
                } else {
                    html = '<div class="ls-empty"><i class="fas fa-search"></i><span>No results for &ldquo;<strong>' + query + '</strong>&rdquo;</span></div>';
                }

                box.innerHTML = html;
                box.classList.add('active');
                positionBox();
            });
        }

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (!box.contains(e.target) && !e.target.closest('.header-search-wrapper')) {
                box.classList.remove('active');
            }
        });

        window.addEventListener('scroll', positionBox, true);
        window.addEventListener('resize', positionBox);
    });
    </script>

    @livewire('newsletter-popup')

    @stack('scripts')
</body>
</html>
