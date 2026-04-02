{{-- Top Promotional Notice Bar --}}
@if(Setting::get('promo.bar_enabled', '1') === '1')
<div class="top-notice text-white" style="background-color: {{ Setting::get('promo.bar_bg_color', '#0088cc') }};">
    <div class="container text-center">
        <h5 class="d-inline-block mb-0">{!! Setting::get('promo.bar_text', 'GET YOUR $50 COUPON NOW') !!}</h5>
        @if(Setting::get('promo.bar_link1_label'))
            <a href="{{ Setting::get('promo.bar_link1_url', '#') }}" class="category">{{ Setting::get('promo.bar_link1_label') }}</a>
        @endif
        @if(Setting::get('promo.bar_link2_label'))
            <a href="{{ Setting::get('promo.bar_link2_url', '#') }}" class="category ml-2 mr-3">{{ Setting::get('promo.bar_link2_label') }}</a>
        @endif
        <small>* Limited time only.</small>
        <button title="Close (Esc)" type="button" class="mfp-close">×</button>
    </div>
</div>
@endif
