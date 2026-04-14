<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Models\Product;
use Illuminate\Http\Request;

class HomepageSectionController extends Controller
{
    /**
     * Show all homepage sections with reorder UI.
     */
    public function index()
    {
        $sections = HomepageSection::ordered()->get();
        return view('admin.homepage.index', compact('sections'));
    }

    /**
     * Edit a specific section's settings.
     */
    public function edit(HomepageSection $section)
    {
        $section->load('products');

        // Pass all active products for the product picker (only for product/widget sections)
        $allProducts = collect();
        if (in_array($section->type, ['products', 'widgets'])) {
            $allProducts = Product::active()
                ->with('images')
                ->orderBy('name')
                ->get();
        }

        return view('admin.homepage.edit', compact('section', 'allProducts'));
    }

    /**
     * Update a section's settings.
     */
    public function update(Request $request, HomepageSection $section)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        // Build settings JSON from the form fields
        $settings = $request->input('settings', []);

        // Handle banner image upload (for sale_banner sections)
        if ($section->type === 'sale_banner') {
            if ($request->hasFile('banner_image')) {
                // Delete old image if exists
                $oldImage = $section->settings['banner_image'] ?? null;
                if ($oldImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldImage)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
                }
                $settings['banner_image'] = $request->file('banner_image')->store('homepage', 'public');
            } elseif ($request->boolean('remove_banner_image')) {
                // Remove existing image
                $oldImage = $section->settings['banner_image'] ?? null;
                if ($oldImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldImage)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
                }
                $settings['banner_image'] = null;
            } else {
                // Preserve existing image path
                $settings['banner_image'] = $section->settings['banner_image'] ?? null;
            }
        }

        $data['settings'] = $settings;

        $section->update($data);

        // Sync manually assigned products
        if (in_array($section->type, ['products', 'widgets'])) {
            $productIds = $request->input('assigned_products', []);
            $syncData = [];
            foreach ($productIds as $index => $productId) {
                $syncData[$productId] = ['sort_order' => $index];
            }
            $section->products()->sync($syncData);
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Section updated successfully.');
    }

    /**
     * Quick toggle section visibility via AJAX.
     */
    public function toggleActive(HomepageSection $section)
    {
        $section->update(['is_active' => !$section->is_active]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'is_active' => $section->is_active]);
        }

        return back()->with('success', "Section '{$section->title}' " . ($section->is_active ? 'enabled' : 'disabled') . '.');
    }

    /**
     * Reorder sections via AJAX (drag-and-drop).
     */
    public function reorder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            HomepageSection::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Show form to add a custom HTML section.
     */
    public function createCustom()
    {
        return view('admin.homepage.custom-form', [
            'section' => new HomepageSection(),
            'isEdit' => false,
        ]);
    }

    /**
     * Store a new custom HTML section.
     */
    public function storeCustom(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'settings.custom_html' => 'required|string',
        ]);

        $maxSort = HomepageSection::max('sort_order') ?? 0;

        HomepageSection::create([
            'key' => 'custom_' . time(),
            'title' => $request->input('title'),
            'type' => 'custom_html',
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $maxSort + 1,
            'settings' => [
                'custom_html' => $request->input('settings.custom_html'),
                'container_class' => $request->input('settings.container_class', 'container'),
                'css_class' => $request->input('settings.css_class', ''),
                'bg_color' => $request->input('settings.bg_color', ''),
            ],
        ]);

        return redirect()->route('admin.homepage.index')->with('success', 'Custom section added.');
    }

    /**
     * Delete a custom section (only custom_html type).
     */
    public function destroyCustom(HomepageSection $section)
    {
        if ($section->type !== 'custom_html') {
            return back()->with('error', 'Cannot delete built-in sections. You can disable them instead.');
        }

        $section->delete();
        return back()->with('success', 'Custom section deleted.');
    }
}
