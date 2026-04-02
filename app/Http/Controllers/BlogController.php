<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use App\Services\SeoService;

class BlogController extends Controller
{
    public function index()
    {
        // SEO — Phase 9-A
        app(SeoService::class)
            ->setTitle('Blog')
            ->setDescription('Latest news, tips, and updates from our store');
        $posts = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['postCategory', 'user', 'tags'])
            ->latest('published_at')
            ->paginate(6);

        $recentPosts = Post::where('is_published', true)
            ->latest('published_at')->take(5)->get();

        $categories = PostCategory::where('is_active', true)
            ->withCount(['posts' => fn($q) => $q->where('is_published', true)])
            ->orderBy('sort_order')
            ->get();

        $tags = Tag::withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->take(20)
            ->get();

        return view('pages.blog.index', compact('posts', 'recentPosts', 'categories', 'tags'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->with(['postCategory', 'user', 'tags', 'approvedComments.replies', 'approvedComments.user'])
            ->firstOrFail();

        $post->increment('views_count');

        // SEO — Phase 9-A/9-B: Article JSON-LD
        $seo = app(SeoService::class);
        $seo->setTitle($post->meta_title ?: $post->title)
            ->setDescription($post->meta_description ?: ($post->excerpt ?? strip_tags(substr($post->content ?? '', 0, 160))))
            ->setCanonical(route('blog.show', $post->slug))
            ->setJsonLd($post->jsonLd());

        if ($post->image) {
            $seo->setImage(asset('storage/' . $post->image));
        }

        $relatedPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where('post_category_id', $post->post_category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $recentPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        $categories = PostCategory::where('is_active', true)
            ->withCount(['posts' => fn($q) => $q->where('is_published', true)])
            ->orderBy('sort_order')
            ->get();

        return view('pages.blog.single', compact('post', 'relatedPosts', 'recentPosts', 'categories'));
    }

    public function category(string $slug)
    {
        $category = PostCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $posts = Post::where('is_published', true)
            ->where('post_category_id', $category->id)
            ->with(['postCategory', 'user', 'tags'])
            ->latest('published_at')
            ->paginate(6);

        $recentPosts = Post::where('is_published', true)->latest('published_at')->take(5)->get();

        $categories = PostCategory::where('is_active', true)
            ->withCount(['posts' => fn($q) => $q->where('is_published', true)])
            ->orderBy('sort_order')->get();

        $tags = Tag::withCount('posts')->having('posts_count', '>', 0)->orderByDesc('posts_count')->take(20)->get();

        return view('pages.blog.index', compact('posts', 'recentPosts', 'categories', 'tags', 'category'));
    }

    public function tag(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->where('is_published', true)
            ->with(['postCategory', 'user', 'tags'])
            ->latest('published_at')
            ->paginate(6);

        $recentPosts = Post::where('is_published', true)->latest('published_at')->take(5)->get();

        $categories = PostCategory::where('is_active', true)
            ->withCount(['posts' => fn($q) => $q->where('is_published', true)])
            ->orderBy('sort_order')->get();

        $tags = Tag::withCount('posts')->having('posts_count', '>', 0)->orderByDesc('posts_count')->take(20)->get();

        return view('pages.blog.index', compact('posts', 'recentPosts', 'categories', 'tags', 'tag'));
    }
}
