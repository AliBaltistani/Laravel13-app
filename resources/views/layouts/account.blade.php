@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['title' => $title ?? 'My Account', 'items' => [['label' => 'Shop', 'url' => url('/shop')]]])

    <div class="container account-container custom-account-container">
        <div class="row">
            {{-- Sidebar --}}
            <div class="sidebar widget widget-dashboard mb-lg-0 mb-3 col-lg-3 order-0">
                <h2 class="text-uppercase">My Account</h2>
                <ul class="nav nav-tabs list flex-column" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('account.dashboard') ? 'active' : '' }}" href="{{ route('account.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('account.orders*') ? 'active' : '' }}" href="{{ url('/account/orders') }}">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('account.addresses*') ? 'active' : '' }}" href="{{ url('/account/addresses') }}">Addresses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('account.details*') ? 'active' : '' }}" href="{{ url('/account/details') }}">Account details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('wishlist*') ? 'active' : '' }}" href="{{ url('/wishlist') }}">Wishlist</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('account-logout-form').submit();">Logout</a>
                    </li>
                </ul>
                <form id="account-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>

            {{-- Content Area --}}
            <div class="col-lg-9 order-lg-last order-1 tab-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                    </div>
                @endif

                @yield('account-content')
            </div>
        </div>
    </div>
@endsection
