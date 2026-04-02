<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Homepage --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>

    {{-- Shop --}}
    <url>
        <loc>{{ route('shop.index') }}</loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>

    {{-- Blog --}}
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <priority>0.7</priority>
        <changefreq>weekly</changefreq>
    </url>

    {{-- About --}}
    <url>
        <loc>{{ route('about') }}</loc>
        <priority>0.5</priority>
        <changefreq>monthly</changefreq>
    </url>

    {{-- Contact --}}
    <url>
        <loc>{{ route('contact') }}</loc>
        <priority>0.5</priority>
        <changefreq>monthly</changefreq>
    </url>

    {{-- Products --}}
    @foreach($products as $product)
    <url>
        <loc>{{ route('product.show', $product->slug) }}</loc>
        <lastmod>{{ $product->updated_at->toW3cString() }}</lastmod>
        <priority>0.8</priority>
        <changefreq>weekly</changefreq>
    </url>
    @endforeach

    {{-- Categories --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ route('shop.category', $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toW3cString() }}</lastmod>
        <priority>0.7</priority>
        <changefreq>weekly</changefreq>
    </url>
    @endforeach

    {{-- Blog Posts --}}
    @foreach($posts as $post)
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toW3cString() }}</lastmod>
        <priority>0.6</priority>
        <changefreq>weekly</changefreq>
    </url>
    @endforeach

    {{-- CMS Pages --}}
    @foreach($pages as $page)
    <url>
        <loc>{{ route('page.show', $page->slug) }}</loc>
        <lastmod>{{ $page->updated_at->toW3cString() }}</lastmod>
        <priority>0.5</priority>
        <changefreq>monthly</changefreq>
    </url>
    @endforeach
</urlset>
