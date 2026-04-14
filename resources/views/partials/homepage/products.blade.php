@php $data = $sectionData[$section->key] ?? []; $products = $data['products'] ?? collect(); $s = $section->settings ?? []; @endphp
@if($products->count())
<div class="{{ $s['container_class'] ?? 'container' }} feature-container">
    <h2 class="subtitle text-center">{{ strtoupper($s['section_title'] ?? 'Featured Products') }}</h2>
    <div class="heading-spacer"></div>
    <div class="row">
        @foreach($products as $product)
        <div class="{{ $s['col_class'] ?? 'col-6 col-sm-4 col-md-3' }} appear-animate" data-animation-delay="100" data-animation-duration="1500">
            @include('partials.product-card', ['product' => $product])
        </div>
        @endforeach
    </div>
</div>
@else
<div class="{{ $s['container_class'] ?? 'container' }} feature-container">
    <h2 class="subtitle text-center">{{ strtoupper($s['section_title'] ?? 'Featured Products') }}</h2>
    <div class="heading-spacer"></div>
    <p class="text-center text-muted py-4">No products yet. Add products from the admin panel.</p>
</div>
@endif
