<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Jobs\SendContactAutoReply;
use App\Jobs\SendContactNotification;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Services\SeoService;

class PageController extends Controller
{
    /**
     * Show a CMS page by slug (Phase 8-D).
     * All CMS pages (about, terms, privacy, etc.) use this single method.
     */
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // SEO — Phase 9-A
        app(SeoService::class)
            ->setTitle($page->meta_title ?: $page->title)
            ->setDescription($page->meta_description ?: strip_tags(substr($page->content ?? '', 0, 160)));

        return view('pages.show', compact('page'));
    }

    /**
     * Contact page (Phase 8-E) with LocalBusiness schema (Phase 9-B).
     */
    public function contact()
    {
        // SEO — Phase 9-A/9-B: LocalBusiness JSON-LD
        app(SeoService::class)
            ->setTitle('Contact Us')
            ->setDescription('Get in touch with us for questions, support, or feedback')
            ->setJsonLd(SeoService::localBusinessSchema());

        return view('pages.contact');
    }

    /**
     * Submit contact form (Phase 8-E).
     */
    public function submitContact(ContactFormRequest $request)
    {
        $message = ContactMessage::create($request->validated());

        SendContactNotification::dispatch($message);
        SendContactAutoReply::dispatch($message);

        return back()->with('success', 'Thank you! Your message has been sent. We\'ll get back to you as soon as possible.');
    }

    /**
     * Promotions page to show available public coupons.
     */
    public function promotions()
    {
        app(SeoService::class)
            ->setTitle('Current Promotions & Coupons')
            ->setDescription('View all active promo codes and discounts to save on your next purchase.');

        $coupons = \App\Models\Coupon::where('is_active', true)->get();

        return view('pages.promotions', compact('coupons'));
    }
}
