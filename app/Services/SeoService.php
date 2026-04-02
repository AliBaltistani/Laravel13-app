<?php

namespace App\Services;

use App\Models\Setting;

/**
 * SeoService — Phase 9-A
 * Fluent interface for setting SEO metadata per page.
 * Rendered by the x-seo.head Blade component.
 */
class SeoService
{
    protected string $title = '';
    protected string $description = '';
    protected string $image = '';
    protected string $canonical = '';
    protected array $jsonLd = [];
    protected bool $noIndex = false;
    protected bool $withSuffix = true;

    /**
     * Set the page title.
     */
    public function setTitle(string $title, bool $withSuffix = true): static
    {
        $this->title = $title;
        $this->withSuffix = $withSuffix;
        return $this;
    }

    /**
     * Set the meta description.
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the OG image URL.
     */
    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Set the canonical URL.
     */
    public function setCanonical(string $canonical): static
    {
        $this->canonical = $canonical;
        return $this;
    }

    /**
     * Set JSON-LD structured data.
     */
    public function setJsonLd(array $jsonLd): static
    {
        $this->jsonLd = $jsonLd;
        return $this;
    }

    /**
     * Mark page as noindex.
     */
    public function setNoIndex(bool $noIndex = true): static
    {
        $this->noIndex = $noIndex;
        return $this;
    }

    /**
     * Get the full page title with optional site name suffix.
     */
    public function getTitle(): string
    {
        $title = $this->title ?: Setting::get('seo.default_meta_title', config('app.name'));
        $siteName = Setting::get('general.site_name', config('app.name'));

        if ($this->withSuffix && $title !== $siteName) {
            return "{$title} | {$siteName}";
        }

        return $title;
    }

    /**
     * Get the meta description.
     */
    public function getDescription(): string
    {
        return $this->description ?: Setting::get('seo.default_meta_description', '');
    }

    /**
     * Get the OG image URL.
     */
    public function getImage(): string
    {
        if ($this->image) {
            return $this->image;
        }

        $defaultImage = Setting::get('seo.default_og_image');
        return $defaultImage ? asset('storage/' . $defaultImage) : '';
    }

    /**
     * Get the canonical URL.
     */
    public function getCanonical(): string
    {
        return $this->canonical ?: url()->current();
    }

    /**
     * Get JSON-LD structured data as a JSON string.
     */
    public function getJsonLd(): string
    {
        if (empty($this->jsonLd)) {
            return '';
        }

        return json_encode($this->jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Check if page should be noindex.
     */
    public function isNoIndex(): bool
    {
        return $this->noIndex;
    }

    /**
     * Get the site name from settings.
     */
    public function getSiteName(): string
    {
        return Setting::get('general.site_name', config('app.name'));
    }

    /**
     * Generate Organization JSON-LD for the homepage (Phase 9-B).
     */
    public static function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => Setting::get('general.site_name', config('app.name')),
            'url' => url('/'),
            'logo' => Setting::get('general.logo') ? asset('storage/' . Setting::get('general.logo')) : '',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => Setting::get('contact.phone', ''),
                'email' => Setting::get('contact.email', ''),
                'contactType' => 'customer service',
            ],
            'sameAs' => array_filter([
                Setting::get('social.facebook_url'),
                Setting::get('social.twitter_url'),
                Setting::get('social.instagram_url'),
                Setting::get('social.linkedin_url'),
                Setting::get('social.youtube_url'),
            ]),
        ];
    }

    /**
     * Generate LocalBusiness JSON-LD for the contact page (Phase 9-B).
     */
    public static function localBusinessSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => Setting::get('general.site_name', config('app.name')),
            'url' => url('/'),
            'telephone' => Setting::get('contact.phone', ''),
            'email' => Setting::get('contact.email', ''),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => Setting::get('contact.address', ''),
            ],
            'openingHours' => Setting::get('contact.working_hours', ''),
        ];
    }

    /**
     * Generate BreadcrumbList JSON-LD (Phase 9-B).
     */
    public static function breadcrumbSchema(array $items): array
    {
        $listItems = [];
        foreach ($items as $i => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
    }
}
