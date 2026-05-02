@php
    $data = $sectionData[$section->key] ?? [];
    $s = $section->settings ?? [];
    $topRated = $data['topRated'] ?? collect();
    $bestSelling = $data['bestSelling'] ?? collect();
    $latestProducts = $data['latestProducts'] ?? collect();
@endphp
<div class="{{ $s['container_class'] ?? 'container' }}">
    <div class="product-widgets row pt-1">
        @if(($s['show_top_rated'] ?? true) && $topRated->count())
        <div class="{{ !empty($s['col_class']) ? $s['col_class'] : 'col-md-4 col-sm-6' }} pb-5 appear-animate" data-animation-name="fadeInRightShorter">
            <h4 class="subtitle text-left text-uppercase">{{ $s['top_rated_title'] ?? 'Top Rated Products' }}</h4>
            <div class="heading-spacer"></div>
            @foreach($topRated as $product)
                @include('partials.product-widget', ['product' => $product])
            @endforeach
        </div>
        @endif

        @if(($s['show_best_selling'] ?? true) && $bestSelling->count())
        <div class="{{ !empty($s['col_class']) ? $s['col_class'] : 'col-md-4 col-sm-6' }} pb-5 appear-animate" data-animation-delay="100" data-animation-duration="1500">
            <h4 class="subtitle text-left text-uppercase">{{ $s['best_selling_title'] ?? 'Best Selling Products' }}</h4>
            <div class="heading-spacer"></div>
            @foreach($bestSelling as $product)
                @include('partials.product-widget', ['product' => $product])
            @endforeach
        </div>
        @endif

        @if(($s['show_latest'] ?? true) && $latestProducts->count())
        <div class="{{ !empty($s['col_class']) ? $s['col_class'] : 'col-md-4 col-sm-6' }} pb-5 appear-animate" data-animation-name="fadeInLeftShorter">
            <h4 class="subtitle text-left text-uppercase">{{ $s['latest_title'] ?? 'Latest Products' }}</h4>
            <div class="heading-spacer"></div>
            @foreach($latestProducts as $product)
                @include('partials.product-widget', ['product' => $product])
            @endforeach
        </div>
        @endif
    </div>
</div>
