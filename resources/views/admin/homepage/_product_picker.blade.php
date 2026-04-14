{{-- Reusable product picker for homepage sections --}}
@php
    $assignedIds = $section->exists ? $section->products->pluck('id')->toArray() : [];
@endphp

<div class="p-3 rounded" style="background:rgba(13,110,253,0.04);border:1px solid rgba(13,110,253,0.1);">
    <h6 class="font-weight-bold mb-2"><i class="fas fa-hand-pointer mr-1"></i> Select Products</h6>
    <p class="text-muted mb-2" style="font-size:12px;">Check the products you want to display in this section. Only active products are shown.</p>

    {{-- Search --}}
    <div class="mb-3">
        <input type="text" class="form-control form-control-sm" id="productSearch" placeholder="Search products..." onkeyup="filterProducts()">
    </div>

    {{-- Product list with checkboxes --}}
    <div style="max-height:350px;overflow-y:auto;border:1px solid #e9ecef;border-radius:6px;background:#fff;" id="productList">
        @forelse($allProducts as $prod)
        <label class="d-flex align-items-center px-3 py-2 mb-0 product-item" style="cursor:pointer;border-bottom:1px solid #f5f5f5;gap:10px;" data-name="{{ strtolower($prod->name) }}" data-sku="{{ strtolower($prod->sku ?? '') }}">
            <input type="checkbox" name="assigned_products[]" value="{{ $prod->id }}"
                {{ in_array($prod->id, $assignedIds) ? 'checked' : '' }}
                style="width:16px;height:16px;flex-shrink:0;">
            @if($prod->mainImage)
            <img src="{{ Storage::url($prod->mainImage->image_path) }}" style="width:36px;height:36px;object-fit:cover;border-radius:4px;flex-shrink:0;">
            @else
            <div style="width:36px;height:36px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-image text-muted" style="font-size:12px;"></i></div>
            @endif
            <div style="flex:1;min-width:0;">
                <div class="font-weight-bold text-truncate" style="font-size:13px;">{{ $prod->name }}</div>
                <div class="text-muted" style="font-size:11px;">
                    {{ $prod->sku ?? 'No SKU' }} · £{{ number_format($prod->price, 2) }}
                    @if($prod->is_featured) <span class="badge badge-warning" style="font-size:9px;">Featured</span> @endif
                    @if($prod->is_new) <span class="badge badge-info" style="font-size:9px;">New</span> @endif
                </div>
            </div>
        </label>
        @empty
        <div class="text-center text-muted py-4">No active products available.</div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between mt-2">
        <small class="text-muted"><span id="selectedCount">{{ count($assignedIds) }}</span> selected</small>
        <div>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.querySelectorAll('#productList input[type=checkbox]').forEach(c=>{c.checked=false});updateCount()">Clear All</button>
        </div>
    </div>
</div>

<script>
function filterProducts() {
    var val = document.getElementById('productSearch').value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(function(item) {
        var name = item.dataset.name || '';
        var sku = item.dataset.sku || '';
        item.style.display = (name.includes(val) || sku.includes(val)) ? '' : 'none';
    });
}
function updateCount() {
    var c = document.querySelectorAll('#productList input[type=checkbox]:checked').length;
    document.getElementById('selectedCount').textContent = c;
}
document.querySelectorAll('#productList input[type=checkbox]').forEach(function(cb) {
    cb.addEventListener('change', updateCount);
});
</script>
