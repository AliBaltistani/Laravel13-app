<div class="mobile-menu-container">
    <div class="mobile-menu-wrapper">
        <span class="mobile-menu-close"><i class="fa fa-times"></i></span>
        <nav class="mobile-nav">
            <ul class="mobile-menu">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>
                    <a href="{{ url('/shop') }}">Categories</a>
                    <ul>
                        @php
                            $mobileCategories = \App\Models\Category::active()->root()->ordered()->get();
                        @endphp
                        @foreach($mobileCategories as $cat)
                            <li>
                                <a href="{{ url('/shop/category/' . $cat->slug) }}">{{ $cat->name }}</a>
                                @if($cat->children->count())
                                <ul>
                                    @foreach($cat->children()->active()->ordered()->take(8)->get() as $child)
                                        <li><a href="{{ url('/shop/category/' . $child->slug) }}">{{ $child->name }}</a></li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li><a href="{{ url('/shop') }}">Products</a></li>
                <li>
                    <a href="#">Pages</a>
                    <ul>
                        <li><a href="{{ url('/wishlist') }}">Wishlist</a></li>
                        <li><a href="{{ url('/cart') }}">Shopping Cart</a></li>
                        <li><a href="{{ url('/checkout') }}">Checkout</a></li>
                        <li><a href="{{ route('account.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ url('/page/about-us') }}">About Us</a></li>
                        <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @endguest
                    </ul>
                </li>
                <li><a href="{{ url('/blog') }}">Blog</a></li>
                <li><a href="{{ url('/contact') }}">Contact Us</a></li>
            </ul>

            <ul class="mobile-menu mt-2 mb-2">
                @if(Setting::get('header.show_special_offer', '1') === '1' && Setting::get('header.special_offer_text'))
                <li class="border-0"><a href="{{ Setting::get('header.special_offer_url', '/shop') }}">{{ Setting::get('header.special_offer_text', 'Special Offer!') }}</a></li>
                @endif
            </ul>

            <ul class="mobile-menu">
                @guest
                    <li><a href="{{ route('login') }}">My Account</a></li>
                @else
                    <li><a href="{{ route('account.dashboard') }}">My Account</a></li>
                @endguest
                <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                <li><a href="{{ url('/blog') }}">Blog</a></li>
                <li><a href="{{ url('/wishlist') }}">My Wishlist</a></li>
                <li><a href="{{ url('/cart') }}">Cart</a></li>
                @guest
                    <li><a href="{{ route('login') }}" class="login-link">Log In</a></li>
                @else
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a></li>
                @endguest
            </ul>
        </nav>

        <form class="search-wrapper mb-2" action="{{ url('/shop') }}">
            <input type="text" class="form-control mb-0" name="q" placeholder="Search..." required />
            <button class="btn icon-search text-white bg-transparent p-0" type="submit"></button>
        </form>

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
