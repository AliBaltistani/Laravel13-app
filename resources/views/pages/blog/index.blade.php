@extends('layouts.app')

@section('meta_title', (isset($category) ? $category->name . ' - ' : (isset($tag) ? 'Tag: ' . $tag->name . ' - ' : '')) . 'Blog - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', [
        'title' => isset($category) ? $category->name : (isset($tag) ? 'Tag: ' . $tag->name : 'Blog'),
        'items' => [['label' => 'Blog', 'url' => url('/blog')]]
    ])

    <section class="blog-hero-section">
        <div class="container">
            <div class="blog-hero-inner">
                <span class="blog-hero-badge"><i class="fas fa-blog"></i> Our Blog</span>
                <h2 class="blog-hero-title">
                    @if(isset($category)) {{ $category->name }}
                    @elseif(isset($tag)) Posts tagged: {{ $tag->name }}
                    @else Latest Articles & Insights @endif
                </h2>
                <p class="blog-hero-desc">Discover tips, guides, and stories curated just for you.</p>
            </div>
        </div>
    </section>

    <div class="container blog-main-container">
        <div class="row">
            <div class="col-lg-8">
                <div class="blog-posts-grid">
                    @forelse($posts as $post)
                        <article class="blog-card" id="post-{{ $post->id }}">
                            @if($post->image)
                                <div class="blog-card-image">
                                    <a href="{{ url('/blog/' . $post->slug) }}">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" loading="lazy">
                                        <div class="blog-card-image-overlay"></div>
                                    </a>
                                    @if($post->postCategory)
                                        <a href="{{ url('/blog/category/' . $post->postCategory->slug) }}" class="blog-card-category">{{ $post->postCategory->name }}</a>
                                    @endif
                                </div>
                            @endif
                            <div class="blog-card-body">
                                <div class="blog-card-meta">
                                    <span class="blog-card-meta-item"><i class="far fa-calendar-alt"></i> {{ $post->published_at?->format('M d, Y') }}</span>
                                    <span class="blog-card-meta-divider"></span>
                                    <span class="blog-card-meta-item"><i class="far fa-comment-alt"></i> {{ $post->approved_comments_count ?? 0 }} Comments</span>
                                </div>
                                <h2 class="blog-card-title"><a href="{{ url('/blog/' . $post->slug) }}">{{ $post->title }}</a></h2>
                                <p class="blog-card-excerpt">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 180) }}</p>
                                <a href="{{ url('/blog/' . $post->slug) }}" class="blog-card-readmore"><span>Read Article</span> <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </article>
                    @empty
                        <div class="blog-empty-state">
                            <div class="blog-empty-icon"><i class="far fa-newspaper"></i></div>
                            <h4>No Posts Found</h4>
                            <p>We couldn't find any posts matching your criteria.</p>
                            <a href="{{ url('/blog') }}" class="blog-empty-btn"><i class="fas fa-arrow-left"></i> View All Posts</a>
                        </div>
                    @endforelse
                </div>
                @if($posts->hasPages())
                    <nav class="blog-pagination">{{ $posts->links() }}</nav>
                @endif
            </div>

            <aside class="col-lg-4">
                <div class="blog-sidebar">
                    <div class="sidebar-widget" id="recent-posts-widget">
                        <h4 class="sidebar-widget-title"><i class="fas fa-fire"></i> Recent Posts</h4>
                        <div class="sidebar-recent-posts">
                            @foreach($recentPosts as $recent)
                                <a href="{{ url('/blog/' . $recent->slug) }}" class="sidebar-recent-post">
                                    @if($recent->image)
                                        <div class="sidebar-recent-post-img"><img src="{{ asset('storage/' . $recent->image) }}" alt="{{ $recent->title }}" loading="lazy"></div>
                                    @endif
                                    <div class="sidebar-recent-post-info">
                                        <h5>{{ $recent->title }}</h5>
                                        <span><i class="far fa-calendar-alt"></i> {{ $recent->published_at?->format('M d, Y') }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="sidebar-widget" id="categories-widget">
                        <h4 class="sidebar-widget-title"><i class="fas fa-folder-open"></i> Categories</h4>
                        <ul class="sidebar-categories">
                            @foreach($categories as $cat)
                                <li class="{{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">
                                    <a href="{{ url('/blog/category/' . $cat->slug) }}"><span class="cat-name">{{ $cat->name }}</span><span class="cat-count">{{ $cat->posts_count }}</span></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if(isset($tags) && $tags->count())
                    <div class="sidebar-widget" id="tags-widget">
                        <h4 class="sidebar-widget-title"><i class="fas fa-tags"></i> Popular Tags</h4>
                        <div class="sidebar-tags">
                            @foreach($tags as $t)
                                <a href="{{ url('/blog/tag/' . $t->slug) }}" class="sidebar-tag {{ isset($tag) && $tag->id == $t->id ? 'active' : '' }}">{{ $t->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('styles')
<style>
.blog-hero-section{background:linear-gradient(135deg,rgba(var(--porto-primary-rgb,0,136,204),.07) 0%,rgba(var(--porto-primary-rgb,0,136,204),.02) 100%);padding:45px 0 35px;text-align:center}
.blog-hero-inner{max-width:600px;margin:0 auto}
.blog-hero-badge{display:inline-flex;align-items:center;gap:8px;background:var(--porto-primary);color:#fff;padding:6px 18px;border-radius:50px;font-size:13px;font-weight:600;margin-bottom:16px}
.blog-hero-title{font-size:34px;font-weight:800;color:var(--porto-heading);margin-bottom:10px;line-height:1.2;font-family:'Poppins',sans-serif}
.blog-hero-desc{font-size:15px;color:#777;line-height:1.6}
.blog-main-container{padding:40px 0 60px}
.blog-posts-grid{display:flex;flex-direction:column;gap:30px}
.blog-card{background:#fff;border-radius:16px;border:1px solid #eef1f5;box-shadow:0 4px 20px rgba(0,0,0,.04);overflow:hidden;transition:all .35s cubic-bezier(.4,0,.2,1)}
.blog-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(0,0,0,.1)}
.blog-card-image{position:relative;overflow:hidden;max-height:340px}
.blog-card-image img{width:100%;height:100%;object-fit:cover;transition:transform .5s ease}
.blog-card:hover .blog-card-image img{transform:scale(1.05)}
.blog-card-image-overlay{position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(0,0,0,.15) 100%);pointer-events:none}
.blog-card-category{position:absolute;top:16px;left:16px;background:var(--porto-primary);color:#fff;padding:5px 14px;border-radius:50px;font-size:12px;font-weight:600;text-decoration:none;transition:all .3s ease;z-index:2}
.blog-card-category:hover{background:#fff;color:var(--porto-primary)}
.blog-card-body{padding:28px}
.blog-card-meta{display:flex;align-items:center;gap:10px;margin-bottom:14px;flex-wrap:wrap}
.blog-card-meta-item{font-size:13px;color:#999;display:flex;align-items:center;gap:5px}
.blog-card-meta-item i{color:var(--porto-primary);font-size:12px}
.blog-card-meta-divider{width:4px;height:4px;border-radius:50%;background:#ccc}
.blog-card-title{font-size:22px;font-weight:700;line-height:1.35;margin-bottom:12px;font-family:'Poppins',sans-serif}
.blog-card-title a{color:var(--porto-heading);text-decoration:none;transition:color .3s ease}
.blog-card-title a:hover{color:var(--porto-primary)}
.blog-card-excerpt{font-size:14px;color:#777;line-height:1.7;margin-bottom:18px}
.blog-card-readmore{display:inline-flex;align-items:center;gap:8px;font-size:14px;font-weight:600;color:var(--porto-primary);text-decoration:none;transition:all .3s ease;padding:8px 0;border-bottom:2px solid transparent}
.blog-card-readmore:hover{color:var(--porto-primary);border-bottom-color:var(--porto-primary)}
.blog-card-readmore i{font-size:12px;transition:transform .3s ease}
.blog-card-readmore:hover i{transform:translateX(5px)}
.blog-empty-state{text-align:center;padding:60px 30px;background:#fff;border-radius:16px;border:2px dashed #e0e4ea}
.blog-empty-icon{font-size:48px;color:#ddd;margin-bottom:16px}
.blog-empty-state h4{color:var(--porto-heading);font-weight:700;margin-bottom:8px}
.blog-empty-state p{color:#999;margin-bottom:20px}
.blog-empty-btn{display:inline-flex;align-items:center;gap:8px;background:var(--porto-primary);color:#fff;padding:10px 24px;border-radius:10px;font-weight:600;text-decoration:none;transition:all .3s ease}
.blog-empty-btn:hover{filter:brightness(1.08);color:#fff}
.blog-pagination{margin-top:40px;display:flex;justify-content:center}
.blog-pagination .pagination{gap:6px}
.blog-pagination .page-link{border-radius:10px!important;border:1px solid #eee;color:#555;font-weight:600;padding:8px 16px;transition:all .25s ease}
.blog-pagination .page-item.active .page-link,.blog-pagination .page-link:hover{background:var(--porto-primary);border-color:var(--porto-primary);color:#fff}
.blog-sidebar{display:flex;flex-direction:column;gap:28px;position:sticky;top:20px}
.sidebar-widget{background:#fff;border-radius:16px;padding:24px;border:1px solid #eef1f5;box-shadow:0 4px 20px rgba(0,0,0,.04)}
.sidebar-widget-title{font-size:17px;font-weight:700;color:var(--porto-heading);margin-bottom:18px;display:flex;align-items:center;gap:8px;padding-bottom:14px;border-bottom:2px solid #f0f2f5;font-family:'Poppins',sans-serif}
.sidebar-widget-title i{color:var(--porto-primary);font-size:15px}
.sidebar-recent-posts{display:flex;flex-direction:column;gap:14px}
.sidebar-recent-post{display:flex;gap:14px;align-items:center;text-decoration:none;padding:8px;border-radius:10px;transition:all .25s ease}
.sidebar-recent-post:hover{background:#f8f9fb}
.sidebar-recent-post-img{width:70px;height:70px;border-radius:10px;overflow:hidden;flex-shrink:0}
.sidebar-recent-post-img img{width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
.sidebar-recent-post:hover .sidebar-recent-post-img img{transform:scale(1.1)}
.sidebar-recent-post-info h5{font-size:14px;font-weight:600;color:var(--porto-heading);margin-bottom:4px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:color .3s ease}
.sidebar-recent-post:hover .sidebar-recent-post-info h5{color:var(--porto-primary)}
.sidebar-recent-post-info span{font-size:12px;color:#999;display:flex;align-items:center;gap:4px}
.sidebar-recent-post-info span i{font-size:11px}
.sidebar-categories{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:4px}
.sidebar-categories li a{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-radius:10px;text-decoration:none;color:#555;font-size:14px;font-weight:500;transition:all .25s ease}
.sidebar-categories li a:hover,.sidebar-categories li.active a{background:rgba(var(--porto-primary-rgb,0,136,204),.06);color:var(--porto-primary)}
.sidebar-categories .cat-count{background:#f0f2f5;padding:2px 10px;border-radius:50px;font-size:12px;font-weight:700;color:#888;transition:all .25s ease}
.sidebar-categories li a:hover .cat-count,.sidebar-categories li.active a .cat-count{background:var(--porto-primary);color:#fff}
.sidebar-tags{display:flex;flex-wrap:wrap;gap:8px}
.sidebar-tag{padding:6px 14px;border-radius:50px;background:#f4f5f8;color:#555;font-size:13px;font-weight:500;text-decoration:none;border:1px solid #eee;transition:all .25s ease}
.sidebar-tag:hover,.sidebar-tag.active{background:var(--porto-primary);color:#fff;border-color:var(--porto-primary)}
@media(max-width:991px){.blog-sidebar{position:static;margin-top:40px}}
@media(max-width:768px){.blog-hero-title{font-size:26px}.blog-card-title{font-size:19px}.blog-card-body{padding:20px}}
</style>
@endpush
