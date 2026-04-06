{{-- Pre-header Promotional Bar (Demo8 style) — fully driven by admin settings --}}
@if(Setting::get('promo.bar_enabled', '0') === '1' && Setting::get('promo.bar_text'))
<div class="pre-header">
    <div>
        <div class="container">
            {!! Setting::get('promo.bar_text') !!}
            @if(Setting::get('promo.bar_note'))
                <small>{{ Setting::get('promo.bar_note') }}</small>
            @endif
        </div>
        <button class="mfp-close"></button>
    </div>
</div>
@endif
