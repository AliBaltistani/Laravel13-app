<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\SliderSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::withCount('slides')->orderBy('name')->paginate(20);
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.form', ['slider' => new Slider(), 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        Slider::create($data);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider created.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.form', ['slider' => $slider, 'isEdit' => true]);
    }

    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $slider->update($data);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated.');
    }

    public function destroy(Slider $slider)
    {
        $slider->slides()->delete();
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted.');
    }

    public function slides(Slider $slider)
    {
        $slides = $slider->slides()->orderBy('sort_order')->get();
        return view('admin.sliders.slides', compact('slider', 'slides'));
    }

    public function storeSlide(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:500',
            'text_color' => 'nullable|string|max:20',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'image_desktop' => 'nullable|image|max:2048',
            'image_mobile' => 'nullable|image|max:2048',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['slider_id'] = $slider->id;
        if ($request->hasFile('image_desktop')) {
            $data['image_desktop'] = $request->file('image_desktop')->store('sliders', 'public');
        }
        if ($request->hasFile('image_mobile')) {
            $data['image_mobile'] = $request->file('image_mobile')->store('sliders', 'public');
        }
        SliderSlide::create($data);
        return back()->with('success', 'Slide added.');
    }

    public function updateSlide(Request $request, Slider $slider, SliderSlide $slide)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'text_color' => 'nullable|string|max:20',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'image_desktop' => 'nullable|image|max:2048',
            'image_mobile' => 'nullable|image|max:2048',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image_desktop')) {
            if ($slide->image_desktop) Storage::disk('public')->delete($slide->image_desktop);
            $data['image_desktop'] = $request->file('image_desktop')->store('sliders', 'public');
        }
        if ($request->hasFile('image_mobile')) {
            if ($slide->image_mobile) Storage::disk('public')->delete($slide->image_mobile);
            $data['image_mobile'] = $request->file('image_mobile')->store('sliders', 'public');
        }
        $slide->update($data);
        return back()->with('success', 'Slide updated.');
    }

    public function destroySlide(Slider $slider, SliderSlide $slide)
    {
        $slide->delete();
        return back()->with('success', 'Slide deleted.');
    }
}
