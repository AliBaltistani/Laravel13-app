<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function show($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->active()->firstOrFail();
        return view('pages.show', compact('page'));
    }
}
