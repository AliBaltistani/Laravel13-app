<div class="newsletter-popup mfp-hide bg-img" id="newsletter-popup-form" style="background: #f1f1f1 no-repeat center/cover url({{ \App\Models\Setting::get('appearance.newsletter_bg') ? asset('storage/' . \App\Models\Setting::get('appearance.newsletter_bg')) : asset('themes/porto/images/newsletter_popup_bg.jpg') }})" wire:ignore.self>
    <div class="newsletter-popup-content">
        <img src="{{ \App\Models\Setting::get('appearance.logo') ? asset('storage/' . \App\Models\Setting::get('appearance.logo')) : asset('themes/porto/images/logo.png') }}" alt="{{ \App\Models\Setting::get('general.site_name', 'Logo') }}" class="logo-newsletter" style="max-height: 44px; width: auto;">
        <h2 class="text-uppercase" style="color: #222529;">{{ \App\Models\Setting::get('general.newsletter_popup_title', 'GET YOUR $50 COUPON NOW') }}</h2>

        @if(!$couponCode)
            <p>
                {{ \App\Models\Setting::get('general.newsletter_popup_subtitle', 'Subscribe to the Porto mailing list to receive updates on new arrivals, special offers and our promotions.') }}
            </p>

            <form wire:submit.prevent="subscribe">
                <div class="input-group">
                    <input type="email" class="form-control" wire:model="email" placeholder="Your email address" required />
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
            @error('email') <small class="text-danger mt-2 d-block">{{ $message }}</small> @enderror
        @else
            <p class="text-success mt-3" style="font-size: 1.5rem; font-weight: 700; background: #fff; padding: 10px; border-radius: 5px; border: 2px dashed #ccc;">
                {{ $couponCode }}
            </p>
            <p class="mt-2">{{ $successMessage }}</p>
            <div class="mt-3">
                <button class="btn btn-primary w-100" type="button" onclick="$.magnificPopup.close();">Shop Now</button>
            </div>
        @endif

        <div class="newsletter-subscribe">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" value="0" id="show-again" />
                <label for="show-again" class="custom-control-label">
                    Don't show this popup again
                </label>
            </div>
        </div>
    </div>
    <!-- End .newsletter-popup-content -->

    <button title="Close (Esc)" type="button" class="mfp-close" onclick="$.magnificPopup.close();">
        ×
    </button>
</div>
