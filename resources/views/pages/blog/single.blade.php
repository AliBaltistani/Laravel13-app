@extends('layouts.app')

@section('meta_title', ($post->meta_title ?? $post->title) . ' - Blog - ' . Setting::get('general.site_name', 'Porto Shop'))
@section('meta_description', $post->meta_description ?? $post->excerpt)

@push('styles')
<style>
    .custom-page-header { background-color: #f8f8f8; padding: 45px 0 50px; }
    .custom-breadcrumb { justify-content: center; background: transparent; padding: 0; margin-bottom: 15px; font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
    .custom-breadcrumb .breadcrumb-item a { color: #dc3545; text-decoration: none; }
    .custom-breadcrumb .breadcrumb-item a:hover { color: #c82333; }
    .custom-breadcrumb .breadcrumb-item.active { color: #333; }
    .custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 15px; line-height: 1; vertical-align: middle; color: #999; margin: 0 8px; }
    
    .post-hero-title { font-size: 2.8rem; font-weight: 800; color: #222529; font-family: 'Poppins', sans-serif; line-height: 1.2; }
    .post-meta-top { margin-bottom: 25px; color: #777; font-size: 12px; font-weight: 500; }
    .post-meta-top a { color: #0d6efd; text-decoration: none; }
    .post-meta-top i { margin-right: 4px; }
    .meta-sep { margin: 0 12px; color: #ccc; }
    
    .post-title-inline { font-size: 1.7rem; font-weight: 800; color: #222529; margin: 25px 0 15px; font-family: 'Poppins', sans-serif; }
    
    .post-content p { font-size: 15px; line-height: 1.8; color: #666; margin-bottom: 1.5rem; font-weight: 400; }
    .post-content h1, .post-content h2, .post-content h3 { font-family: 'Poppins', sans-serif; font-weight: 700; color: #222529; margin-top: 1.5rem; margin-bottom: 1rem; }
    
    .post-tags-span { font-size: 13px; color: #666; font-weight: 500; }
    .post-tag-link { font-size: 13px; color: #0d6efd; font-weight: 600; text-decoration: none; margin-left: 5px; }
    .post-tag-link:hover { text-decoration: underline; }

    .social-btn { display: inline-flex; width: 32px; height: 32px; align-items: center; justify-content: center; color: #fff !important; border-radius: 4px; margin-right: 6px; font-size: 14px; transition: opacity 0.3s; }
    .social-btn:hover { opacity: 0.8; }
    .social-fb { background: #3b5998; }
    .social-tw { background: #1da1f2; }
    .social-mail { background: #dd4b39; }

    .sidebar-widget-title { font-size: 15px; font-weight: 800; color: #222; text-transform: uppercase; margin-bottom: 20px; border-bottom: none; font-family: 'Poppins', sans-serif; }
    .sidebar .recent-posts-list { list-style: none; padding: 0; margin: 0; }
    .sidebar .recent-posts-list li { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
    .sidebar .recent-posts-list li:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .sidebar .recent-posts-list a { color: #dc3545; font-size: 13px; font-weight: 600; line-height: 1.4; display: block; text-decoration: none; margin-bottom: 4px; }
    .sidebar .recent-posts-list a:hover { color: #c82333; text-decoration: underline; }
    .sidebar .recent-posts-list .post-date { font-size: 11px; color: #888; font-weight: 500; }

    .sidebar .cat-list li { margin-bottom: 12px; }
    .sidebar .cat-list a { color: #666; font-size: 13px; text-decoration: none; font-weight: 500; }
    .sidebar .cat-list a:hover { color: #dc3545; }
    .sidebar .cat-list .products-count { float: right; color: #999; font-size: 12px; }

    /* Comment Section Styles */
    .comment-list-wrapper { margin-top: 50px; padding-top: 0; }
    .comment-block { display: flex; margin-bottom: 25px; }
    .comment-block .avatar { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 20px; }
    .comment-block.reply { margin-left: 80px; }
    .comment-block.reply .avatar { width: 50px; height: 50px; }
    .comment-details .name { font-size: 14px; font-weight: 700; color: #222; margin-bottom: 0px; font-family: 'Poppins', sans-serif;}
    .comment-details .date { font-size: 11px; color: #999; margin-left: 10px; font-weight: 500; }
    .comment-details .text { font-size: 14px; color: #555; line-height: 1.6; margin-top: 3px; }
</style>
@endpush

@section('content')
    <div class="custom-page-header">
        <div class="container text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/blog') }}">BLOG</a></li>
                    @if($post->postCategory)
                        <li class="breadcrumb-item active" aria-current="page">{{ strtoupper($post->postCategory->name) }}</li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">POST</li>
                    @endif
                </ol>
            </nav>
            <h1 class="post-hero-title mx-auto" style="max-width: 900px;">{{ $post->title }}</h1>
        </div>
    </div>

    <div class="container mt-5 mb-5 pb-4">
        <div class="row">
            <div class="col-lg-9 pr-lg-5">
                <article class="post-single">
                    
                    <div class="post-meta-top border-bottom pb-3 mb-4">
                        <span><i class="far fa-user"></i> by <a href="#">{{ $post->user?->full_name ?? 'Super Admin' }}</a></span>
                        <span class="meta-sep">|</span>
                        <span><i class="far fa-calendar-alt"></i> {{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}</span>
                        @if($post->postCategory)
                            <span class="meta-sep">|</span>
                            <span><i class="far fa-folder"></i> <a href="{{ url('/blog/category/' . $post->postCategory->slug) }}">{{ $post->postCategory->name }}</a></span>
                        @endif
                        <span class="meta-sep">|</span>
                        <span><i class="far fa-eye"></i> {{ $post->views_count }} Views</span>
                    </div>

                    <h2 class="post-title-inline">{{ $post->title }}</h2>
                    <p style="color: #666; font-size: 14px; margin-bottom: 30px;">
                        {{ $post->excerpt ?? strip_tags(Str::limit($post->content, 120)) }}
                    </p>

                    @if($post->image)
                        <figure class="post-media mb-5">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" style="width:100%; max-height:500px; object-fit:cover; border-radius: 8px;">
                        </figure>
                    @endif

                    <div class="post-content">
                        {!! $post->content !!}
                    </div>

                    @if($post->tags->count())
                    <div class="post-tags-wrapper mt-5 pt-3">
                        <span class="post-tags-span">Tags:</span>
                        @foreach($post->tags as $tag)
                            <a href="{{ url('/blog/tag/' . $tag->slug) }}" class="post-tag-link">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                    @endif

                    <div class="post-share mt-5 border-top pt-4">
                        <h3 style="font-size: 20px; font-family: 'Poppins', sans-serif; font-weight: 700; color: #222; margin-bottom: 15px;">Share this post:</h3>
                        <div class="social-icons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="social-btn social-fb" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" class="social-btn social-tw" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode(url()->current()) }}" class="social-btn social-mail" target="_blank"><i class="far fa-envelope"></i></a>
                        </div>
                    </div>
                </article>

                {{-- Comments Section --}}
                <div class="comment-list-wrapper" id="comments">
                    <h3 style="font-size: 20px; font-family: 'Poppins', sans-serif; font-weight: 800; color: #222; margin-bottom: 30px;">
                        {{ $post->approvedComments->count() }} Comments
                    </h3>

                    @foreach($post->approvedComments->whereNull('parent_id') as $comment)
                        <div class="comment-block">
                            <img src="{{ asset('themes/porto/images/blog/author.jpg') }}" alt="{{ $comment->name }}" class="avatar">
                            <div class="comment-details">
                                <div class="d-flex align-items-baseline">
                                    <h4 class="name">{{ $comment->name }}</h4>
                                    <span class="date">{{ $comment->created_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                                <p class="text">{{ $comment->body }}</p>
                            </div>
                        </div>

                        {{-- Replies --}}
                        @if($comment->replies && $comment->replies->count())
                            @foreach($comment->replies as $reply)
                                <div class="comment-block reply">
                                    <img src="{{ asset('themes/porto/images/blog/author.jpg') }}" alt="{{ $reply->name }}" class="avatar">
                                    <div class="comment-details">
                                        <div class="d-flex align-items-baseline">
                                            <h4 class="name">{{ $reply->name }}</h4>
                                            <span class="date">{{ $reply->created_at->format('M d, Y \a\t h:i A') }}</span>
                                        </div>
                                        <p class="text">{{ $reply->body }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Livewire Comment Form --}}
                    @livewire('comment-form', ['postId' => $post->id])
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="sidebar col-lg-3">
                <div class="widget mb-5">
                    <h4 class="sidebar-widget-title">Recent Posts</h4>
                    <ul class="recent-posts-list">
                        @foreach($recentPosts as $recent)
                            <li>
                                <a href="{{ url('/blog/' . $recent->slug) }}">{{ $recent->title }}</a>
                                <div class="post-date">{{ $recent->published_at?->format('M d, Y') ?? $recent->created_at->format('M d, Y') }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="widget mb-5">
                    <h4 class="sidebar-widget-title">Blog Categories</h4>
                    <ul class="cat-list list-unstyled">
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ url('/blog/category/' . $cat->slug) }}">
                                    {{ $cat->name }} <span class="products-count">({{ $cat->posts_count }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </div>
@endsection
