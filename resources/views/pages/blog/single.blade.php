@extends('layouts.app')

@section('meta_title', ($post->meta_title ?? $post->title) . ' - Blog - ' . Setting::get('general.site_name', 'Porto Shop'))
@section('meta_description', $post->meta_description ?? $post->excerpt)

@section('content')
    @include('partials.breadcrumb', [
        'title' => $post->title,
        'items' => [['label' => 'Blog', 'url' => url('/blog')], ['label' => $post->postCategory?->name ?? 'Post', 'url' => $post->postCategory ? url('/blog/category/' . $post->postCategory->slug) : '#']]
    ])

    <div class="container single-post-container">
        <div class="row">
            <div class="col-lg-8">
                <article class="single-post-card" id="single-post">
                    @if($post->image)
                        <div class="single-post-hero-img">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                            <div class="single-post-hero-overlay"></div>
                        </div>
                    @endif

                    <div class="single-post-body">
                        <div class="single-post-meta-bar">
                            <div class="single-post-meta-item"><i class="far fa-user"></i> <a href="#">{{ $post->user?->full_name ?? 'Admin' }}</a></div>
                            <div class="single-post-meta-item"><i class="far fa-calendar-alt"></i> {{ $post->published_at?->format('M d, Y') }}</div>
                            @if($post->postCategory)
                            <div class="single-post-meta-item"><i class="far fa-folder"></i> <a href="{{ url('/blog/category/' . $post->postCategory->slug) }}">{{ $post->postCategory->name }}</a></div>
                            @endif
                            <div class="single-post-meta-item"><i class="far fa-eye"></i> {{ $post->views_count }} views</div>
                        </div>

                        <h1 class="single-post-title">{{ $post->title }}</h1>
                        <div class="single-post-content">{!! $post->content !!}</div>

                        @if($post->tags->count())
                        <div class="single-post-tags-bar">
                            <span class="tags-label"><i class="fas fa-tags"></i> Tags:</span>
                            @foreach($post->tags as $tag)
                                <a href="{{ url('/blog/tag/' . $tag->slug) }}" class="single-post-tag">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                        @endif

                        <div class="single-post-share-bar">
                            <span class="share-label">Share this post:</span>
                            <div class="share-btns">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn share-facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn share-twitter"><i class="fab fa-twitter"></i></a>
                                <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode(url()->current()) }}" target="_blank" class="share-btn share-email"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- Comments --}}
                <div class="single-post-comments" id="comments">
                    <h3 class="comments-heading"><i class="far fa-comments"></i> {{ $post->approvedComments->count() }} Comments</h3>
                    @foreach($post->approvedComments->whereNull('parent_id') as $comment)
                        <div class="comment-item">
                            <div class="comment-avatar"><img src="{{ asset('themes/porto/images/blog/author.jpg') }}" alt="{{ $comment->name }}"></div>
                            <div class="comment-content-box">
                                <div class="comment-header-row">
                                    <strong>{{ $comment->name }}</strong>
                                    <span class="comment-date">{{ $comment->created_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                                <p>{{ $comment->body }}</p>
                            </div>
                            @if($comment->replies && $comment->replies->count())
                                @foreach($comment->replies as $reply)
                                    <div class="comment-item comment-reply-item">
                                        <div class="comment-avatar sm"><img src="{{ asset('themes/porto/images/blog/author.jpg') }}" alt="{{ $reply->name }}"></div>
                                        <div class="comment-content-box">
                                            <div class="comment-header-row">
                                                <strong>{{ $reply->name }}</strong>
                                                <span class="comment-date">{{ $reply->created_at->format('M d, Y \a\t h:i A') }}</span>
                                            </div>
                                            <p>{{ $reply->body }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                    @livewire('comment-form', ['postId' => $post->id])
                </div>

                {{-- Related Posts --}}
                @if($relatedPosts->count())
                <div class="related-posts-section">
                    <h3 class="related-posts-heading"><i class="fas fa-th-large"></i> Related Posts</h3>
                    <div class="row">
                        @foreach($relatedPosts as $rp)
                            <div class="col-md-4 mb-4">
                                <div class="related-post-card">
                                    @if($rp->image)
                                    <div class="related-post-img"><a href="{{ url('/blog/' . $rp->slug) }}"><img src="{{ asset('storage/' . $rp->image) }}" alt="{{ $rp->title }}"></a></div>
                                    @endif
                                    <div class="related-post-info">
                                        <h5><a href="{{ url('/blog/' . $rp->slug) }}">{{ $rp->title }}</a></h5>
                                        <span><i class="far fa-calendar-alt"></i> {{ $rp->published_at?->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <aside class="col-lg-4">
                <div class="blog-sidebar">
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title"><i class="fas fa-fire"></i> Recent Posts</h4>
                        <div class="sidebar-recent-posts">
                            @foreach($recentPosts as $recent)
                                <a href="{{ url('/blog/' . $recent->slug) }}" class="sidebar-recent-post">
                                    <div class="sidebar-recent-post-info">
                                        <h5>{{ $recent->title }}</h5>
                                        <span><i class="far fa-calendar-alt"></i> {{ $recent->published_at?->format('M d, Y') }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title"><i class="fas fa-folder-open"></i> Categories</h4>
                        <ul class="sidebar-categories">
                            @foreach($categories as $cat)
                                <li><a href="{{ url('/blog/category/' . $cat->slug) }}"><span class="cat-name">{{ $cat->name }}</span><span class="cat-count">{{ $cat->posts_count }}</span></a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('styles')
<style>
.single-post-container{padding:40px 0 60px}
.single-post-card{background:#fff;border-radius:16px;border:1px solid #eef1f5;box-shadow:0 4px 25px rgba(0,0,0,.04);overflow:hidden;margin-bottom:35px}
.single-post-hero-img{position:relative;max-height:460px;overflow:hidden}
.single-post-hero-img img{width:100%;height:100%;object-fit:cover}
.single-post-hero-overlay{position:absolute;inset:0;background:linear-gradient(180deg,transparent 60%,rgba(0,0,0,.12) 100%);pointer-events:none}
.single-post-body{padding:35px}
.single-post-meta-bar{display:flex;flex-wrap:wrap;gap:16px;margin-bottom:18px}
.single-post-meta-item{font-size:13px;color:#999;display:flex;align-items:center;gap:6px}
.single-post-meta-item i{color:var(--porto-primary);font-size:13px}
.single-post-meta-item a{color:#666;text-decoration:none;font-weight:500}
.single-post-meta-item a:hover{color:var(--porto-primary)}
.single-post-title{font-size:30px;font-weight:800;color:var(--porto-heading);line-height:1.3;margin-bottom:24px;font-family:'Poppins',sans-serif}
.single-post-content{font-size:15px;color:#555;line-height:1.85}
.single-post-content img{border-radius:12px;margin:16px 0;max-width:100%;height:auto}
.single-post-content h2,.single-post-content h3,.single-post-content h4{color:var(--porto-heading);margin-top:28px;margin-bottom:12px;font-family:'Poppins',sans-serif}
.single-post-content p{margin-bottom:16px}
.single-post-content blockquote{border-left:4px solid var(--porto-primary);padding:16px 20px;background:#f8f9fb;border-radius:0 10px 10px 0;margin:20px 0;font-style:italic;color:#666}
.single-post-tags-bar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;padding-top:24px;margin-top:28px;border-top:1px solid #f0f2f5}
.tags-label{font-weight:600;color:var(--porto-heading);font-size:14px;display:flex;align-items:center;gap:6px}
.tags-label i{color:var(--porto-primary)}
.single-post-tag{padding:5px 14px;border-radius:50px;background:#f4f5f8;color:#555;font-size:13px;font-weight:500;text-decoration:none;border:1px solid #eee;transition:all .25s ease}
.single-post-tag:hover{background:var(--porto-primary);color:#fff;border-color:var(--porto-primary)}
.single-post-share-bar{display:flex;align-items:center;gap:14px;margin-top:24px;padding-top:20px;border-top:1px solid #f0f2f5}
.share-label{font-weight:600;color:var(--porto-heading);font-size:14px}
.share-btns{display:flex;gap:8px}
.share-btn{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;font-size:15px;transition:all .3s ease}
.share-btn:hover{transform:translateY(-2px);color:#fff}
.share-facebook{background:#3b5998}
.share-twitter{background:#1da1f2}
.share-email{background:#ea4335}

/* Comments */
.single-post-comments{background:#fff;border-radius:16px;border:1px solid #eef1f5;box-shadow:0 4px 20px rgba(0,0,0,.04);padding:30px;margin-bottom:35px}
.comments-heading{font-size:20px;font-weight:700;color:var(--porto-heading);margin-bottom:24px;display:flex;align-items:center;gap:10px;font-family:'Poppins',sans-serif}
.comments-heading i{color:var(--porto-primary)}
.comment-item{display:flex;gap:14px;padding:18px 0;border-bottom:1px solid #f0f2f5;flex-wrap:wrap}
.comment-item:last-child{border-bottom:none}
.comment-avatar{flex-shrink:0}
.comment-avatar img{width:50px;height:50px;border-radius:50%;object-fit:cover;border:2px solid #eef1f5}
.comment-avatar.sm img{width:40px;height:40px}
.comment-content-box{flex:1;min-width:0}
.comment-header-row{display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap}
.comment-header-row strong{font-size:14px;color:var(--porto-heading)}
.comment-date{font-size:12px;color:#aaa}
.comment-content-box p{font-size:14px;color:#666;line-height:1.6;margin:0}
.comment-reply-item{margin-left:50px;padding-top:14px;border-bottom:none;border-top:1px solid #f5f5f5}

/* Related Posts */
.related-posts-section{margin-bottom:35px}
.related-posts-heading{font-size:20px;font-weight:700;color:var(--porto-heading);margin-bottom:20px;display:flex;align-items:center;gap:10px;font-family:'Poppins',sans-serif}
.related-posts-heading i{color:var(--porto-primary)}
.related-post-card{background:#fff;border-radius:14px;border:1px solid #eef1f5;box-shadow:0 3px 15px rgba(0,0,0,.04);overflow:hidden;transition:all .3s ease;height:100%}
.related-post-card:hover{transform:translateY(-4px);box-shadow:0 10px 30px rgba(0,0,0,.1)}
.related-post-img{height:160px;overflow:hidden}
.related-post-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s ease}
.related-post-card:hover .related-post-img img{transform:scale(1.06)}
.related-post-info{padding:16px}
.related-post-info h5{font-size:14px;font-weight:600;line-height:1.4;margin-bottom:6px}
.related-post-info h5 a{color:var(--porto-heading);text-decoration:none;transition:color .3s ease}
.related-post-info h5 a:hover{color:var(--porto-primary)}
.related-post-info span{font-size:12px;color:#999;display:flex;align-items:center;gap:4px}

/* Shared sidebar styles (same as blog index) */
.blog-sidebar{display:flex;flex-direction:column;gap:28px;position:sticky;top:20px}
.sidebar-widget{background:#fff;border-radius:16px;padding:24px;border:1px solid #eef1f5;box-shadow:0 4px 20px rgba(0,0,0,.04)}
.sidebar-widget-title{font-size:17px;font-weight:700;color:var(--porto-heading);margin-bottom:18px;display:flex;align-items:center;gap:8px;padding-bottom:14px;border-bottom:2px solid #f0f2f5;font-family:'Poppins',sans-serif}
.sidebar-widget-title i{color:var(--porto-primary);font-size:15px}
.sidebar-recent-posts{display:flex;flex-direction:column;gap:14px}
.sidebar-recent-post{display:flex;gap:14px;align-items:center;text-decoration:none;padding:8px;border-radius:10px;transition:all .25s ease}
.sidebar-recent-post:hover{background:#f8f9fb}
.sidebar-recent-post-info h5{font-size:14px;font-weight:600;color:var(--porto-heading);margin-bottom:4px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:color .3s ease}
.sidebar-recent-post:hover .sidebar-recent-post-info h5{color:var(--porto-primary)}
.sidebar-recent-post-info span{font-size:12px;color:#999;display:flex;align-items:center;gap:4px}
.sidebar-categories{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:4px}
.sidebar-categories li a{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-radius:10px;text-decoration:none;color:#555;font-size:14px;font-weight:500;transition:all .25s ease}
.sidebar-categories li a:hover{background:rgba(var(--porto-primary-rgb,0,136,204),.06);color:var(--porto-primary)}
.sidebar-categories .cat-count{background:#f0f2f5;padding:2px 10px;border-radius:50px;font-size:12px;font-weight:700;color:#888;transition:all .25s ease}
.sidebar-categories li a:hover .cat-count{background:var(--porto-primary);color:#fff}

@media(max-width:991px){.blog-sidebar{position:static;margin-top:40px}.comment-reply-item{margin-left:30px}}
@media(max-width:768px){.single-post-title{font-size:24px}.single-post-body{padding:24px}.single-post-share-bar{flex-direction:column;align-items:flex-start;gap:10px}}
</style>
@endpush
