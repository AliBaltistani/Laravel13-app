<header class="header">
    {{-- Header Top Bar (Demo8) --}}
    <div class="header-top">
        <div class="header-row container">
            <div class="header-left">
                <div class="header-dropdown">
                    <a href="#" class="pl-0">{{ Setting::get('general.currency_code', 'USD') }}</a>
                    <div class="header-menu">
                        <ul>
                            <li><a href="#">{{ Setting::get('general.currency_code', 'USD') }}</a></li>
                        </ul>
                    </div>
                </div>

                <div class="header-dropdown mr-auto mr-sm-3 mr-md-0">
                    <a href="#">ENG</a>
                    <div class="header-menu">
                        <ul>
                            <li><a href="#">ENG</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <div class="wel-msg text-uppercase d-none d-lg-block">
                    {{ Setting::get('header.top_message', 'FREE Returns. Standard Shipping Orders $99+') }}
                </div>
                <span class="separator d-none d-xl-block"></span>
                <ul class="top-links mega-menu show-arrow d-none d-sm-inline-block">
                    <li class="item-menu narrow"><a href="{{ route('account.dashboard') }}">My Account</a></li>
                    <li class="item-menu narrow"><a href="{{ url('/about') }}">About Us</a></li>
                    <li class="item-menu narrow"><a href="{{ url('/blog') }}">Blog</a></li>
                    <li class="item-menu narrow"><a href="{{ url('/cart') }}">Cart</a></li>
                    <li class="item-menu">
                        @guest
                            <a class="login" href="{{ route('login') }}">Log In</a>
                        @else
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
                        @endguest
                    </li>
                </ul>
                <span class="separator d-none d-xl-block"></span>
                <div class="share-links d-none d-xl-block">
                    @if(Setting::get('social.facebook'))
                        <a target="_blank" rel="nofollow" class="share-facebook icon-facebook" href="{{ Setting::get('social.facebook') }}" title="Facebook"></a>
                    @endif
                    @if(Setting::get('social.twitter'))
                        <a target="_blank" rel="nofollow" class="share-twitter icon-twitter" href="{{ Setting::get('social.twitter') }}" title="Twitter"></a>
                    @endif
                    @if(Setting::get('social.instagram'))
                        <a target="_blank" rel="nofollow" class="share-instagram icon-instagram" href="{{ Setting::get('social.instagram') }}" title="Instagram"></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- End .header-top --}}

    {{-- Header Middle (Sticky) — Demo8: Phone left, Logo center, Icons right --}}
    <div class="header-middle sticky-header" data-sticky-options="{'mobile': true}">
        <div class="container">
            <div class="header-left d-lg-block d-none">
                @if(Setting::get('contact.phone'))
                <div class="header-contact d-none d-lg-flex align-items-center pl-1 mr-lg-5 pr-xl-2">
                    <i class="icon-phone-2"></i>
                    <h6>Call us now<a href="tel:{{ Setting::get('contact.phone') }}" class="text-dark font1">{{ Setting::get('contact.phone') }}</a></h6>
                </div>
                @endif
            </div>

            <div class="header-center">
                <button class="mobile-menu-toggler" type="button">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ url('/') }}" class="logo">
                    @if(Setting::get('appearance.logo'))
                        <img src="{{ asset('storage/' . Setting::get('appearance.logo')) }}" alt="{{ Setting::get('general.site_name', 'Porto Shop') }}" width="104" height="41" />
                    @else
                        <img src="{{ asset('themes/porto/images/logo.png') }}" alt="{{ Setting::get('general.site_name', 'Porto Shop') }}" width="104" height="41" />
                    @endif
                </a>
            </div>

            <div class="header-right">
                {{-- User Icon --}}
                @guest
                    <a href="{{ route('login') }}" class="header-icon header-icon-user"><i class="icon-user-2"></i></a>
                @else
                    <a href="{{ route('account.dashboard') }}" class="header-icon header-icon-user"><i class="icon-user-2"></i></a>
                @endguest

                {{-- Wishlist Icon --}}
                <a href="{{ url('/wishlist') }}" class="header-icon"><i class="icon-wishlist-2"></i></a>

                {{-- Search (Demo8 popup style via Livewire) --}}
                @livewire('live-search')

                {{-- Mini Cart --}}
                @livewire('mini-cart')
            </div>
        </div>
    </div>
    {{-- End .header-middle --}}

    {{-- Header Bottom - Main Navigation (Demo8) --}}
    <div class="header-bottom sticky-header d-none d-lg-flex" data-sticky-options="{'mobile': false}">
        <div class="container">
            <nav class="main-nav w-100">
                <ul class="menu w-100">
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
                    <li>
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
