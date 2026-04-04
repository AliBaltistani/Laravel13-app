<footer class="footer bg-dark">
    <div class="footer-middle">
        <div class="container">
            <div class="row">
                {{-- Column 1: Contact Info --}}
                <div class="col-lg-3 col-sm-6">
                    <div class="widget">
                        <h4 class="widget-title">Contact Info</h4>
                        <ul class="contact-info">
                            @if(Setting::get('contact.address'))
                            <li>
                                <span class="contact-info-label">Address:</span>{{ Setting::get('contact.address') }}
                            </li>
                            @endif
                            @if(Setting::get('contact.phone'))
                            <li>
                                <span class="contact-info-label">Phone:</span><a href="tel:{{ Setting::get('contact.phone') }}">{{ Setting::get('contact.phone') }}</a>
                            </li>
                            @endif
                            @if(Setting::get('contact.email'))
                            <li>
                                <span class="contact-info-label">Email:</span> <a href="mailto:{{ Setting::get('contact.email') }}">{{ Setting::get('contact.email') }}</a>
                            </li>
                            @endif
                            @if(Setting::get('contact.working_hours'))
                            <li>
                                <span class="contact-info-label">Working Days/Hours:</span>{{ Setting::get('contact.working_hours') }}
                            </li>
                            @endif
                        </ul>
                        <div class="social-icons">
                            @if(Setting::get('social.facebook'))
                                <a href="{{ Setting::get('social.facebook') }}" class="social-icon social-facebook icon-facebook" target="_blank" title="Facebook"></a>
                            @endif
                            @if(Setting::get('social.twitter'))
                                <a href="{{ Setting::get('social.twitter') }}" class="social-icon social-twitter icon-twitter" target="_blank" title="Twitter"></a>
                            @endif
                            @if(Setting::get('social.instagram'))
                                <a href="{{ Setting::get('social.instagram') }}" class="social-icon social-instagram icon-instagram" target="_blank" title="Instagram"></a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Column 2: Customer Service --}}
                <div class="col-lg-3 col-sm-6">
                    <div class="widget">
                        <h4 class="widget-title">Customer Service</h4>
                        <ul class="links">
                            <li><a href="{{ url('/page/faq') }}">Help & FAQs</a></li>
                            <li><a href="{{ url('/account/orders') }}">Order Tracking</a></li>
                            <li><a href="{{ url('/page/shipping') }}">Shipping & Delivery</a></li>
                            <li><a href="{{ url('/account/orders') }}">Orders History</a></li>
                            <li><a href="{{ url('/shop') }}">Advanced Search</a></li>
                            <li><a href="{{ route('account.dashboard') }}">My Account</a></li>
                            <li><a href="{{ url('/about') }}">About Us</a></li>
                            <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Column 3: Popular Tags --}}
                <div class="col-lg-3 col-sm-6">
                    <div class="widget">
                        <h4 class="widget-title">Popular Tags</h4>
                        @php
                            $footerTags = \App\Models\Tag::take(12)->get();
                        @endphp
                        @if($footerTags->count())
                        <div class="tagcloud">
                            @foreach($footerTags as $tag)
                                <a href="{{ url('/shop?tag=' . $tag->slug) }}">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                        @else
                            <p class="text-muted">No tags yet.</p>
                        @endif
                    </div>
                </div>

                {{-- Column 4: Newsletter --}}
                <div class="col-lg-3 col-sm-6">
                    <div class="widget widget-newsletter">
                        <h4 class="widget-title">{{ Setting::get('footer.newsletter_title', 'Subscribe Newsletter') }}</h4>
                        <p>{{ Setting::get('footer.newsletter_description', 'Get all the latest information on events, sales and offers. Sign up for newsletter:') }}</p>
                        <form action="{{ url('/newsletter/subscribe') }}" method="POST" class="mb-0">
                            @csrf
                            <input type="email" name="email" class="form-control m-b-3" placeholder="Email address" required>
                            <input type="submit" class="btn btn-primary shadow-none" value="Subscribe">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="footer-bottom">
            <div class="container d-sm-flex align-items-center">
                <div class="footer-left">
                    <span class="footer-copyright">{{ Setting::get('general.copyright', '© ' . date('Y') . ' ' . Setting::get('general.site_name', 'Shop') . '. All Rights Reserved.') }}</span>
                </div>

                <div class="footer-right ml-auto mt-1 mt-sm-0">
                    <div class="payment-icons">
                        <span class="payment-icon visa" style="background-image: url({{ asset('themes/porto/images/payments/payment-visa.svg') }})"></span>
                        <span class="payment-icon paypal" style="background-image: url({{ asset('themes/porto/images/payments/payment-paypal.svg') }})"></span>
                        <span class="payment-icon stripe" style="background-image: url({{ asset('themes/porto/images/payments/payment-stripe.png') }})"></span>
                        <span class="payment-icon verisign" style="background-image: url({{ asset('themes/porto/images/payments/payment-verisign.svg') }})"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
