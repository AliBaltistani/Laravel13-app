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
                families: ['Inter:300,400,500,600,700,800', 'Playfair+Display:400,700,900', 'Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700']
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
            /* ═══ Porto Dynamic Variables (from admin) ═══ */
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

            /* ═══ DEVOGUE Design Tokens ═══ */
            /* Brand Colors */
            --dv-navy: #2B3674;
            --dv-navy-dark: #1B2559;
            --dv-navy-light: #3B4A8C;
            --dv-orange: #F5A623;
            --dv-orange-light: #FFB84D;
            --dv-orange-dark: #E09000;

            /* Typography */
            --font-serif: 'Playfair Display', Georgia, serif;
            --font-sans: 'Inter', 'Open Sans', system-ui, sans-serif;

            /* Neutrals */
            --ink-900: #1a1d2e;
            --ink-700: #2c3046;
            --ink-500: #6b7085;
            --ink-300: #b0b4c4;
            --ink-100: #f0f1f5;
            --ink-50:  #f8f9fc;
            --surface: #ffffff;

            /* Spacing */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2.5rem;
            --space-2xl: 4rem;
            --space-3xl: 6rem;

            /* Borders & Radius */
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-pill: 100px;

            /* Shadows */
            --shadow-card: 0 1px 3px rgba(43,54,116,.04), 0 4px 16px rgba(43,54,116,.06);
            --shadow-hover: 0 8px 28px rgba(43,54,116,.12);
            --shadow-modal: 0 20px 60px rgba(27,37,89,.16);

            /* Transitions */
            --ease-out: cubic-bezier(0.22, 1, 0.36, 1);
            --duration-fast: 150ms;
            --duration-base: 250ms;
            --duration-slow: 400ms;
        }

        /* ══════════════════════════════════════════════════
           1. GLOBAL BASE STYLES
           ══════════════════════════════════════════════════ */
        body {
            font-family: var(--font-sans) !important;
            background-color: var(--ink-50) !important;
            color: var(--ink-700);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        h1, h2, h3, h4, h5, h6 { color: var(--ink-900); font-family: var(--font-sans); }
        a { color: var(--dv-navy); transition: color var(--duration-fast); }
        a:hover, a:focus { color: var(--dv-orange); }

        .page-wrapper { background: var(--surface); }
        .main { background: var(--surface); }
        .container { max-width: 1200px; }

        /* ══════════════════════════════════════════════════
           2. PROMO / TOP-NOTICE BAR
           ══════════════════════════════════════════════════ */
        .pre-header, .pre-header > div, .top-notice {
            background: var(--dv-navy-dark) !important;
            color: rgba(255,255,255,.85) !important;
            font-family: var(--font-sans) !important;
            font-size: 11.5px !important;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 8px 0 !important;
        }
        .pre-header a, .top-notice a {
            color: var(--dv-orange-light) !important;
            text-decoration: underline !important;
            text-underline-offset: 2px;
        }
        .pre-header .mfp-close { color: rgba(255,255,255,.6) !important; }
        .pre-header .mfp-close:hover { color: #fff !important; }

        /* ══════════════════════════════════════════════════
           3. HEADER
           ══════════════════════════════════════════════════ */
        /* --- Header Top Bar --- */
        .header-top {
            background: var(--ink-50) !important;
            border-bottom: 1px solid rgba(43,54,116,.06) !important;
            font-family: var(--font-sans) !important;
            font-size: 11px;
            letter-spacing: .6px;
            padding: 6px 0 !important;
        }
        .header-top, .header-top a, .header-top .wel-msg {
            color: var(--ink-500) !important;
            font-weight: 500;
        }
        .header-top a:hover { color: var(--dv-navy) !important; }
        .header-top .header-dropdown > a { font-size: 11px; font-weight: 600; }
        .header-top .separator { background: rgba(43,54,116,.1); }
        .header-top .top-links a { font-weight: 500; }
        .header-top .share-links a { color: var(--ink-300) !important; font-size: 13px; }
        .header-top .share-links a:hover { color: var(--dv-navy) !important; }

        /* --- Header Middle --- */
        .header-middle {
            background: var(--surface) !important;
            padding: 18px 0 !important;
            border-bottom: 1px solid rgba(43,54,116,.06) !important;
        }
        .header-middle .logo img { height: 40px; width: auto; }
        .header-middle .header-contact i { color: var(--dv-navy); font-size: 20px; }
        .header-middle .header-contact h6 { font-size: 12px; color: var(--ink-500); }
        .header-middle .header-contact h6 a { color: var(--dv-navy) !important; font-weight: 700; }

        /* Icon buttons */
        .header-right .header-icon {
            position: relative;
            width: 38px; height: 38px;
            display: inline-flex !important; align-items: center; justify-content: center;
            border-radius: 50%;
            color: var(--ink-700) !important;
            transition: background var(--duration-fast), color var(--duration-fast);
            font-size: 18px;
        }
        .header-right .header-icon:hover {
            background: var(--ink-100);
            color: var(--dv-navy) !important;
        }
        .header-right .header-icon i { font-size: 20px; }

        /* Cart badge */
        .cart-count.badge-circle {
            background: var(--dv-orange) !important;
            color: #fff !important;
            font-size: 9px;
            min-width: 17px; height: 17px;
            line-height: 17px;
            font-weight: 700;
            letter-spacing: 0;
            border: 2px solid var(--surface);
        }

        /* --- Header Bottom / Navigation --- */
        .header-bottom {
            background: var(--dv-navy) !important;
            border-bottom: none !important;
            padding: 0 !important;
        }
        .header-bottom .menu > li > a {
            color: rgba(255,255,255,.85) !important;
            font-family: var(--font-sans) !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 14px 16px !important;
            position: relative;
            transition: color var(--duration-fast);
        }
        .header-bottom .menu > li > a::after {
            content: '';
            position: absolute;
            bottom: 0; left: 16px; right: 16px;
            height: 2.5px;
            background: var(--dv-orange);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform var(--duration-base) var(--ease-out);
            border-radius: 2px 2px 0 0;
        }
        .header-bottom .menu > li:hover > a,
        .header-bottom .menu > li.active > a {
            color: #fff !important;
        }
        .header-bottom .menu > li:hover > a::after,
        .header-bottom .menu > li.active > a::after {
            transform: scaleX(1);
        }
        .header-bottom .menu > li.float-right > a {
            color: var(--dv-orange) !important;
            font-weight: 700 !important;
        }

        /* Megamenu */
        .megamenu {
            border: none !important;
            border-top: 3px solid var(--dv-navy) !important;
            box-shadow: var(--shadow-modal) !important;
            border-radius: 0 0 var(--radius-md) var(--radius-md) !important;
            padding: var(--space-xl) !important;
            background: var(--surface) !important;
        }
        .megamenu .nolink {
            font-family: var(--font-sans) !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            letter-spacing: 1.5px !important;
            text-transform: uppercase !important;
            color: var(--dv-navy) !important;
            padding-bottom: 8px !important;
            border-bottom: 2px solid var(--dv-orange) !important;
            margin-bottom: 10px !important;
        }
        .megamenu .submenu li a {
            font-size: 13px !important;
            color: var(--ink-500) !important;
            padding: 5px 0 !important;
            transition: color var(--duration-fast), padding-left var(--duration-fast);
        }
        .megamenu .submenu li a:hover {
            color: var(--dv-navy) !important;
            padding-left: 4px !important;
        }

        /* ══════════════════════════════════════════════════
           4. BUTTONS
           ══════════════════════════════════════════════════ */
        .btn-primary, .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--dv-navy) !important;
            border-color: var(--dv-navy) !important;
            color: #fff !important;
            font-family: var(--font-sans);
            font-weight: 700;
            letter-spacing: .8px;
            border-radius: var(--radius-sm);
            transition: all var(--duration-fast) var(--ease-out);
        }
        .btn-primary:hover { background-color: var(--dv-navy-light) !important; border-color: var(--dv-navy-light) !important; }

        .btn-dark, .btn-dark:hover, .btn-dark:focus, .btn-dark:active {
            background-color: var(--dv-navy) !important;
            border-color: var(--dv-navy) !important;
            color: #fff !important;
            font-family: var(--font-sans);
            font-weight: 700;
            letter-spacing: .8px;
            border-radius: var(--radius-sm);
            transition: all var(--duration-fast) var(--ease-out);
        }
        .btn-dark:hover { background-color: var(--dv-navy-light) !important; border-color: var(--dv-navy-light) !important; }

        /* Luxury button system */
        .btn-luxury {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            height: 44px; padding: 0 24px;
            font-family: var(--font-sans); font-size: 11px; font-weight: 800;
            letter-spacing: 1.5px; text-transform: uppercase;
            border-radius: var(--radius-sm); border: 1.5px solid transparent;
            cursor: pointer; text-decoration: none !important;
            transition: all var(--duration-fast) var(--ease-out); white-space: nowrap;
        }
        .btn-luxury--filled {
            background: var(--dv-navy); border-color: var(--dv-navy); color: #fff !important;
        }
        .btn-luxury--filled:hover {
            background: var(--dv-navy-light); border-color: var(--dv-navy-light); color: #fff !important;
        }
        .btn-luxury--outline {
            background: transparent; border-color: var(--dv-navy);
            color: var(--dv-navy) !important;
        }
        .btn-luxury--outline:hover {
            background: var(--dv-navy); border-color: var(--dv-navy); color: #fff !important;
        }
        .btn-luxury--orange {
            background: var(--dv-orange); border-color: var(--dv-orange); color: #fff !important;
        }
        .btn-luxury--orange:hover {
            background: var(--dv-orange-dark); border-color: var(--dv-orange-dark); color: #fff !important;
        }

        /* ══════════════════════════════════════════════════
           5. BREADCRUMB & PAGE HEADER
           ══════════════════════════════════════════════════ */
        .page-header {
            background: var(--ink-50) !important;
            padding: 20px 0 !important;
            border-bottom: 1px solid rgba(43,54,116,.06);
        }
        .page-header h1 {
            font-family: var(--font-serif) !important;
            font-size: clamp(22px, 3vw, 30px) !important;
            font-weight: 700;
            color: var(--dv-navy) !important;
            letter-spacing: -0.3px;
            margin-top: 6px;
        }
        .breadcrumb-nav { padding: 0 !important; }
        .breadcrumb { background: none !important; padding: 0 !important; margin: 0 !important; gap: 4px; }
        .breadcrumb-item a {
            font-family: var(--font-sans);
            font-size: 11px; font-weight: 600; letter-spacing: .4px;
            color: var(--ink-300) !important; text-decoration: none;
            transition: color var(--duration-fast);
        }
        .breadcrumb-item a:hover { color: var(--dv-navy) !important; }
        .breadcrumb-item.active {
            font-size: 11px; color: var(--dv-navy) !important; font-weight: 600;
        }
        .breadcrumb-item + .breadcrumb-item::before { color: var(--ink-300) !important; }
        .breadcrumb-item a i.icon-home { color: var(--ink-300); font-size: 13px; }

        /* ══════════════════════════════════════════════════
           6. SECTION TITLES
           ══════════════════════════════════════════════════ */
        .section-title, .subtitle {
            font-family: var(--font-serif) !important;
            font-size: clamp(20px, 3vw, 28px) !important;
            font-weight: 700 !important;
            color: var(--dv-navy) !important;
            letter-spacing: -0.3px;
            line-height: 1.3;
        }
        .section-title::after {
            background: var(--dv-orange) !important;
            height: 3px !important;
            width: 40px !important;
        }
        .heading-spacer {
            width: 40px; height: 3px;
            background: var(--dv-orange);
            margin: 8px auto 24px;
            border-radius: 2px;
        }

        /* ══════════════════════════════════════════════════
           7. PRODUCT CARDS
           ══════════════════════════════════════════════════ */
        .product-default {
            background: var(--surface);
            border: 1px solid rgba(43,54,116,.05);
            border-radius: var(--radius-md) !important;
            overflow: hidden;
            transition: box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
        }
        .product-default:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }
        .product-default figure {
            overflow: hidden;
            background: var(--ink-50);
            border-radius: var(--radius-md) var(--radius-md) 0 0 !important;
            margin: 0 !important;
        }
        .product-default figure img {
            transition: transform var(--duration-slow) var(--ease-out) !important;
            width: 100%; object-fit: cover;
        }
        .product-default:hover figure img:first-child { transform: scale(1.05); }

        /* Labels */
        .product-label {
            font-family: var(--font-sans) !important;
            font-size: 9px !important; font-weight: 800 !important;
            letter-spacing: 1.2px; text-transform: uppercase;
            padding: 4px 10px !important; border-radius: var(--radius-sm) !important;
        }
        .product-label.label-sale { background: var(--dv-orange) !important; color: #fff !important; }
        .product-label.label-hot { background: var(--dv-navy) !important; color: #fff !important; }

        /* Action icons */
        .product-default .btn-icon-group {
            transition: opacity var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
        }
        .product-default .btn-icon {
            background: var(--surface) !important;
            border: 1px solid rgba(43,54,116,.12) !important;
            border-radius: 50% !important;
            width: 36px !important; height: 36px !important;
            display: inline-flex !important; align-items: center; justify-content: center;
            color: var(--ink-700) !important;
            transition: all var(--duration-fast);
        }
        .product-default .btn-icon:hover {
            background: var(--dv-navy) !important;
            border-color: var(--dv-navy) !important;
            color: #fff !important;
        }
        .product-default .btn-quickview {
            background: rgba(43,54,116,.85) !important;
            color: #fff !important;
            font-family: var(--font-sans) !important;
            font-size: 10px !important; font-weight: 700 !important;
            letter-spacing: 1.2px; text-transform: uppercase;
            padding: 10px !important;
            backdrop-filter: blur(4px);
            border-radius: 0 !important;
        }

        /* Product info */
        .product-default .product-details {
            padding: 14px 12px 12px !important;
        }
        .product-default .category-list .product-category,
        .product-default .category-wrap .product-category {
            font-family: var(--font-sans) !important;
            font-size: 10px !important; font-weight: 600 !important;
            letter-spacing: 1px; text-transform: uppercase;
            color: var(--ink-300) !important;
        }
        .product-default .product-title {
            font-family: var(--font-sans) !important;
            font-size: 13.5px !important; font-weight: 600 !important;
            line-height: 1.4; margin: 4px 0 6px !important;
        }
        .product-default .product-title a {
            color: var(--ink-700) !important;
            transition: color var(--duration-fast);
        }
        .product-default .product-title a:hover { color: var(--dv-navy) !important; }

        /* Price */
        .price-box .product-price, .price-box .new-price {
            font-family: var(--font-sans) !important;
            font-size: 15px !important; font-weight: 700 !important;
            color: var(--dv-navy) !important;
        }
        .price-box .old-price {
            font-size: 12px !important;
            color: var(--ink-300) !important;
            text-decoration: line-through;
        }

        /* Wishlist */
        .btn-icon-wish { transition: color var(--duration-fast); }
        .btn-icon-wish.added-wishlist,
        .btn-icon-wish.added-wishlist i { color: #e74c3c !important; }
        .btn-icon-wish:hover { color: var(--ink-700) !important; }

        /* Ratings */
        .ratings-container .product-ratings .ratings { background-color: var(--dv-orange); }

        /* ══════════════════════════════════════════════════
           8. SHOP PAGE
           ══════════════════════════════════════════════════ */
        .toolbox {
            background: var(--surface) !important;
            border: 1px solid rgba(43,54,116,.08) !important;
            border-radius: var(--radius-md) !important;
            padding: 12px 20px !important;
            margin-bottom: var(--space-xl) !important;
        }
        .toolbox label {
            font-family: var(--font-sans) !important;
            font-size: 10px !important; font-weight: 700 !important;
            letter-spacing: 1px; text-transform: uppercase;
            color: var(--ink-500) !important;
        }
        .toolbox .select-custom select {
            border: 1px solid rgba(43,54,116,.12) !important;
            border-radius: var(--radius-sm) !important;
            font-family: var(--font-sans) !important;
            font-size: 12px !important; font-weight: 600 !important;
            color: var(--ink-700) !important;
        }
        /* Sidebar toggle */
        .sidebar-toggle {
            font-family: var(--font-sans) !important;
            color: var(--dv-navy) !important;
        }
        .sidebar-toggle:hover {
            background: var(--dv-navy) !important;
            color: #fff !important;
        }
        /* Sidebar widgets */
        .sidebar .widget-title,
        .sidebar-shop .widget-title {
            font-family: var(--font-sans) !important;
            font-size: 11px !important; font-weight: 800 !important;
            letter-spacing: 1.5px !important; text-transform: uppercase !important;
            color: var(--dv-navy) !important;
            padding-bottom: 10px !important;
            border-bottom: 2px solid var(--dv-orange) !important;
            margin-bottom: 16px !important;
        }
        .sidebar .widget-title::after,
        .sidebar-shop .widget-title::after { display: none !important; }
        .sidebar .cat-list a,
        .sidebar-shop .cat-list a {
            font-family: var(--font-sans) !important;
            font-size: 13px !important;
            color: var(--ink-500) !important;
            transition: color var(--duration-fast), padding-left var(--duration-fast);
        }
        .sidebar .cat-list a:hover,
        .sidebar-shop .cat-list a:hover { color: var(--dv-navy) !important; padding-left: 4px; }
        .sidebar .cat-list li.active > a,
        .sidebar-shop .cat-list li.active > a {
            color: var(--dv-navy) !important; font-weight: 700 !important;
        }
        .sidebar .cat-list .products-count,
        .sidebar-shop .cat-list .products-count {
            color: var(--ink-300) !important; font-size: 11px;
        }

        /* Pagination */
        .pagination .page-item .page-link {
            font-family: var(--font-sans) !important;
            font-size: 13px !important; font-weight: 600 !important;
            color: var(--ink-500) !important;
            border-color: rgba(43,54,116,.1) !important;
            border-radius: var(--radius-sm) !important;
            transition: all var(--duration-fast);
        }
        .pagination .page-item.active .page-link {
            background: var(--dv-navy) !important;
            border-color: var(--dv-navy) !important;
            color: #fff !important;
        }
        .pagination .page-item .page-link:hover {
            background: var(--ink-100) !important;
            color: var(--dv-navy) !important;
        }

        /* Tags */
        .tags .tag {
            font-family: var(--font-sans) !important;
            font-size: 11px !important; font-weight: 600 !important;
            padding: 4px 12px !important;
            border: 1px solid rgba(43,54,116,.12) !important;
            border-radius: var(--radius-pill) !important;
            color: var(--ink-500) !important;
            transition: all var(--duration-fast);
        }
        .tags .tag:hover {
            background: var(--dv-navy) !important;
            border-color: var(--dv-navy) !important;
            color: #fff !important;
        }

        /* ══════════════════════════════════════════════════
           9. SINGLE PRODUCT PAGE
           ══════════════════════════════════════════════════ */
        .product-single-gallery { position: sticky; top: 80px; align-self: start; }
        .product-single-carousel .product-item img {
            border-radius: var(--radius-md) !important;
            background: var(--ink-50);
        }
        .prod-thumbnail { margin-top: 12px; }
        .prod-thumbnail .owl-dot img {
            border-radius: var(--radius-sm) !important;
            border: 2px solid transparent !important;
            opacity: .6; cursor: pointer;
            transition: border-color var(--duration-fast), opacity var(--duration-fast);
        }
        .prod-thumbnail .owl-dot.active img,
        .prod-thumbnail .owl-dot:hover img {
            border-color: var(--dv-navy) !important; opacity: 1;
        }
        .product-single-details .product-title {
            font-family: var(--font-serif) !important;
            font-size: clamp(22px, 3vw, 30px) !important;
            font-weight: 700 !important; line-height: 1.25;
            letter-spacing: -0.3px;
            color: var(--dv-navy) !important;
            margin-bottom: var(--space-md) !important;
        }
        .product-single-details .price-box { margin: var(--space-md) 0; }
        .product-single-details .product-price,
        .product-single-details .new-price {
            font-size: 24px !important; font-weight: 800 !important;
            color: var(--dv-navy) !important;
        }
        .product-single-details .old-price {
            font-size: 16px !important; color: var(--ink-300) !important;
        }
        .short-divider {
            border-color: rgba(43,54,116,.08) !important;
            margin: var(--space-lg) 0 !important;
        }
        .single-info-list li {
            font-family: var(--font-sans) !important;
            font-size: 12px !important;
            color: var(--ink-500) !important;
            letter-spacing: .3px;
        }
        .single-info-list strong { color: var(--ink-700) !important; font-weight: 700; }
        .single-info-list a.product-category {
            color: var(--dv-navy) !important;
            text-decoration: none;
        }
        .single-info-list a.product-category:hover { color: var(--dv-orange) !important; }

        /* Product tabs */
        .product-single-tabs .nav-tabs {
            border-bottom: 1px solid rgba(43,54,116,.08) !important;
        }
        .product-single-tabs .nav-link,
        .nav-tabs .nav-link {
            font-family: var(--font-sans) !important;
            font-size: 11px !important; font-weight: 700 !important;
            letter-spacing: 1px; text-transform: uppercase;
            color: var(--ink-500) !important;
            border: none !important; padding: 14px 20px !important;
            border-bottom: 2px solid transparent !important;
            border-radius: 0 !important;
            transition: color var(--duration-fast), border-color var(--duration-fast);
        }
        .product-single-tabs .nav-link.active,
        .nav-tabs .nav-link.active {
            color: var(--dv-navy) !important;
            border-bottom-color: var(--dv-navy) !important;
        }
        .product-single-tabs .nav-link:hover,
        .nav-tabs .nav-link:hover { color: var(--dv-navy) !important; }

        /* Rating link */
        .rating-link { color: var(--ink-500) !important; font-size: 12px; }
        .rating-link:hover { color: var(--dv-navy) !important; }

        /* Related products section */
        .products-section .section-title {
            font-family: var(--font-serif) !important;
            color: var(--dv-navy) !important;
        }

        /* ══════════════════════════════════════════════════
           10. FOOTER
           ══════════════════════════════════════════════════ */
        .footer { font-family: var(--font-sans) !important; }
        .footer .footer-middle {
            background: var(--dv-navy-dark) !important;
            color: rgba(255,255,255,.5) !important;
            padding: var(--space-3xl) 0 var(--space-2xl) !important;
        }
        .footer .widget-title {
            font-family: var(--font-sans) !important;
            font-size: 10px !important; font-weight: 800 !important;
            letter-spacing: 2px !important; text-transform: uppercase !important;
            color: rgba(255,255,255,.9) !important;
            margin-bottom: var(--space-lg) !important;
            padding-bottom: 10px !important;
            border-bottom: 2px solid var(--dv-orange) !important;
        }
        .footer .widget-title::after { display: none !important; }
        .footer .links li { margin-bottom: 8px !important; }
        .footer .links a, .footer .footer-middle a {
            font-size: 13px !important;
            color: rgba(255,255,255,.45) !important;
            font-weight: 400 !important;
            transition: color var(--duration-fast) !important;
        }
        .footer .links a:hover, .footer .footer-middle a:hover {
            color: var(--dv-orange-light) !important;
        }
        .footer .contact-info li {
            font-size: 13px !important;
            color: rgba(255,255,255,.45) !important;
        }
        .footer .contact-info-label {
            color: rgba(255,255,255,.75) !important;
            font-weight: 600 !important;
        }
        .footer .contact-info a { color: rgba(255,255,255,.45) !important; }
        .footer .contact-info a:hover { color: var(--dv-orange-light) !important; }

        /* Social icons */
        .footer .social-icon {
            width: 34px !important; height: 34px !important;
            border: 1px solid rgba(255,255,255,.15) !important;
            border-radius: 50% !important;
            display: inline-flex !important; align-items: center; justify-content: center;
            color: rgba(255,255,255,.5) !important;
            font-size: 13px !important;
            transition: all var(--duration-fast) !important;
            background: transparent !important;
        }
        .footer .social-icon:hover {
            background: var(--dv-orange) !important;
            border-color: var(--dv-orange) !important;
            color: #fff !important;
        }

        /* Newsletter */
        .widget-newsletter p {
            font-size: 13px !important;
            color: rgba(255,255,255,.45) !important;
            line-height: 1.7 !important;
        }
        .widget-newsletter .form-control {
            background: rgba(255,255,255,.07) !important;
            border: 1px solid rgba(255,255,255,.12) !important;
            color: #fff !important;
            border-radius: var(--radius-sm) !important;
            height: 44px !important;
            font-family: var(--font-sans) !important;
            font-size: 13px !important;
        }
        .widget-newsletter .form-control::placeholder { color: rgba(255,255,255,.3) !important; }
        .widget-newsletter .form-control:focus {
            background: rgba(255,255,255,.1) !important;
            border-color: var(--dv-orange) !important;
            box-shadow: none !important;
        }
        .widget-newsletter .btn {
            background: var(--dv-orange) !important;
            border: none !important;
            color: #fff !important;
            font-family: var(--font-sans) !important;
            font-size: 11px !important; font-weight: 800 !important;
            letter-spacing: 1.2px; text-transform: uppercase;
            height: 44px !important; padding: 0 20px !important;
            border-radius: var(--radius-sm) !important;
            transition: background var(--duration-fast) !important;
        }
        .widget-newsletter .btn:hover {
            background: var(--dv-orange-dark) !important;
        }

        /* Footer bottom */
        .footer .footer-bottom, .footer-bottom {
            background: #141B3D !important;
            border-top: 1px solid rgba(255,255,255,.05) !important;
            padding: var(--space-lg) 0 !important;
        }
        .footer .footer-copyright, .footer-copyright {
            font-family: var(--font-sans) !important;
            font-size: 11px !important;
            color: rgba(255,255,255,.3) !important;
        }

        /* ══════════════════════════════════════════════════
           11. MINI CART / CART DROPDOWN
           ══════════════════════════════════════════════════ */
        .dropdown-menu.mobile-cart {
            border: 1px solid rgba(43,54,116,.08) !important;
            box-shadow: var(--shadow-modal) !important;
            border-radius: var(--radius-lg) !important;
        }
        .dropdown-cart-header {
            font-family: var(--font-serif) !important;
            font-size: 20px !important; font-weight: 700 !important;
            color: var(--dv-navy) !important;
            letter-spacing: -0.3px;
        }
        .dropdown-cart-products .product-title a {
            font-family: var(--font-sans) !important;
            font-size: 13px !important; font-weight: 600 !important;
            color: var(--ink-700) !important;
        }
        .dropdown-cart-products .product-image-container img {
            border-radius: var(--radius-sm) !important;
        }
        .dropdown-cart-total {
            font-family: var(--font-sans) !important;
            font-size: 13px !important; font-weight: 700 !important;
            letter-spacing: .5px; text-transform: uppercase;
            color: var(--dv-navy) !important;
        }
        .dropdown-cart-action .btn {
            font-family: var(--font-sans) !important;
            font-weight: 700 !important;
            letter-spacing: .8px;
            border-radius: var(--radius-sm) !important;
        }
        .dropdown-cart-action .btn.btn-gray,
        .dropdown-cart-action .btn.view-cart {
            background: var(--ink-100) !important;
            border-color: rgba(43,54,116,.1) !important;
            color: var(--dv-navy) !important;
        }
        .dropdown-cart-action .btn.btn-gray:hover,
        .dropdown-cart-action .btn.view-cart:hover {
            background: var(--ink-50) !important;
        }

        /* ══════════════════════════════════════════════════
           12. CART PAGE
           ══════════════════════════════════════════════════ */
        .table-cart thead th {
            font-family: var(--font-sans) !important;
            font-size: 10px !important; font-weight: 700 !important;
            letter-spacing: 1.2px; text-transform: uppercase;
            color: var(--ink-500) !important;
            border-bottom: 2px solid rgba(43,54,116,.08) !important;
        }
        .table-cart .product-title a {
            font-family: var(--font-sans) !important;
            font-size: 14px !important; font-weight: 600 !important;
            color: var(--ink-700) !important;
        }
        .table-cart .product-title a:hover { color: var(--dv-navy) !important; }
        .cart-summary {
            background: var(--surface) !important;
            border: 1px solid rgba(43,54,116,.08) !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--shadow-card) !important;
        }
        .cart-summary h3 {
            font-family: var(--font-serif) !important;
            font-size: 18px !important;
            color: var(--dv-navy) !important;
        }

        /* ══════════════════════════════════════════════════
           13. CHECKOUT PAGE
           ══════════════════════════════════════════════════ */
        .checkout-billing-card {
            background: var(--surface) !important;
            border: 1px solid rgba(43,54,116,.07) !important;
            border-radius: var(--radius-lg) !important;
            padding: var(--space-xl) !important;
            margin-bottom: var(--space-lg) !important;
            box-shadow: var(--shadow-card) !important;
        }
        .checkout-billing-card .section-header {
            margin-bottom: var(--space-xl) !important;
            padding-bottom: var(--space-lg) !important;
            border-bottom: 1px solid rgba(43,54,116,.06) !important;
        }
        .checkout-billing-card .section-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--dv-navy), var(--dv-navy-light)) !important;
            border-radius: var(--radius-md) !important;
            display: flex; align-items: center; justify-content: center;
            color: #fff !important; font-size: 14px;
        }
        .checkout-billing-card .section-header h3 {
            font-family: var(--font-serif) !important;
            font-size: 18px !important; font-weight: 700 !important;
            color: var(--dv-navy) !important;
        }
        .checkout-billing-card .section-header p {
            font-size: 13px !important; color: var(--ink-500) !important;
        }
        .checkout-billing-card .form-control {
            border: 1px solid rgba(43,54,116,.12) !important;
            border-radius: var(--radius-sm) !important;
            height: 44px !important;
            font-family: var(--font-sans) !important;
            font-size: 13.5px !important; color: var(--ink-700) !important;
            transition: border-color var(--duration-fast), box-shadow var(--duration-fast);
        }
        .checkout-billing-card .form-control:focus {
            border-color: var(--dv-navy) !important;
            box-shadow: 0 0 0 3px rgba(43,54,116,.08) !important;
        }
        .checkout-billing-card label {
            font-family: var(--font-sans) !important;
            font-size: 11px !important; font-weight: 700 !important;
            letter-spacing: .6px; text-transform: uppercase;
            color: var(--ink-500) !important;
        }

        /* Saved address cards */
        .saved-address-card {
            border: 1.5px solid rgba(43,54,116,.1) !important;
            border-radius: var(--radius-md) !important;
            padding: 14px !important; cursor: pointer;
            transition: border-color var(--duration-fast), box-shadow var(--duration-fast);
            background: var(--surface) !important;
        }
        .saved-address-card:hover { border-color: var(--dv-navy-light) !important; }
        .saved-address-card.selected {
            border-color: var(--dv-navy) !important;
            box-shadow: 0 0 0 3px rgba(43,54,116,.1) !important;
        }

        /* Order summary sidebar */
        .order-summary-card {
            background: var(--surface) !important;
            border: 1px solid rgba(43,54,116,.07) !important;
            border-radius: var(--radius-lg) !important;
            padding: var(--space-xl) !important;
            position: sticky; top: 100px;
            box-shadow: var(--shadow-card) !important;
        }
        .order-summary-card .order-title {
            font-family: var(--font-serif) !important;
            font-size: 18px !important;
            color: var(--dv-navy) !important;
        }

        /* Place order button */
        .btn-place-order-modern, .btn-place-order {
            width: 100% !important; height: 52px !important;
            background: var(--dv-navy) !important;
            color: #fff !important; border: none !important;
            border-radius: var(--radius-sm) !important;
            font-family: var(--font-sans) !important;
            font-size: 12px !important; font-weight: 800 !important;
            letter-spacing: 1.5px; text-transform: uppercase;
            cursor: pointer;
            transition: background var(--duration-fast), transform var(--duration-fast);
            display: flex !important; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-place-order-modern:hover, .btn-place-order:hover {
            background: var(--dv-navy-light) !important;
        }
        .btn-place-order-modern:active, .btn-place-order:active {
            transform: scale(.98);
        }

        /* Secure badge */
        .secure-badge {
            color: var(--ink-300) !important;
            font-size: 11px !important;
        }

        /* Payment methods */
        .payment-option {
            border: 1px solid rgba(43,54,116,.1) !important;
            border-radius: var(--radius-md) !important;
            transition: border-color var(--duration-fast);
        }
        .payment-option.selected, .payment-option:hover {
            border-color: var(--dv-navy) !important;
        }
        .payment-label {
            font-family: var(--font-sans) !important;
            font-weight: 600 !important;
            color: var(--ink-700) !important;
        }
        .payment-desc {
            font-size: 12px !important;
            color: var(--ink-500) !important;
        }

        /* ══════════════════════════════════════════════════
           14. AUTH PAGES (Login/Register)
           ══════════════════════════════════════════════════ */
        .login-container {
            padding: var(--space-2xl) 0 !important;
        }
        .login-container .heading .title {
            font-family: var(--font-serif) !important;
            font-size: 28px !important; font-weight: 700 !important;
            color: var(--dv-navy) !important;
            letter-spacing: -0.3px;
        }
        .login-container .form-input,
        .login-container .form-wide {
            height: 48px !important;
            border: 1px solid rgba(43,54,116,.12) !important;
            border-radius: var(--radius-sm) !important;
            font-family: var(--font-sans) !important;
            font-size: 14px !important; color: var(--ink-700) !important;
            transition: border-color var(--duration-fast), box-shadow var(--duration-fast);
            width: 100%; padding: 0 14px;
        }
        .login-container .form-input:focus,
        .login-container .form-wide:focus {
            border-color: var(--dv-navy) !important;
            box-shadow: 0 0 0 3px rgba(43,54,116,.08) !important;
            outline: none;
        }
        .login-container label {
            font-family: var(--font-sans) !important;
            font-size: 11px !important; font-weight: 700 !important;
            letter-spacing: .6px; text-transform: uppercase;
            color: var(--ink-500) !important;
            margin-bottom: 6px !important;
        }
        .login-container .btn-dark {
            height: 48px !important;
            font-size: 12px !important; font-weight: 800 !important;
            letter-spacing: 1.5px;
        }
        .login-container .forget-password {
            font-family: var(--font-sans) !important;
            font-size: 12px !important;
            color: var(--dv-navy) !important;
        }
        .login-container .forget-password:hover {
            color: var(--dv-orange) !important;
        }
        .login-container .text-primary {
            color: var(--dv-navy) !important;
        }
        .login-container .text-primary:hover {
            color: var(--dv-orange) !important;
        }

        /* ══════════════════════════════════════════════════
           15. ACCOUNT PAGES
           ══════════════════════════════════════════════════ */
        .account-container {
            padding: var(--space-2xl) 0 !important;
        }
        .account-container .sidebar h2 {
            font-family: var(--font-serif) !important;
            font-size: 20px !important;
            color: var(--dv-navy) !important;
        }
        .account-container .nav.nav-tabs.list {
            border: 1px solid rgba(43,54,116,.07) !important;
            border-radius: var(--radius-lg) !important;
            overflow: hidden;
        }
        .account-container .nav.nav-tabs.list .nav-item {
            border-bottom: 1px solid rgba(43,54,116,.05) !important;
        }
        .account-container .nav.nav-tabs.list .nav-link {
            font-family: var(--font-sans) !important;
            font-size: 13px !important; font-weight: 600 !important;
            color: var(--ink-500) !important;
            padding: 14px 20px !important;
            border: none !important; border-radius: 0 !important;
            transition: background var(--duration-fast), color var(--duration-fast);
        }
        .account-container .nav.nav-tabs.list .nav-link:hover {
            background: var(--ink-50) !important;
            color: var(--dv-navy) !important;
        }
        .account-container .nav.nav-tabs.list .nav-link.active {
            background: var(--ink-100) !important;
            color: var(--dv-navy) !important;
            font-weight: 700 !important;
            border-left: 3px solid var(--dv-navy) !important;
        }
        .account-container .tab-content {
            background: var(--surface) !important;
            border: 1px solid rgba(43,54,116,.07) !important;
            border-radius: var(--radius-lg) !important;
            padding: var(--space-xl) !important;
        }

        /* ══════════════════════════════════════════════════
           16. HOMEPAGE SECTIONS
           ══════════════════════════════════════════════════ */
        .feature-container {
            padding: var(--space-2xl) 0 !important;
        }
        .feature-container .subtitle {
            font-family: var(--font-serif) !important;
            color: var(--dv-navy) !important;
        }
        .banner-container {
            padding: var(--space-xl) 0 !important;
        }
        .banner.banner-image {
            border-radius: var(--radius-md) !important;
            overflow: hidden;
        }
        .banner.banner-image img {
            transition: transform var(--duration-slow) var(--ease-out);
        }
        .banner.banner-image:hover img { transform: scale(1.04); }
        .banner-meta a {
            font-family: var(--font-sans) !important;
            font-weight: 700 !important;
            letter-spacing: .5px;
        }

        /* Slider */
        .home-slider-container {
            border-radius: var(--radius-lg) !important;
            overflow: hidden;
        }
        .home-slide figure img.slide-bg {
            border-radius: 0 !important;
        }
        .home-slide .btn { border-radius: var(--radius-sm) !important; }

        /* ══════════════════════════════════════════════════
           17. FORM INPUTS GLOBAL
           ══════════════════════════════════════════════════ */
        .form-control {
            font-family: var(--font-sans) !important;
            border-radius: var(--radius-sm) !important;
        }
        .form-control:focus {
            border-color: var(--dv-navy) !important;
            box-shadow: 0 0 0 3px rgba(43,54,116,.08) !important;
        }

        /* Alerts */
        .alert { border-radius: var(--radius-md) !important; font-family: var(--font-sans); }

        /* ══════════════════════════════════════════════════
           18. MOBILE RESPONSIVE
           ══════════════════════════════════════════════════ */
        @media (max-width: 991px) {
            .sticky-navbar {
                background: rgba(255,255,255,.95) !important;
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-top: 1px solid rgba(43,54,116,.08) !important;
                box-shadow: 0 -4px 20px rgba(43,54,116,.06) !important;
            }
            .sticky-navbar .sticky-info a {
                font-family: var(--font-sans) !important;
                font-size: 10px !important; font-weight: 600 !important;
                color: var(--ink-500) !important;
                letter-spacing: .2px;
            }
            .sticky-navbar .sticky-info a i {
                color: var(--ink-500) !important;
                font-size: 18px !important;
            }
            .sticky-navbar .sticky-info a:hover,
            .sticky-navbar .sticky-info a:hover i {
                color: var(--dv-navy) !important;
            }
        }

        /* Mobile menu */
        .mobile-menu-container {
            background: var(--surface) !important;
        }
        .mobile-menu-wrapper {
            background: var(--surface) !important;
        }
        .mobile-menu li > a {
            font-family: var(--font-sans) !important;
            font-size: 13px !important; font-weight: 600 !important;
            color: var(--ink-700) !important;
            padding: 12px var(--space-lg) !important;
            border-bottom: 1px solid rgba(43,54,116,.05) !important;
        }
        .mobile-menu li > a:hover { color: var(--dv-navy) !important; }
        .mobile-menu-close {
            color: var(--ink-500) !important;
        }
        .mobile-nav .search-wrapper input {
            border: 1px solid rgba(43,54,116,.12) !important;
            border-radius: var(--radius-sm) !important;
            font-family: var(--font-sans) !important;
        }
        .mobile-nav .social-icon {
            border-color: rgba(43,54,116,.12) !important;
            color: var(--ink-500) !important;
        }
        .mobile-nav .social-icon:hover {
            background: var(--dv-navy) !important;
            border-color: var(--dv-navy) !important;
            color: #fff !important;
        }

        /* Mobile header adjustments */
        @media (max-width: 767px) {
            .header-middle { padding: 12px 0 !important; }
            .header-middle .logo img { height: 32px; }
            .page-header h1 { font-size: 20px !important; }
            .product-single-details .product-title { font-size: 20px !important; }
        }

        /* Scroll top button */
        .btn-scroll {
            background: var(--dv-navy) !important;
            color: #fff !important;
            border-radius: 50% !important;
            width: 40px !important; height: 40px !important;
            display: flex !important; align-items: center; justify-content: center;
            box-shadow: var(--shadow-card) !important;
        }
        .btn-scroll:hover {
            background: var(--dv-orange) !important;
        }

        /* Loading overlay */
        .bounce-loader .bounce1,
        .bounce-loader .bounce2,
        .bounce-loader .bounce3 {
            background: var(--dv-navy) !important;
        }

        /* Owl Carousel nav arrows */
        .owl-theme .owl-nav [class*=owl-] {
            background: var(--surface) !important;
            color: var(--dv-navy) !important;
            border: 1px solid rgba(43,54,116,.12) !important;
            border-radius: 50% !important;
            width: 38px !important; height: 38px !important;
            display: flex !important; align-items: center; justify-content: center;
            transition: all var(--duration-fast);
        }
        .owl-theme .owl-nav [class*=owl-]:hover {
            background: var(--dv-navy) !important;
            border-color: var(--dv-navy) !important;
            color: #fff !important;
        }
        .owl-theme .owl-dots .owl-dot span {
            background: var(--ink-300) !important;
        }
        .owl-theme .owl-dots .owl-dot.active span {
            background: var(--dv-navy) !important;
        }

        /* Quick view modal */
        .product-single-container .product-title {
            font-family: var(--font-serif) !important;
            color: var(--dv-navy) !important;
        }

        /* Contact page */
        .contact-info i {
            color: var(--dv-navy) !important;
        }
    </style>

    {{-- Live Search Dropdown + Product Card Hover CSS --}}
    <style>
        /* ══════ Live Search Float Box ══════ */
        #ls-float-box {
            display: none; position: fixed; background: var(--surface);
            border: 1px solid rgba(43,54,116,.08); border-top: 3px solid var(--dv-navy);
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            box-shadow: var(--shadow-modal);
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
            padding: 24px; gap: 10px; color: var(--dv-navy);
        }
        .ls-spinner {
            width: 20px; height: 20px; border: 3px solid var(--ink-100);
            border-top-color: var(--dv-navy); border-radius: 50%;
            animation: lsSpin 0.7s linear infinite;
        }
        @keyframes lsSpin { to { transform: rotate(360deg); } }
        .ls-row {
            display: flex; flex-direction: row; align-items: center;
            padding: 12px 20px; text-decoration: none !important;
            color: var(--ink-700); border-bottom: 1px solid var(--ink-100);
            gap: 14px; transition: background var(--duration-fast); cursor: pointer;
        }
        .ls-row:hover { background: var(--ink-50); text-decoration: none !important; }
        .ls-row:last-of-type { border-bottom: none; }
        .ls-row img {
            width: 48px !important; height: 48px !important; min-width: 48px;
            object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--ink-100);
            flex-shrink: 0; background: var(--ink-50);
        }
        .ls-detail { flex: 1; display: flex; flex-direction: column; min-width: 0; overflow: hidden; }
        .ls-title {
            font-family: var(--font-sans); font-size: 13px; font-weight: 600; color: var(--ink-700);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .ls-row:hover .ls-title { color: var(--dv-navy); }
        .ls-catname { font-size: 11px; color: var(--ink-300); margin-top: 1px; }
        .ls-prices {
            text-align: right; flex-shrink: 0; white-space: nowrap;
            display: flex; flex-direction: column; align-items: flex-end;
        }
        .ls-now { font-size: 14px; font-weight: 800; color: var(--dv-navy); }
        .ls-was { font-size: 11px; color: var(--ink-300); text-decoration: line-through; font-weight: 400; }
        .ls-viewall {
            display: block; text-align: center; padding: 13px;
            font-family: var(--font-sans); color: var(--dv-navy); font-weight: 700; font-size: 11px;
            letter-spacing: 1px; text-transform: uppercase;
            text-decoration: none !important;
            border-top: 1px solid var(--ink-100); background: var(--ink-50);
            border-radius: 0 0 var(--radius-lg) var(--radius-lg); transition: background var(--duration-fast);
        }
        .ls-viewall:hover { background: var(--ink-100); color: var(--dv-navy-dark); }
        .ls-empty {
            padding: 24px 16px; text-align: center; color: var(--ink-300);
            display: flex; flex-direction: column; align-items: center; gap: 6px;
        }
        .ls-empty i { font-size: 26px; color: var(--ink-100); }
        .ls-empty span { font-family: var(--font-sans); font-size: 13px; }
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
        .porto-toast { padding: 14px 22px; border-radius: var(--radius-md); color: #fff; font-size: 13px; font-family: var(--font-sans); font-weight: 500; box-shadow: var(--shadow-modal); display: flex; align-items: center; gap: 10px; animation: portoSlideIn 0.3s ease-out; min-width: 300px; max-width: 420px; backdrop-filter: blur(8px); }
        .porto-toast.success { background: var(--dv-navy-dark); }
        .porto-toast.error { background: #7f1d1d; }
        .porto-toast.info { background: var(--dv-navy); }
        .porto-toast.warning { background: #7c4a00; }
        .porto-toast i { font-size: 16px; }
        .porto-toast-close { margin-left: auto; cursor: pointer; opacity: 0.7; background: none; border: none; color: #fff; font-size: 18px; padding: 0; transition: opacity var(--duration-fast); }
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

    {{-- Newsletter popup removed — use dedicated coupon management instead --}}

    @stack('scripts')
</body>
</html>
