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
     * About page (Phase 8-E).
     */
    public function about()
    {
        $page = Page::where('slug', 'about-us')->where('is_active', true)->first();

        // SEO — Phase 9-A
        app(SeoService::class)
            ->setTitle('About Us')
            ->setDescription('Learn more about our story, mission, and values');

        return view('pages.about', compact('page'));
    }

    /**
     * Contact page (Phase 8-E) with LocalBusiness schema (Phase 9-B).
     */
    public function contact()
    {
        $page = Page::where('slug', 'contact')->where('is_active', true)->first();

        // SEO — Phase 9-A/9-B: LocalBusiness JSON-LD
        app(SeoService::class)
            ->setTitle($page?->meta_title ?? 'Contact Us')
            ->setDescription($page?->meta_description ?? 'Get in touch with us for questions, support, or feedback')
            ->setJsonLd(SeoService::localBusinessSchema());

        return view('pages.contact', compact('page'));
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
     * Terms & Conditions page.
     */
    public function terms()
    {
        $page = Page::where('slug', 'terms-conditions')->where('is_active', true)->firstOrFail();

        app(SeoService::class)
            ->setTitle($page->meta_title ?: 'Terms & Conditions')
            ->setDescription($page->meta_description ?: 'Read our terms and conditions before using our website and services.');

        return view('pages.terms', compact('page'));
    }

    /**
     * Privacy Policy page.
     */
    public function privacy()
    {
        $page = Page::where('slug', 'privacy-policy')->where('is_active', true)->firstOrFail();

        app(SeoService::class)
            ->setTitle($page->meta_title ?: 'Privacy Policy')
            ->setDescription($page->meta_description ?: 'Read our privacy policy to understand how we collect, use, and protect your personal data.');

        return view('pages.privacy', compact('page'));
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
