@extends('layouts.app')

@section('meta_title', (isset($category) ? $category->name . ' - ' : (isset($tag) ? 'Tag: ' . $tag->name . ' - ' : '')) . 'Blog - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', [
        'title' => isset($category) ? $category->name : (isset($tag) ? 'Tag: ' . $tag->name : 'Blog'),
        'items' => [['label' => 'Blog', 'url' => url('/blog')]]
    ])

    <div class="container">
        <div class="row">
            {{-- Blog Posts --}}
            <div class="col-lg-9">
                @forelse($posts as $post)
                    <article class="entry">
                        @if($post->featured_image)
                            <figure class="entry-media">
                                <a href="{{ url('/blog/' . $post->slug) }}">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="width:100%; max-height:400px; object-fit:cover;">
                                </a>
                            </figure>
                        @endif

                        <div class="entry-body">
                            <div class="entry-meta">
                                @if($post->postCategory)
                                    <span class="entry-author">
                                        in <a href="{{ url('/blog/category/' . $post->postCategory->slug) }}">{{ $post->postCategory->name }}</a>
                                    </span>
                                @endif
                                <span class="meta-separator">|</span>
                                <a href="{{ url('/blog/' . $post->slug) }}">{{ $post->published_at?->format('M d, Y') }}</a>
                                <span class="meta-separator">|</span>
                                <a href="{{ url('/blog/' . $post->slug) }}#comments">{{ $post->approved_comments_count ?? 0 }} Comments</a>
                            </div>

                            <h2 class="entry-title">
                                <a href="{{ url('/blog/' . $post->slug) }}">{{ $post->title }}</a>
                            </h2>

                            <div class="entry-content">
                                <p>{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 200) }}</p>
                                <a href="{{ url('/blog/' . $post->slug) }}" class="read-more">Continue Reading</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-5">
                        <h4 class="text-muted">No posts found</h4>
                        <a href="{{ url('/blog') }}" class="btn btn-outline-dark mt-2">View All Posts</a>
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if($posts->hasPages())
                    <nav class="toolbox toolbox-pagination">
                        <ul class="pagination">
                            {{ $posts->links() }}
                        </ul>
                    </nav>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="sidebar col-lg-3">
                {{-- Recent Posts --}}
                <div class="widget widget-post">
                    <h4 class="widget-title">Recent Posts</h4>
                    <ul class="simple-post-list">
                        @foreach($recentPosts as $recent)
                            <li>
                                @if($recent->featured_image)
                                    <div class="post-media">
                                        <a href="{{ url('/blog/' . $recent->slug) }}">
                                            <img src="{{ asset('storage/' . $recent->featured_image) }}" alt="{{ $recent->title }}" width="80">
                                        </a>
                                    </div>
                                @endif
                                <div class="post-info">
                                    <a href="{{ url('/blog/' . $recent->slug) }}">{{ $recent->title }}</a>
                                    <div class="post-meta">{{ $recent->published_at?->format('M d, Y') }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Categories --}}
                <div class="widget widget-categories">
                    <h4 class="widget-title">Blog Categories</h4>
                    <ul class="cat-list">
                        @foreach($categories as $cat)
                            <li {{ isset($category) && $category->id == $cat->id ? 'class=active' : '' }}>
                                <a href="{{ url('/blog/category/' . $cat->slug) }}">{{ $cat->name }} <span class="products-count">({{ $cat->posts_count }})</span></a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Tags --}}
                @if(isset($tags) && $tags->count())
                <div class="widget">
                    <h4 class="widget-title">Tags</h4>
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
