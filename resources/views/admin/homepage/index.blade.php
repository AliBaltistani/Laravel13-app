@extends('layouts.admin')
@section('title', 'Homepage Builder')
@section('breadcrumb')<li class="active">Homepage Builder</li>@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Homepage Builder</h4>
        <small class="text-muted">Manage sections, order, and content of your homepage</small>
    </div>
    <a href="{{ route('admin.homepage.custom.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Custom Section</a>
</div>

<div class="admin-card">
    <div class="card-body p-0">
        <table class="admin-table" id="sections-table">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>Section</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody id="sortable-sections">
                @foreach($sections as $section)
                <tr data-id="{{ $section->id }}">
                    <td><i class="fas fa-grip-vertical text-muted" style="cursor:grab;"></i></td>
                    <td>
                        <strong>{{ $section->title }}</strong>
                        <br><small class="text-muted">{{ $section->key }}</small>
                    </td>
                    <td>
                        @switch($section->type)
                            @case('slider') <span class="badge badge-info"><i class="fas fa-images mr-1"></i>Slider</span> @break
                            @case('banners') <span class="badge badge-warning"><i class="fas fa-flag mr-1"></i>Banners</span> @break
                            @case('products') <span class="badge badge-primary"><i class="fas fa-box mr-1"></i>Products</span> @break
                            @case('sale_banner') <span class="badge badge-danger"><i class="fas fa-percent mr-1"></i>Sale Banner</span> @break
                            @case('widgets') <span class="badge badge-secondary"><i class="fas fa-th-large mr-1"></i>Widgets</span> @break
                            @case('brands') <span class="badge badge-dark"><i class="fas fa-tags mr-1"></i>Brands</span> @break
                            @case('instagram') <span class="badge badge-success"><i class="fab fa-instagram mr-1"></i>Instagram</span> @break
                            @case('custom_html') <span class="badge badge-purple" style="background:#9c27b0;color:#fff;"><i class="fas fa-code mr-1"></i>Custom HTML</span> @break
                        @endswitch
                    </td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="toggle_{{ $section->id }}" {{ $section->is_active ? 'checked' : '' }}
                                onchange="toggleSection({{ $section->id }})">
                            <label class="custom-control-label" for="toggle_{{ $section->id }}">
                                {{ $section->is_active ? 'Active' : 'Hidden' }}
                            </label>
                        </div>
                    </td>
                    <td><span class="badge badge-light">{{ $section->sort_order }}</span></td>
                    <td>
                        <a href="{{ route('admin.homepage.edit', $section) }}" class="btn btn-sm btn-outline-primary" title="Edit Settings"><i class="fas fa-cog"></i></a>
                        @if($section->type === 'custom_html')
                        <form method="POST" action="{{ route('admin.homepage.custom.destroy', $section) }}" class="d-inline" onsubmit="return confirm('Delete this custom section?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="admin-card mt-3">
    <div class="card-body">
        <p class="text-muted mb-2"><i class="fas fa-info-circle mr-1"></i> <strong>Tips:</strong></p>
        <ul class="text-muted" style="font-size:13px;">
            <li>Drag rows to reorder homepage sections</li>
            <li>Toggle the switch to show/hide sections</li>
            <li>Click <i class="fas fa-cog"></i> to edit section-specific settings (columns, titles, limits)</li>
            <li>Built-in sections cannot be deleted, only hidden</li>
        </ul>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
new Sortable(document.getElementById('sortable-sections'), {
    handle: '.fa-grip-vertical',
    animation: 200,
    onEnd: function() {
        var order = [];
        document.querySelectorAll('#sortable-sections tr').forEach(function(row) {
            order.push(row.dataset.id);
        });
        fetch('{{ route("admin.homepage.reorder") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ order: order })
        });
    }
});

function toggleSection(id) {
    fetch('/admin/homepage/' + id + '/toggle', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).then(r => r.json()).then(d => {
        var label = document.querySelector('label[for="toggle_' + id + '"]');
        label.textContent = d.is_active ? 'Active' : 'Hidden';
    });
}
</script>
@endpush
@endsection
