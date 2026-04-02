<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    public function index()
    {
        $categories = PostCategory::withCount('posts')->orderBy('sort_order')->get();
        return view('admin.post-categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = PostCategory::whereNull('parent_id')->ordered()->get();
        return view('admin.post-categories.form', ['category' => new PostCategory(), 'parents' => $parents, 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:post_categories,slug',
            'parent_id' => 'nullable|exists:post_categories,id',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        PostCategory::create($data);
        return redirect()->route('admin.post-categories.index')->with('success', 'Blog category created.');
    }

    public function edit(PostCategory $postCategory)
    {
        $parents = PostCategory::whereNull('parent_id')->where('id', '!=', $postCategory->id)->ordered()->get();
        return view('admin.post-categories.form', ['category' => $postCategory, 'parents' => $parents, 'isEdit' => true]);
    }

    public function update(Request $request, PostCategory $postCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:post_categories,slug,' . $postCategory->id,
            'parent_id' => 'nullable|exists:post_categories,id',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $postCategory->update($data);
        return redirect()->route('admin.post-categories.index')->with('success', 'Blog category updated.');
    }

    public function destroy(PostCategory $postCategory)
    {
        $postCategory->delete();
        return redirect()->route('admin.post-categories.index')->with('success', 'Blog category deleted.');
    }
}
