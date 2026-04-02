<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['category', 'author']);
        if ($request->filled('category_id')) $query->where('post_category_id', $request->category_id);
        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $posts = $query->latest()->paginate(20)->withQueryString();
        $categories = PostCategory::ordered()->get();
        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = PostCategory::ordered()->get();
        return view('admin.posts.form', ['post' => new Post(), 'categories' => $categories, 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'post_category_id' => 'nullable|exists:post_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:2048',
        ]);
        $data['is_published'] = $request->boolean('is_published');
        $data['user_id'] = auth()->id();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        Post::create($data);
        return redirect()->route('admin.posts.index')->with('success', 'Post created.');
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::ordered()->get();
        return view('admin.posts.form', ['post' => $post, 'categories' => $categories, 'isEdit' => true]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'post_category_id' => 'nullable|exists:post_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:2048',
        ]);
        $data['is_published'] = $request->boolean('is_published');
        if ($request->hasFile('image')) {
            if ($post->image) Storage::disk('public')->delete($post->image);
            $data['image'] = $request->file('image')->store('posts', 'public');
        }
        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }
}
