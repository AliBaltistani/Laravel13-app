@extends('layouts.admin')
@section('title', 'Banners')
@section('breadcrumb')<li class="active">Banners</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Banners</h4>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Banner</a>
</div>
<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th>Image</th><th>Title</th><th>Position</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($banners as $b)
<tr>
    <td>@if($b->image)<img src="{{ Storage::url($b->image) }}" style="max-height:40px;border-radius:4px;">@else —@endif</td>
    <td><strong>{{ $b->title ?? 'Untitled' }}</strong>@if($b->subtitle)<br><small class="text-muted">{{ $b->subtitle }}</small>@endif</td>
    <td>@php $posLabels = ['home-mid'=>'Homepage — Category Banners','home-instagram'=>'Homepage — Instagram Feed','shop-top'=>'Shop — Top Banner','shop-sidebar'=>'Shop — Sidebar Banner']; @endphp<span class="badge badge-info">{{ $posLabels[$b->position] ?? $b->position }}</span></td>
    <td>{{ $b->sort_order }}</td>
    <td>{!! $b->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>' !!}</td>
    <td>
        <a href="{{ route('admin.banners.edit', $b) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
        <form method="POST" action="{{ route('admin.banners.destroy', $b) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="6" class="text-center text-muted py-4">No banners.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $banners->links() }}</div>
@endsection
