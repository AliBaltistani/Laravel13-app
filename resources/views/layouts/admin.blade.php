<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Admin | {{ \App\Models\Setting::get('general.site_name', 'Porto Shop') }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('themes/porto/images/icons/favicon.png') }}">

    {{-- Google Fonts --}}
    <script>
        WebFontConfig = {
            google: { families: ['Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700'] }
        };
        (function(d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = '{{ asset("themes/porto/js/webfont.js") }}';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    {{-- Plugins CSS (No Porto storefront CSS — it conflicts with admin) --}}
    <link rel="stylesheet" href="{{ asset('themes/porto/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/porto/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/porto/vendor/simple-line-icons/css/simple-line-icons.min.css') }}">

    {{-- Admin Custom Styles --}}
    {{-- Admin Dynamic Colors from Appearance settings --}}
    @php
        $adminPrimary    = \App\Models\Setting::get('appearance.admin_primary', '#0d6efd');
        $adminSidebarBg  = \App\Models\Setting::get('appearance.admin_sidebar_bg', '#1e2a3a');
        $adminSidebarTxt = \App\Models\Setting::get('appearance.admin_sidebar_text', '#a8b6c7');
        $adminTopbarBg   = \App\Models\Setting::get('appearance.admin_topbar_bg', '#ffffff');
        // Auto-derive darker/border shades from sidebar bg
        $sidebarRgb = sscanf($adminSidebarBg, '#%02x%02x%02x');
        $darkerBg   = sprintf('#%02x%02x%02x', max(0,$sidebarRgb[0]-15), max(0,$sidebarRgb[1]-15), max(0,$sidebarRgb[2]-15));
        $borderBg   = sprintf('#%02x%02x%02x', min(255,$sidebarRgb[0]+20), min(255,$sidebarRgb[1]+20), min(255,$sidebarRgb[2]+20));
    @endphp
    <style>
        :root {
            --admin-sidebar-width: 260px;
            --admin-sidebar-collapsed: 60px;
            --admin-primary: {{ $adminPrimary }};
            --admin-dark: {{ $adminSidebarBg }};
            --admin-darker: {{ $darkerBg }};
            --admin-border: {{ $borderBg }};
            --admin-sidebar-text: {{ $adminSidebarTxt }};
            --admin-topbar-bg: {{ $adminTopbarBg }};
        }

        body.admin-body {
            background: #f4f6f9;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--admin-sidebar-width);
            height: 100vh;
            background: var(--admin-dark);
            z-index: 1050;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width 0.3s ease;
            scrollbar-width: thin;
            scrollbar-color: var(--admin-border) transparent;
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: var(--admin-border);
            border-radius: 3px;
        }

        .admin-sidebar .sidebar-brand {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid var(--admin-border);
            height: 60px;
        }

        .admin-sidebar .sidebar-brand img {
            max-height: 30px;
            max-width: 130px;
        }

        .admin-sidebar .sidebar-brand .brand-text {
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .admin-sidebar .nav-section {
            padding: 12px 15px 5px;
            font-size: 11px;
            font-weight: 700;
            color: #6c7a8d;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .admin-sidebar .nav-item {
            margin: 1px 8px;
        }

        .admin-sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 9px 14px;
            color: var(--admin-sidebar-text);
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .admin-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }

        .admin-sidebar .nav-link.active {
            background: var(--admin-primary);
            color: #fff;
            box-shadow: 0 2px 8px rgba(13,110,253,0.3);
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 12px;
            font-size: 16px;
            flex-shrink: 0;
        }

        .admin-sidebar .nav-link .menu-badge {
            margin-left: auto;
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 10px;
        }

        .admin-sidebar .nav-submenu {
            display: none;
            padding-left: 10px;
        }

        .admin-sidebar .nav-item.open > .nav-submenu {
            display: block;
        }

        .admin-sidebar .nav-link .arrow {
            margin-left: auto;
            transition: transform 0.2s;
            font-size: 10px;
        }

        .admin-sidebar .nav-item.open > .nav-link .arrow {
            transform: rotate(90deg);
        }

        .admin-sidebar .nav-submenu .nav-link {
            padding: 7px 14px 7px 46px;
            font-size: 13px;
        }

        /* Top Navbar */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: var(--admin-sidebar-width);
            right: 0;
            height: 60px;
            background: var(--admin-topbar-bg);
            z-index: 1040;
            display: flex;
            align-items: center;
            padding: 0 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            transition: left 0.3s ease;
        }

        .admin-topbar .topbar-left {
            display: flex;
            align-items: center;
        }

        .admin-topbar .sidebar-toggle {
            background: none;
            border: none;
            font-size: 20px;
            color: #495057;
            cursor: pointer;
            padding: 5px 10px;
            margin-right: 15px;
            border-radius: 4px;
        }

        .admin-topbar .sidebar-toggle:hover {
            background: #f0f0f0;
        }

        .admin-topbar .page-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3d50;
            margin: 0;
        }

        .admin-topbar .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-topbar .topbar-link {
            color: #6c757d;
            font-size: 18px;
            padding: 5px;
            position: relative;
        }

        .admin-topbar .topbar-link:hover {
            color: var(--admin-primary);
        }

        .admin-topbar .admin-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--admin-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        /* Main Content */
        .admin-content {
            margin-left: var(--admin-sidebar-width);
            padding: 80px 24px 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Stat Cards */
        .stat-card {
            background: #fff;
            border-radius: 8px;
            padding: 22px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            display: flex;
            align-items: flex-start;
            gap: 15px;
            border: 1px solid #eef0f3;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-card .stat-icon.bg-primary-soft { background: rgba(13,110,253,0.1); color: #0d6efd; }
        .stat-card .stat-icon.bg-success-soft { background: rgba(25,135,84,0.1); color: #198754; }
        .stat-card .stat-icon.bg-warning-soft { background: rgba(255,193,7,0.1); color: #cc9a06; }
        .stat-card .stat-icon.bg-danger-soft { background: rgba(220,53,69,0.1); color: #dc3545; }

        .stat-card .stat-info h3 {
            font-size: 26px;
            font-weight: 700;
            margin: 0;
            color: #1e2a3a;
        }

        .stat-card .stat-info p {
            font-size: 13px;
            color: #6c757d;
            margin: 0;
            font-weight: 500;
        }

        /* Cards */
        .admin-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border: 1px solid #eef0f3;
        }

        .admin-card .card-header {
            background: transparent;
            border-bottom: 1px solid #eef0f3;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-card .card-header h5 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #1e2a3a;
        }

        .admin-card .card-body {
            padding: 20px;
        }

        /* Admin Table */
        .admin-table {
            width: 100%;
        }

        .admin-table thead th {
            background: #f8f9fa;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: 0.5px;
            padding: 10px 14px;
            border-bottom: 2px solid #dee2e6;
            white-space: nowrap;
        }

        .admin-table tbody td {
            padding: 12px 14px;
            font-size: 13.5px;
            color: #495057;
            border-bottom: 1px solid #eef0f3;
            vertical-align: middle;
        }

        .admin-table tbody tr:hover {
            background: #f8f9fc;
        }

        /* Status Badge */
        .badge-status {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 600;
        }

        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-processing { background: #cce5ff; color: #004085; }
        .badge-shipped { background: #d1ecf1; color: #0c5460; }
        .badge-delivered { background: #d4edda; color: #155724; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
        .badge-paid { background: #d4edda; color: #155724; }
        .badge-unpaid { background: #fff3cd; color: #856404; }
        .badge-failed { background: #f8d7da; color: #721c24; }

        /* Responsive */
        .sidebar-collapsed .admin-sidebar {
            width: var(--admin-sidebar-collapsed);
        }
        .sidebar-collapsed .admin-sidebar .nav-section,
        .sidebar-collapsed .admin-sidebar .nav-link span,
        .sidebar-collapsed .admin-sidebar .nav-link .arrow,
        .sidebar-collapsed .admin-sidebar .nav-link .menu-badge,
        .sidebar-collapsed .admin-sidebar .brand-text {
            display: none;
        }
        .sidebar-collapsed .admin-sidebar .nav-link {
            justify-content: center;
            padding: 10px;
        }
        .sidebar-collapsed .admin-sidebar .nav-link i {
            margin-right: 0;
        }
        .sidebar-collapsed .admin-topbar {
            left: var(--admin-sidebar-collapsed);
        }
        .sidebar-collapsed .admin-content {
            margin-left: var(--admin-sidebar-collapsed);
        }

        @@media (max-width: 991px) {
            .admin-sidebar {
                left: calc(-1 * var(--admin-sidebar-width));
            }
            .admin-topbar {
                left: 0;
            }
            .admin-content {
                margin-left: 0;
            }
            body.sidebar-open .admin-sidebar {
                left: 0;
            }
            body.sidebar-open .admin-overlay {
                display: block;
            }
        }

        .admin-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            z-index: 1045;
        }

        /* Quick action buttons */
        .btn-admin-sm {
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 4px;
        }

        /* Page header breadcrumb */
        .admin-breadcrumb {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0 0 20px;
            font-size: 13px;
        }
        .admin-breadcrumb li + li::before {
            content: '/';
            padding: 0 8px;
            color: #adb5bd;
        }
        .admin-breadcrumb a { color: var(--admin-primary); }
        .admin-breadcrumb .active { color: #6c757d; }

        /* Chart container */
        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>

    {{-- Summernote Rich Text Editor (Free) --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

    @livewireStyles
    @stack('styles')

    {{-- Custom CSS --}}
    @if(\App\Models\Setting::get('custom_code.css'))
        <style>
            {!! \App\Models\Setting::get('custom_code.css') !!}
        </style>
    @endif
</head>

<body class="admin-body">
    {{-- Overlay for mobile --}}
    <div class="admin-overlay" onclick="document.body.classList.remove('sidebar-open')"></div>

    {{-- Sidebar --}}
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <span class="brand-text">{{ \App\Models\Setting::get('general.site_name', 'Porto Admin') }}</span>
        </div>

        <nav class="mt-2">
            {{-- Dashboard --}}
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-section">Catalog</div>

            @can('manage_products')
            <div class="nav-item {{ request()->routeIs('admin.products*') ? 'open' : '' }}">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </div>
            @endcan

            @can('manage_categories')
            <div class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Categories</span>
                </a>
            </div>
            @endcan

            @can('manage_brands')
            <div class="nav-item">
                <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Brands</span>
                </a>
            </div>
            @endcan

            <div class="nav-section">Sales</div>

            @can('manage_orders')
            <div class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Orders</span>
                </a>
            </div>
            @endcan

            @can('manage_customers')
            <div class="nav-item">
                <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </div>
            @endcan

            @can('manage_reviews')
            <div class="nav-item">
                <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Reviews</span>
                </a>
            </div>
            @endcan

            <div class="nav-section">Promotions</div>

            @can('manage_coupons')
            <div class="nav-item">
                <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Coupons</span>
                </a>
            </div>
            @endcan

            @can('manage_flash_sales')
            <div class="nav-item">
                <a href="{{ route('admin.flash-sales.index') }}" class="nav-link {{ request()->routeIs('admin.flash-sales*') ? 'active' : '' }}">
                    <i class="fas fa-bolt"></i>
                    <span>Flash Sales</span>
                </a>
            </div>
            @endcan

            <div class="nav-section">Content</div>

            @can('manage_blog')
            <div class="nav-item {{ request()->routeIs('admin.posts*') || request()->routeIs('admin.post-categories*') || request()->routeIs('admin.comments*') ? 'open' : '' }}">
                <a href="#" class="nav-link" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="fas fa-newspaper"></i>
                    <span>Blog</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="nav-submenu">
                    <div class="nav-item">
                        <a href="{{ route('admin.posts.index') }}" class="nav-link {{ request()->routeIs('admin.posts*') ? 'active' : '' }}">
                            <span>Posts</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.post-categories.index') }}" class="nav-link {{ request()->routeIs('admin.post-categories*') ? 'active' : '' }}">
                            <span>Categories</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.comments.index') }}" class="nav-link {{ request()->routeIs('admin.comments*') ? 'active' : '' }}">
                            <span>Comments</span>
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            @can('manage_pages')
            <div class="nav-item">
                <a href="{{ route('admin.homepage.index') }}" class="nav-link {{ request()->routeIs('admin.homepage*') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Homepage Builder</span>
                </a>
            </div>
            @endcan

            @can('manage_pages')
            <div class="nav-item">
                <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Pages</span>
                </a>
            </div>
            @endcan

            @can('manage_banners')
            <div class="nav-item">
                <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
                    <i class="fas fa-image"></i>
                    <span>Banners</span>
                </a>
            </div>
            @endcan

            @can('manage_sliders')
            <div class="nav-item">
                <a href="{{ route('admin.sliders.index') }}" class="nav-link {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i>
                    <span>Sliders</span>
                </a>
            </div>
            @endcan

            <div class="nav-section">Configuration</div>

            @can('manage_shipping')
            <div class="nav-item">
                <a href="{{ route('admin.shipping-zones.index') }}" class="nav-link {{ request()->routeIs('admin.shipping*') ? 'active' : '' }}">
                    <i class="fas fa-truck"></i>
                    <span>Shipping</span>
                </a>
            </div>
            @endcan

            @can('manage_newsletter')
            <div class="nav-item">
                <a href="{{ route('admin.newsletter.index') }}" class="nav-link {{ request()->routeIs('admin.newsletter*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i>
                    <span>Newsletter</span>
                </a>
            </div>
            @endcan

            @can('manage_reports')
            <div class="nav-item {{ request()->routeIs('admin.reports*') ? 'open' : '' }}">
                <a href="#" class="nav-link" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="nav-submenu">
                    <div class="nav-item">
                        <a href="{{ route('admin.reports.sales') }}" class="nav-link {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                            <span>Sales</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.reports.products') }}" class="nav-link {{ request()->routeIs('admin.reports.products') ? 'active' : '' }}">
                            <span>Products</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.reports.inventory') }}" class="nav-link {{ request()->routeIs('admin.reports.inventory') ? 'active' : '' }}">
                            <span>Inventory</span>
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            @can('manage_settings')
            <div class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </div>
            @endcan
        </nav>
    </aside>

    {{-- Top Bar --}}
    <header class="admin-topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" onclick="document.body.classList.toggle('sidebar-collapsed'); document.body.classList.toggle('sidebar-open');">
                <i class="fas fa-bars"></i>
            </button>
            <h4 class="page-title d-none d-md-block">@yield('title', 'Dashboard')</h4>
        </div>

        <div class="topbar-right">
            <a href="{{ route('home') }}" class="topbar-link" title="View Store" target="_blank">
                <i class="fas fa-external-link-alt"></i>
            </a>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-toggle="dropdown">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar"
                             style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div class="admin-avatar">
                            {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                    <span class="d-none d-md-inline-block ml-2 text-dark font-weight-bold" style="font-size:13px;">
                        {{ auth()->user()->full_name ?? auth()->user()->name ?? 'Admin' }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="fas fa-user mr-2"></i> My Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.profile.password') }}">
                        <i class="fas fa-key mr-2"></i> Change Password
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="admin-content">
        {{-- Breadcrumb --}}
        @hasSection('breadcrumb')
            <ul class="admin-breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                @yield('breadcrumb')
            </ul>
        @endif

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @yield('admin-content')
    </main>

    {{-- Core JS --}}
    <script src="{{ asset('themes/porto/js/jquery.min.js') }}"></script>
    <script src="{{ asset('themes/porto/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Chart.js from CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Auto-close alerts
        setTimeout(function() {
            document.querySelectorAll('.alert-dismissible').forEach(function(el) {
                el.classList.remove('show');
                setTimeout(() => el.remove(), 300);
            });
        }, 4000);
    </script>

    @livewireScripts

    {{-- Summernote Rich Text Editor (Free) --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($('.richtext-editor').length) {
                $('.richtext-editor').summernote({
                    height: 300,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video', 'hr']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onInit: function() {
                            // Style the editor to match admin theme
                            $(this).closest('.note-editor').css('border-radius', '6px');
                        }
                    }
                });
            }
        });
    </script>

    @stack('scripts')

    {{-- Custom JS --}}
    @if(\App\Models\Setting::get('custom_code.js'))
        <script>
            {!! \App\Models\Setting::get('custom_code.js') !!}
        </script>
    @endif
</body>
</html>
