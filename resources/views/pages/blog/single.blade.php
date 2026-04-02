@extends('layouts.app')

@section('meta_title', ($post->meta_title ?? $post->title) . ' - Blog - ' . Setting::get('general.site_name', 'Porto Shop'))
@section('meta_description', $post->meta_description ?? $post->excerpt)

@section('content')
    @include('partials.breadcrumb', [
        'title' => $post->title,
        'items' => [['label' => 'Blog', 'url' => url('/blog')], ['label' => $post->postCategory?->name ?? 'Post', 'url' => $post->postCategory ? url('/blog/category/' . $post->postCategory->slug) : '#']]
    ])

    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <article class="post-single">
                    @if($post->featured_image)
                        <figure class="post-media">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="width:100%; max-height:500px; object-fit:cover;">
                        </figure>
                    @endif

                    <div class="post-body">
                        <div class="post-meta">
                            <span><i class="far fa-user"></i> by <a href="#">{{ $post->user?->full_name ?? 'Admin' }}</a></span>
                            <span class="meta-separator">|</span>
                            <span><i class="far fa-calendar-alt"></i> {{ $post->published_at?->format('M d, Y') }}</span>
                            @if($post->postCategory)
                                <span class="meta-separator">|</span>
                                <span><i class="far fa-folder"></i> <a href="{{ url('/blog/category/' . $post->postCategory->slug) }}">{{ $post->postCategory->name }}</a></span>
                            @endif
                            <span class="meta-separator">|</span>
                            <span><i class="far fa-eye"></i> {{ $post->views_count }} views</span>
                        </div>

                        <h2 class="post-title">{{ $post->title }}</h2>

                        <div class="post-content">
                            {!! $post->content !!}
                        </div>

                        @if($post->tags->count())
                        <div class="post-footer">
                            <div class="post-tags">
                                <span>Tags:</span>
                                @foreach($post->tags as $tag)
                                    <a href="{{ url('/blog/tag/' . $tag->slug) }}">{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="post-share">
                            <h3 class="d-inline-block mr-2">Share this post:</h3>
                            <div class="social-icons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="social-icon social-facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" class="social-icon social-twitter" target="_blank"><i class="fab fa-twitter"></i></a>
                                <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode(url()->current()) }}" class="social-icon social-mail" target="_blank"><i class="icon-mail-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- Comments Section --}}
                <div class="comments-section" id="comments">
                    <h3 class="mb-3">{{ $post->approvedComments->count() }} Comments</h3>

                    @foreach($post->approvedComments->whereNull('parent_id') as $comment)
                        <div class="comment mb-3">
                            <figure class="comment-media">
                                <img src="{{ asset('themes/porto/images/blog/author.jpg') }}" alt="{{ $comment->name }}" width="60" height="60" class="rounded-circle">
                            </figure>
                            <div class="comment-body">
                                <div class="comment-header">
                                    <strong class="comment-author">{{ $comment->name }}</strong>
                                    <span class="comment-date ml-2 text-muted small">{{ $comment->created_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                                <div class="comment-content">
                                    <p>{{ $comment->body }}</p>
                                </div>
                            </div>

                            {{-- Replies --}}
                            @if($comment->replies && $comment->replies->count())
                                @foreach($comment->replies as $reply)
                                    <div class="comment comment-reply ml-5 mt-2">
                                        <figure class="comment-media">
                                            <img src="{{ asset('themes/porto/images/blog/author.jpg') }}" alt="{{ $reply->name }}" width="50" height="50" class="rounded-circle">
                                        </figure>
                                        <div class="comment-body">
                                            <div class="comment-header">
                                                <strong class="comment-author">{{ $reply->name }}</strong>
                                                <span class="comment-date ml-2 text-muted small">{{ $reply->created_at->format('M d, Y \a\t h:i A') }}</span>
                                            </div>
                                            <div class="comment-content"><p>{{ $reply->body }}</p></div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach

                    {{-- Comment Form --}}
                    @livewire('comment-form', ['postId' => $post->id])
                </div>

                {{-- Related Posts --}}
                @if($relatedPosts->count())
                <div class="related-posts mt-4">
                    <h3>Related Posts</h3>
                    <div class="row">
                        @foreach($relatedPosts as $rp)
                            <div class="col-md-4">
                                <article class="entry entry-grid">
                                    @if($rp->featured_image)
                                        <figure class="entry-media">
                                            <a href="{{ url('/blog/' . $rp->slug) }}">
                                                <img src="{{ asset('storage/' . $rp->featured_image) }}" alt="{{ $rp->title }}" style="width:100%; height:180px; object-fit:cover;">
                                            </a>
                                        </figure>
                                    @endif
                                    <div class="entry-body">
                                        <h5 class="entry-title"><a href="{{ url('/blog/' . $rp->slug) }}">{{ $rp->title }}</a></h5>
                                        <small class="text-muted">{{ $rp->published_at?->format('M d, Y') }}</small>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="sidebar col-lg-3">
                <div class="widget widget-post">
                    <h4 class="widget-title">Recent Posts</h4>
                    <ul class="simple-post-list">
                        @foreach($recentPosts as $recent)
                            <li>
                                <div class="post-info">
                                    <a href="{{ url('/blog/' . $recent->slug) }}">{{ $recent->title }}</a>
                                    <div class="post-meta">{{ $recent->published_at?->format('M d, Y') }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="widget widget-categories">
                    <h4 class="widget-title">Blog Categories</h4>
                    <ul class="cat-list">
                        @foreach($categories as $cat)
                            <li><a href="{{ url('/blog/category/' . $cat->slug) }}">{{ $cat->name }} <span class="products-count">({{ $cat->posts_count }})</span></a></li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </div>
@endsection
