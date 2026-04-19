@extends('layouts.admin')
@section('title', 'Pages')
@section('breadcrumb')<li class="active">Pages</li>@endsection
@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">CMS Pages</h4>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Page</a>
</div>

{{-- Search --}}
<div class="admin-card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex align-items-center" style="gap:10px;">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search pages..." value="{{ request('search') }}" style="max-width:250px;">
            <select name="status" class="form-control form-control-sm" style="max-width:140px;">
                <option value="">All Status</option>
                <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
            </select>
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.pages.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="admin-card"><div class="card-body p-0"><table class="admin-table">
<thead><tr><th style="width:40px;"></th><th>Title</th><th>Slug</th><th>Template</th><th>Location</th><th>Media</th><th>Sections</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($pages as $p)
<tr>
    <td>
        @if($p->image)
        <img src="{{ Storage::url($p->image) }}" style="width:36px;height:36px;object-fit:cover;border-radius:4px;">
        @else
        <div style="width:36px;height:36px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-file-alt text-muted"></i></div>
        @endif
    </td>
    <td><a href="{{ route('admin.pages.edit', $p) }}" class="text-dark font-weight-bold">{{ $p->title }}</a></td>
    <td class="text-muted">/{{ $p->slug }}</td>
    <td><span class="badge badge-light">{{ ucfirst($p->template ?? 'default') }}</span></td>
    <td>
        @if($p->show_in_header)<span class="badge badge-primary" title="Shows in header"><i class="fas fa-arrow-up mr-1"></i>Header</span>@endif
        @if($p->show_in_footer)<span class="badge badge-success" title="Shows in footer"><i class="fas fa-arrow-down mr-1"></i>Footer</span>@endif
        @if(!$p->show_in_header && !$p->show_in_footer)<span class="badge badge-light text-muted">None</span>@endif
    </td>
    <td><span class="badge badge-info">{{ $p->images_count ?? 0 }} <i class="fas fa-image ml-1"></i></span></td>
    <td><span class="badge badge-secondary">{{ $p->sections_count ?? 0 }}</span></td>
    <td>{!! $p->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>' !!}</td>
    <td>
        <a href="{{ route('page.show', $p->slug) }}" class="btn btn-sm btn-outline-info" target="_blank" title="Preview"><i class="fas fa-eye"></i></a>
        <a href="{{ route('admin.pages.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
        <form method="POST" action="{{ route('admin.pages.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete this page?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="9" class="text-center text-muted py-4">No pages found.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $pages->links() }}</div>
@endsection
