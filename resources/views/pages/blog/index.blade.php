@extends('layouts.app')

@section('meta_title', (isset($category) ? $category->name . ' - ' : (isset($tag) ? 'Tag: ' . $tag->name . ' - ' : '')) . 'Blog - ' . Setting::get('general.site_name', 'Porto Shop'))

@push('styles')
<style>
    .custom-page-header { background-color: #f8f8f8; padding: 45px 0 50px; }
    .custom-breadcrumb { justify-content: center; background: transparent; padding: 0; margin-bottom: 15px; font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
    .custom-breadcrumb .breadcrumb-item a { color: #dc3545; text-decoration: none; }
    .custom-breadcrumb .breadcrumb-item a:hover { color: #c82333; }
    .custom-breadcrumb .breadcrumb-item.active { color: #333; }
    .custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 15px; line-height: 1; vertical-align: middle; color: #999; margin: 0 8px; }
    
    .post-hero-title { font-size: 2.8rem; font-weight: 800; color: #222529; font-family: 'Poppins', sans-serif; line-height: 1.2; }

    .blog-card-ui {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .blog-card-ui:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .read-details-link:hover {
        color: #b58032 !important;
    }
    .read-details-link:hover span {
        background-color: #b58032 !important;
    }

    .sidebar-widget-title { font-size: 15px; font-weight: 800; color: #222; text-transform: uppercase; margin-bottom: 20px; border-bottom: none; font-family: 'Poppins', sans-serif; }
    .sidebar .recent-posts-list { list-style: none; padding: 0; margin: 0; }
    .sidebar .recent-posts-list li { margin-bottom: 20px; padding-bottom: 0; display: flex; align-items: start; gap: 15px; }
    .sidebar .recent-posts-list a { color: #dc3545; font-size: 13px; font-weight: 600; line-height: 1.4; display: block; text-decoration: none; margin-bottom: 4px; }
    .sidebar .recent-posts-list a:hover { color: #c82333; text-decoration: underline; }
    .sidebar .recent-posts-list .post-date { font-size: 11px; color: #888; font-weight: 500; }

    .sidebar .cat-list li { margin-bottom: 12px; }
    .sidebar .cat-list a { color: #666; font-size: 13px; text-decoration: none; font-weight: 500; }
    .sidebar .cat-list a:hover { color: #dc3545; }
    .sidebar .cat-list .products-count { float: right; color: #999; font-size: 12px; }
    
    .sidebar .tags a.tag { display: inline-block; padding: 5px 12px; font-size: 12px; border: 1px solid #ddd; border-radius: 4px; margin: 0 4px 8px 0; color: #666; text-decoration: none; transition: 0.2s; }
    .sidebar .tags a.tag:hover, .sidebar .tags a.tag.active { background: #dc3545; color: #fff; border-color: #dc3545; }
    
    /* Pagination Overrides */
    .toolbox-pagination { margin-top: 30px; display: flex; justify-content: center; }
    .pagination .page-item .page-link { color: #333; font-weight: 600; font-size: 14px; border: 1px solid #ddd; padding: 8px 16px; margin: 0 3px; border-radius: 4px; }
    .pagination .page-item.active .page-link { background-color: #000; border-color: #000; color: #fff; }
    .pagination .page-item .page-link:hover { background-color: #f4f4f4; }
</style>
@endpush

@section('content')
    <div class="custom-page-header">
        <div class="container text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></li>
                    @if(isset($category) || isset($tag))
                        <li class="breadcrumb-item"><a href="{{ url('/blog') }}">BLOG</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ strtoupper(isset($category) ? $category->name : $tag->name) }}</li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">BLOG</li>
                    @endif
                </ol>
            </nav>
            <h1 class="post-hero-title mx-auto">Blog</h1>
        </div>
    </div>

    <div class="container mt-5 mb-5 pb-4">
        <div class="row">
            {{-- Blog Posts --}}
            <div class="col-lg-9 pr-lg-5">
                <div class="row">
                    @forelse($posts as $post)
                        <div class="col-md-6 mb-4 d-flex">
                            <div class="card shadow-sm border-0 flex-fill blog-card-ui" style="border-radius: 16px; overflow: hidden; background: #fff;">
                                @if($post->image)
                                    <a href="{{ url('/blog/' . $post->slug) }}" class="d-block">
                                        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="width: 100%; height: 220px; object-fit: cover; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                                    </a>
                                @endif
                                <div class="card-body p-4 d-flex flex-column">
                                    <h5 class="card-title font-weight-bold mb-3" style="color: #2b3a4a; font-size: 1.25rem; font-family: 'Inter', sans-serif; line-height: 1.4;">
                                        <a href="{{ url('/blog/' . $post->slug) }}" class="text-dark text-decoration-none">{{ $post->title }}</a>
                                    </h5>
                                    
                                    <p class="card-text text-muted mb-4" style="font-size: 0.95rem; line-height: 1.6; color: #6c757d;">
                                        {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 110) }}
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <a href="{{ url('/blog/' . $post->slug) }}" class="read-details-link font-weight-bold" style="color: #dca54a; text-decoration: none; font-size: 14px; position: relative; padding-bottom: 3px; display: inline-block;">
                                            Read Details &raquo;
                                            <span style="position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background-color: #dca54a;"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 w-100">
                            <h4 class="text-muted">No posts found</h4>
                            <a href="{{ url('/blog') }}" class="btn btn-outline-dark mt-2">View All Posts</a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($posts->hasPages())
                    <nav class="toolbox toolbox-pagination">
                        {{ $posts->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </nav>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="sidebar col-lg-3">
                {{-- Recent Posts --}}
                <div class="widget mb-5">
                    <h4 class="sidebar-widget-title">Recent Posts</h4>
                    <ul class="recent-posts-list">
                        @foreach($recentPosts as $recent)
                            <li>
                                @if($recent->image)
                                    <div class="post-media" style="flex-shrink: 0;">
                                        <a href="{{ url('/blog/' . $recent->slug) }}">
                                            <img src="{{ asset('storage/' . $recent->image) }}" alt="{{ $recent->title }}" width="65" style="border-radius: 4px; object-fit: cover; height: 65px;">
                                        </a>
                                    </div>
                                @endif
                                <div class="post-info">
                                    <a href="{{ url('/blog/' . $recent->slug) }}">{{ $recent->title }}</a>
                                    <div class="post-date">{{ $recent->published_at?->format('M d, Y') ?? $recent->created_at->format('M d, Y') }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Categories --}}
                <div class="widget mb-5">
                    <h4 class="sidebar-widget-title">Blog Categories</h4>
                    <ul class="cat-list list-unstyled">
                        @foreach($categories as $cat)
                            <li {{ isset($category) && $category->id == $cat->id ? 'class=active' : '' }}>
                                <a href="{{ url('/blog/category/' . $cat->slug) }}">
                                    {{ $cat->name }} <span class="products-count">({{ $cat->posts_count }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Tags --}}
                @if(isset($tags) && $tags->count())
                <div class="widget mb-5">
                    <h4 class="sidebar-widget-title">Tags</h4>
                    <div class="tags">
                        @foreach($tags as $t)
                            <a href="{{ url('/blog/tag/' . $t->slug) }}" class="tag {{ isset($tag) && $tag->id == $t->id ? 'active' : '' }}">{{ $t->name }}</a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </div>
@endsection
