<footer class="footer appear-animate">
    <div class="footer-middle">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="row row-sm">
                        {{-- Column 1: Contact Info --}}
                        <div class="col-sm-4">
                            <div class="widget">
                                <h4 class="widget-title">{{ Setting::get('footer.col1_title', 'CONTACT INFO') }}</h4>
                                <ul class="contact-info mb-3">
                                    @if(Setting::get('contact.address'))
                                    <li>
                                        <span class="contact-info-label">Address:</span>{{ Setting::get('contact.address') }}
                                    </li>
                                    @endif
                                    @if(Setting::get('contact.phone'))
                                    <li>
                                        <span class="contact-info-label">Phone:</span>Toll Free <a href="tel:{{ Setting::get('contact.phone') }}">{{ Setting::get('contact.phone') }}</a>
                                    </li>
                                    @endif
                                    @if(Setting::get('contact.email'))
                                    <li>
                                        <span class="contact-info-label">Email:</span> <a href="mailto:{{ Setting::get('contact.email') }}">{{ Setting::get('contact.email') }}</a>
                                    </li>
                                    @endif
                                    @if(Setting::get('contact.working_hours'))
                                    <li>
                                        <span class="contact-info-label">Working Hours:</span> {{ Setting::get('contact.working_hours') }}
                                    </li>
                                    @endif
                                </ul>
                                <div class="social-icons">
                                    @if(Setting::get('social.facebook'))
                                        <a href="{{ Setting::get('social.facebook') }}" class="social-icon social-facebook icon-facebook" target="_blank"></a>
                                    @endif
                                    @if(Setting::get('social.twitter'))
                                        <a href="{{ Setting::get('social.twitter') }}" class="social-icon social-twitter icon-twitter" target="_blank"></a>
                                    @endif
                                    @if(Setting::get('social.instagram'))
                                        <a href="{{ Setting::get('social.instagram') }}" class="social-icon social-instagram fab fa-instagram" target="_blank"></a>
                                    @endif
                                    @if(Setting::get('social.linkedin'))
                                        <a href="{{ Setting::get('social.linkedin') }}" class="social-icon social-linkedin fab fa-linkedin-in" target="_blank"></a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Column 2: Quick Links --}}
                        <div class="col-sm-4">
                            <div class="widget pl-sm-1">
                                <h4 class="widget-title">{{ Setting::get('footer.col2_title', 'CUSTOMER SERVICE') }}</h4>
                                <ul class="links">
                                    <li><a href="{{ route('account.dashboard') }}">My Account</a></li>
                                    <li><a href="{{ url('/account/orders') }}">Orders History</a></li>
                                    <li><a href="{{ url('/wishlist') }}">Wishlist</a></li>
                                    <li><a href="{{ url('/cart') }}">Shopping Cart</a></li>
                                    <li><a href="{{ url('/shop') }}">Shop</a></li>
                                    <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>

                        {{-- Column 3: Dynamic Pages (show_in_footer) --}}
                        <div class="col-sm-4">
                            <div class="widget pl-sm-2">
                                <h4 class="widget-title">{{ Setting::get('footer.col3_title', 'INFORMATION') }}</h4>
                                <ul class="links">
                                    @php
                                        $footerPages = \App\Models\Page::active()->showInFooter()->orderBy('sort_order')->get();
                                    @endphp
                                    @forelse($footerPages as $fPage)
                                        <li><a href="{{ $fPage->frontend_url }}">{{ $fPage->title }}</a></li>
                                    @empty
                                        {{-- Fallback links if no pages are configured --}}
                                        <li><a href="{{ url('/about') }}">About Us</a></li>
                                        <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                                    @endforelse
                                    {{-- Always show legal pages --}}
                                    <li><a href="{{ url('/terms') }}">Terms & Conditions</a></li>
                                    <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 4: Newsletter --}}
                <div class="col-lg-3">
                    <div class="widget widget-newsletter">
                        <h4 class="widget-title">{{ Setting::get('footer.newsletter_title', 'Subscribe Newsletter') }}</h4>
                        <p>{{ Setting::get('footer.newsletter_description', 'Get all the latest information on events, sales and offers. Sign up for newsletter:') }}</p>
                        <form action="{{ url('/newsletter/subscribe') }}" method="POST">
                            @csrf
                            <input type="email" name="email" class="form-control" placeholder="Email address" required>
                            <input type="submit" class="btn" value="Go!">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End .footer-middle --}}

    <div class="container">
        <div class="footer-bottom d-sm-flex align-items-center">
            <div class="footer-left">
                <span class="footer-copyright">{{ Setting::get('general.copyright', '© ' . date('Y') . ' ' . Setting::get('general.site_name', 'Shop') . '. All Rights Reserved.') }}</span>
            </div>

            <div class="footer-right ml-auto mt-1 mt-sm-0">
                <div class="payment-icons mr-0">
                    <span class="payment-icon visa" style="background-image: url({{ asset('themes/porto/images/payments/payment-visa.svg') }})"></span>
                    <span class="payment-icon paypal" style="background-image: url({{ asset('themes/porto/images/payments/payment-paypal.svg') }})"></span>
                    <span class="payment-icon stripe" style="background-image: url({{ asset('themes/porto/images/payments/payment-stripe.png') }})"></span>
                    <span class="payment-icon verisign" style="background-image: url({{ asset('themes/porto/images/payments/payment-verisign.svg') }})"></span>
                </div>
            </div>
        </div>
    </div>
</footer>
