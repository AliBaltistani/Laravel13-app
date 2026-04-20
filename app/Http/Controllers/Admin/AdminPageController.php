<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageImage;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::withCount(['images', 'sections']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $pages = $query->orderBy('sort_order')->paginate(20);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.form', ['page' => new Page(), 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePage($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['show_sidebar'] = $request->boolean('show_sidebar');
        $data['show_in_header'] = $request->boolean('show_in_header');
        $data['show_in_footer'] = $request->boolean('show_in_footer');
        $data['header_label'] = $request->input('header_label') ?: null;
        $data['footer_label'] = $request->input('footer_label') ?: null;
        $data['header_order'] = $request->integer('header_order') ?? 0;
        $data['footer_order'] = $request->integer('footer_order') ?? 0;

        // Handle file uploads
        $data = $this->handleFileUploads($request, $data);

        $page = Page::create($data);

        // Handle gallery images
        $this->handleGalleryImages($request, $page);

        // Handle sections
        $this->handleSections($request, $page);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $page->load(['images', 'sections']);
        return view('admin.pages.form', ['page' => $page, 'isEdit' => true]);
    }

    public function update(Request $request, Page $page)
    {
        $data = $this->validatePage($request, $page->id);
        $data['is_active'] = $request->boolean('is_active');
        $data['show_sidebar'] = $request->boolean('show_sidebar');
        $data['show_in_header'] = $request->boolean('show_in_header');
        $data['show_in_footer'] = $request->boolean('show_in_footer');
        $data['header_label'] = $request->input('header_label') ?: null;
        $data['footer_label'] = $request->input('footer_label') ?: null;
        $data['header_order'] = $request->integer('header_order') ?? 0;
        $data['footer_order'] = $request->integer('footer_order') ?? 0;

        // Handle file uploads
        $data = $this->handleFileUploads($request, $data, $page);

        $page->update($data);

        // Handle gallery images
        $this->handleGalleryImages($request, $page);

        // Handle sections
        $this->handleSections($request, $page);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        // Delete all associated files
        if ($page->image) Storage::disk('public')->delete($page->image);
        if ($page->banner_image) Storage::disk('public')->delete($page->banner_image);
        if ($page->video_file) Storage::disk('public')->delete($page->video_file);

        foreach ($page->images as $img) {
            Storage::disk('public')->delete($img->image);
        }
        foreach ($page->sections as $sec) {
            if ($sec->image) Storage::disk('public')->delete($sec->image);
        }

        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted.');
    }

    /**
     * Delete a gallery image via AJAX.
     */
    public function deleteImage(Page $page, PageImage $image)
    {
        if ($image->page_id !== $page->id) abort(404);
        Storage::disk('public')->delete($image->image);
        $image->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Delete a section via AJAX.
     */
    public function deleteSection(Page $page, PageSection $section)
    {
        if ($section->page_id !== $page->id) abort(404);
        if ($section->image) Storage::disk('public')->delete($section->image);
        $section->delete();
        return response()->json(['success' => true]);
    }

    // ─── Private Helpers ─────────────────────────────────

    private function validatePage(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug' . ($ignoreId ? ',' . $ignoreId : ''),
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'template' => 'required|in:default,full-width,with-sidebar',
            'layout' => 'nullable|in:default,full-width,with-sidebar',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'show_sidebar' => 'boolean',
            'sidebar_content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:500',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:4096',
            'video_file' => 'nullable|mimes:mp4,webm,ogg|max:51200',
            'show_in_header' => 'boolean',
            'show_in_footer' => 'boolean',
            'header_label' => 'nullable|string|max:255',
            'footer_label' => 'nullable|string|max:255',
            'header_order' => 'nullable|integer|min:0|max:10',
            'footer_order' => 'nullable|integer|min:0|max:10',
        ]);
    }

    private function handleFileUploads(Request $request, array $data, ?Page $page = null): array
    {
        // Featured image
        if ($request->hasFile('image')) {
            if ($page && $page->image) Storage::disk('public')->delete($page->image);
            $data['image'] = $request->file('image')->store('pages', 'public');
        }

        // Banner image
        if ($request->hasFile('banner_image')) {
            if ($page && $page->banner_image) Storage::disk('public')->delete($page->banner_image);
            $data['banner_image'] = $request->file('banner_image')->store('pages/banners', 'public');
        }

        // Video file
        if ($request->hasFile('video_file')) {
            if ($page && $page->video_file) Storage::disk('public')->delete($page->video_file);
            $data['video_file'] = $request->file('video_file')->store('pages/videos', 'public');
        }

        // Remove files if requested
        if ($request->boolean('remove_image') && $page) {
            Storage::disk('public')->delete($page->image);
            $data['image'] = null;
        }
        if ($request->boolean('remove_banner') && $page) {
            Storage::disk('public')->delete($page->banner_image);
            $data['banner_image'] = null;
        }
        if ($request->boolean('remove_video_file') && $page) {
            Storage::disk('public')->delete($page->video_file);
            $data['video_file'] = null;
        }

        return $data;
    }

    private function handleGalleryImages(Request $request, Page $page): void
    {
        if ($request->hasFile('gallery_images')) {
            $maxSort = $page->images()->max('sort_order') ?? 0;
            foreach ($request->file('gallery_images') as $i => $file) {
                $page->images()->create([
                    'image' => $file->store('pages/gallery', 'public'),
                    'alt_text' => $request->input("gallery_alt.{$i}", ''),
                    'sort_order' => $maxSort + $i + 1,
                ]);
            }
        }
    }

    private function handleSections(Request $request, Page $page): void
    {
        if (!$request->has('sections')) return;

        $sectionData = $request->input('sections', []);
        $existingIds = [];

        foreach ($sectionData as $idx => $sec) {
            $sData = [
                'type' => $sec['type'] ?? 'text',
                'title' => $sec['title'] ?? null,
                'content' => $sec['content'] ?? null,
                'video_url' => $sec['video_url'] ?? null,
                'css_class' => $sec['css_class'] ?? null,
                'bg_color' => $sec['bg_color'] ?? null,
                'sort_order' => $sec['sort_order'] ?? $idx,
                'is_active' => isset($sec['is_active']) ? (bool)$sec['is_active'] : true,
            ];

            // Handle section image
            if ($request->hasFile("sections.{$idx}.image")) {
                $sData['image'] = $request->file("sections.{$idx}.image")->store('pages/sections', 'public');
            }

            if (!empty($sec['id'])) {
                $section = $page->sections()->find($sec['id']);
                if ($section) {
                    if (isset($sData['image']) && $section->image) {
                        Storage::disk('public')->delete($section->image);
                    }
                    $section->update($sData);
                    $existingIds[] = $section->id;
                }
            } else {
                $newSection = $page->sections()->create($sData);
                $existingIds[] = $newSection->id;
            }
        }

        // Remove sections not in the submission
        $page->sections()->whereNotIn('id', $existingIds)->each(function ($s) {
            if ($s->image) Storage::disk('public')->delete($s->image);
            $s->delete();
        });
    }
}
