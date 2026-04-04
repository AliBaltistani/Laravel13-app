<header class="header @yield('header-class')">
    {{-- Header Top Bar --}}
    <div class="header-top @yield('header-top-class')">
        <div class="container">
            <div class="header-left d-none d-sm-block">
                @if(Setting::get('header.top_message'))
                    <p class="top-message text-uppercase mb-0">{{ Setting::get('header.top_message') }}</p>
                @endif
            </div>

            <div class="header-right header-dropdowns ml-0 ml-sm-auto w-sm-100">
                <div class="header-dropdown dropdown-expanded d-none d-lg-block">
                    <a href="#">Links</a>
                    <div class="header-menu">
                        <ul>
                            <li><a href="{{ route('account.dashboard') }}">My Account</a></li>
                            <li><a href="{{ url('/about') }}">About Us</a></li>
                            <li><a href="{{ url('/blog') }}">Blog</a></li>
                            <li><a href="{{ url('/wishlist') }}">My Wishlist</a></li>
                            <li><a href="{{ url('/cart') }}">Cart</a></li>
                            @guest
                                <li><a href="{{ route('login') }}" class="login-link">Log In</a></li>
                            @else
                                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a></li>
                            @endguest
                        </ul>
                    </div>
                </div>

                <span class="separator"></span>

                <div class="social-icons">
                    @if(Setting::get('social.facebook'))
                        <a href="{{ Setting::get('social.facebook') }}" class="social-icon social-facebook icon-facebook" target="_blank"></a>
                    @endif
                    @if(Setting::get('social.twitter'))
                        <a href="{{ Setting::get('social.twitter') }}" class="social-icon social-twitter icon-twitter" target="_blank"></a>
                    @endif
                    @if(Setting::get('social.instagram'))
                        <a href="{{ Setting::get('social.instagram') }}" class="social-icon social-instagram icon-instagram" target="_blank"></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- End .header-top --}}

    {{-- Header Middle (Sticky) --}}
    <div class="header-middle sticky-header" data-sticky-options="{'mobile': true}">
        <div class="container">
            <div class="header-left col-lg-2 w-auto pl-0">
                <button class="mobile-menu-toggler text-primary mr-2" type="button">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ url('/') }}" class="logo">
                    @if(Setting::get('appearance.logo'))
                        <img src="{{ asset('storage/' . Setting::get('appearance.logo')) }}" width="111" height="44" alt="{{ Setting::get('general.site_name', 'Porto Shop') }}">
                    @else
                        <img src="{{ asset('themes/porto/images/logo.png') }}" width="111" height="44" alt="{{ Setting::get('general.site_name', 'Porto Shop') }}">
                    @endif
                </a>
            </div>

            <div class="header-right w-lg-max">
                {{-- Live Search Bar (Livewire — OLX-style autocomplete with category filter) --}}
                @livewire('live-search')

                {{-- Phone --}}
                @if(Setting::get('contact.phone'))
                <div class="header-contact d-none d-lg-flex pl-4 pr-4">
                    <img alt="phone" src="{{ asset('themes/porto/images/phone.png') }}" width="30" height="30" class="pb-1">
                    <h6><span>Call us now</span><a href="tel:{{ Setting::get('contact.phone') }}" class="text-dark font1">{{ Setting::get('contact.phone') }}</a></h6>
                </div>
                @endif

                {{-- User Icon --}}
                @guest
                    <a href="{{ route('login') }}" class="header-icon" title="Login"><i class="icon-user-2"></i></a>
                @else
                    <a href="{{ route('account.dashboard') }}" class="header-icon" title="My Account"><i class="icon-user-2"></i></a>
                @endguest

                {{-- Wishlist Icon --}}
                <a href="{{ url('/wishlist') }}" class="header-icon" title="Wishlist"><i class="icon-wishlist-2"></i></a>

                {{-- Mini Cart (Livewire — auto-updates on addToCart/cartUpdated events) --}}
                @livewire('mini-cart')
            </div>
        </div>
    </div>
    {{-- End .header-middle --}}

    {{-- Header Bottom - Main Navigation (desktop only) --}}
    <div class="header-bottom sticky-header d-none d-lg-block" data-sticky-options="{'mobile': false}">
        <div class="container">
            <nav class="main-nav w-100">
                <ul class="menu">
                    <li class="{{ request()->is('/') ? 'active' : '' }}">
                        <a href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="{{ request()->is('shop*') ? 'active' : '' }}">
                        <a href="{{ url('/shop') }}">Categories</a>
                        @php $headerCategories = \App\Models\Category::active()->root()->ordered()->get(); @endphp
                        @if($headerCategories->count())
                        <div class="megamenu megamenu-fixed-width megamenu-3cols">
                            <div class="row">
                                @foreach($headerCategories->take(6)->chunk(3) as $chunk)
                                <div class="col-lg-4">
                                    @foreach($chunk as $cat)
                                    <a href="{{ url('/shop/category/' . $cat->slug) }}" class="nolink">{{ strtoupper($cat->name) }}</a>
                                    <ul class="submenu">
                                        @foreach($cat->children()->active()->ordered()->take(6)->get() as $child)
                                        <li><a href="{{ url('/shop/category/' . $child->slug) }}">{{ $child->name }}</a></li>
                                        @endforeach
                                    </ul>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </li>
                    <li class="{{ request()->is('shop*') ? 'active' : '' }}">
                        <a href="{{ url('/shop') }}">Products</a>
                    </li>
                    <li class="{{ request()->is('about*') || request()->is('contact*') || request()->is('blog*') ? 'active' : '' }}">
                        <a href="#">Pages</a>
                        <ul>
                            <li><a href="{{ url('/wishlist') }}">Wishlist</a></li>
                            <li><a href="{{ url('/cart') }}">Shopping Cart</a></li>
                            <li><a href="{{ url('/checkout') }}">Checkout</a></li>
                            <li><a href="{{ route('account.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ url('/about') }}">About Us</a></li>
                            <li><a href="#">Blog</a>
                                <ul>
                                    <li><a href="{{ url('/blog') }}">Blog</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                            @guest
                                <li><a href="{{ route('login') }}">Login</a></li>
                            @endguest
                        </ul>
                    </li>
                    <li class="{{ request()->is('blog*') ? 'active' : '' }}">
                        <a href="{{ url('/blog') }}">Blog</a>
                    </li>
                    <li class="{{ request()->is('contact*') ? 'active' : '' }}">
                        <a href="{{ url('/contact') }}">Contact Us</a>
                    </li>
                    @if(Setting::get('header.show_special_offer', '1') === '1' && Setting::get('header.special_offer_text'))
                        <li class="float-right"><a href="{{ Setting::get('header.special_offer_url', '/shop') }}" class="pl-5">{{ Setting::get('header.special_offer_text', 'Special Offer!') }}</a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
    {{-- End .header-bottom --}}
</header>

@guest
@else
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
@endguest
