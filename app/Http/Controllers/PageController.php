<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('pages.page', compact('page'));
    }

    public function about()
    {
        $page = Page::where('slug', 'about-us')->where('is_active', true)->first();
        return view('pages.about', compact('page'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Thank you! Your message has been sent. We\'ll get back to you as soon as possible.');
    }
}
