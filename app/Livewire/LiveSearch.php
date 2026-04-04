<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class LiveSearch extends Component
{
    public $query = '';
    public $categorySlug = '';
    public $showResults = false;

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->showResults = false;
            $this->dispatch('liveSearchResults', results: [], query: $this->query, loading: false);
            return;
        }

        // Dispatch loading state first
        $this->dispatch('liveSearchResults', results: [], query: $this->query, loading: true);

        $this->performSearch();
    }

    public function updatedCategorySlug()
    {
        if ($this->query && strlen($this->query) >= 2) {
            $this->dispatch('liveSearchResults', results: [], query: $this->query, loading: true);
            $this->performSearch();
        }
    }

    public function performSearch()
    {
        $q = Product::active()
            ->with(['images', 'category'])
            ->where(function ($qb) {
                $qb->where('name', 'LIKE', '%' . $this->query . '%')
                   ->orWhere('sku', 'LIKE', '%' . $this->query . '%')
                   ->orWhere('short_description', 'LIKE', '%' . $this->query . '%');
            });

        // Filter by category if selected
        if ($this->categorySlug) {
            $category = Category::where('slug', $this->categorySlug)->first();
            if ($category) {
                $catIds = $category->children()->active()->pluck('id')->push($category->id);
                $q->whereIn('category_id', $catIds);
            }
        }

        $currency = config('app.currency_symbol', '£');

        $results = $q->orderByDesc('is_featured')
            ->orderByDesc('sold_count')
            ->take(8)
            ->get()
            ->map(function ($product) use ($currency) {
                $img = $product->images->where('is_primary', true)->first()
                    ?? $product->images->first();
                $price = $product->effectivePrice();
                return [
                    'id'               => $product->id,
                    'name'             => $product->name,
                    'slug'             => $product->slug,
                    'price'            => $price,
                    'price_fmt'        => $currency . number_format($price, 2),
                    'compare_price'    => $product->compare_price,
                    'compare_price_fmt'=> $product->compare_price ? $currency . number_format($product->compare_price, 2) : '',
                    'image'            => $img ? asset('storage/' . $img->image_path) : asset('images/no-image.svg'),
                    'category'         => $product->category?->name,
                    'url'              => url('/product/' . $product->slug),
                ];
            })
            ->toArray();

        $this->showResults = true;
        $this->dispatch('liveSearchResults', results: $results, query: $this->query, loading: false);
    }

    public function hideResults()
    {
        $this->showResults = false;
        $this->dispatch('liveSearchResults', results: [], query: '', loading: false);
    }

    public function submitSearch()
    {
        $params = ['q' => $this->query];
        if ($this->categorySlug) {
            $params['category'] = $this->categorySlug;
        }
        return redirect()->to('/shop/search?' . http_build_query($params));
    }

    public function render()
    {
        $categories = Category::active()->root()->ordered()->get();
        return view('livewire.live-search', [
            'categories' => $categories,
        ]);
    }
}
