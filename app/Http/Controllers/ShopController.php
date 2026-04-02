<?php

namespace App\Http\Controllers;

use App\Models\AttributeGroup;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['images', 'category', 'brand', 'flashSaleProducts.flashSale', 'approvedReviews']);

        $query = $this->applyFilters($query, $request);

        $products = $query->paginate($request->get('per_page', 12))->withQueryString();

        $categories = Category::active()->root()->ordered()->withCount(['products' => fn($q) => $q->where('is_active', true)])->get();
        $brands = Brand::active()->ordered()->withCount(['products' => fn($q) => $q->where('is_active', true)])->get();
        $attributeGroups = AttributeGroup::with(['attributes'])->orderBy('sort_order')->get();
        $tags = Tag::withCount('products')->having('products_count', '>', 0)->orderByDesc('products_count')->take(15)->get();

        $priceRange = Product::active()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        return view('pages.shop.index', compact('products', 'categories', 'brands', 'attributeGroups', 'tags', 'priceRange'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();
        $childIds = $category->children()->active()->pluck('id')->push($category->id);

        $query = Product::active()->whereIn('category_id', $childIds)
            ->with(['images', 'category', 'brand', 'flashSaleProducts.flashSale', 'approvedReviews']);

        $query = $this->applyFilters($query, request());

        $products = $query->paginate(request()->get('per_page', 12))->withQueryString();

        $categories = Category::active()->root()->ordered()->withCount(['products' => fn($q) => $q->where('is_active', true)])->get();
        $brands = Brand::active()->ordered()->withCount(['products' => fn($q) => $q->where('is_active', true)])->get();
        $attributeGroups = AttributeGroup::with(['attributes'])->orderBy('sort_order')->get();
        $tags = Tag::withCount('products')->having('products_count', '>', 0)->orderByDesc('products_count')->take(15)->get();
        $priceRange = Product::active()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        return view('pages.shop.index', compact('products', 'categories', 'brands', 'attributeGroups', 'tags', 'priceRange', 'category'));
    }

    public function brand(string $slug)
    {
        $brand = Brand::where('slug', $slug)->active()->firstOrFail();

        $query = Product::active()->where('brand_id', $brand->id)
            ->with(['images', 'category', 'brand', 'flashSaleProducts.flashSale', 'approvedReviews']);

        $query = $this->applyFilters($query, request());

        $products = $query->paginate(request()->get('per_page', 12))->withQueryString();

        $categories = Category::active()->root()->ordered()->withCount(['products' => fn($q) => $q->where('is_active', true)])->get();
        $brands = Brand::active()->ordered()->withCount(['products' => fn($q) => $q->where('is_active', true)])->get();
        $attributeGroups = AttributeGroup::with(['attributes'])->orderBy('sort_order')->get();
        $tags = Tag::withCount('products')->having('products_count', '>', 0)->orderByDesc('products_count')->take(15)->get();
        $priceRange = Product::active()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        $currentBrand = $brand;

        return view('pages.shop.index', compact('products', 'categories', 'brands', 'attributeGroups', 'tags', 'priceRange', 'currentBrand'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $query = Product::active()
            ->with(['images', 'category', 'brand', 'flashSaleProducts.flashSale', 'approvedReviews'])
            ->where(function ($qb) use ($q) {
                $qb->where('name', 'LIKE', "%{$q}%")
                   ->orWhere('sku', 'LIKE', "%{$q}%")
                   ->orWhere('short_description', 'LIKE', "%{$q}%")
                   ->orWhere('meta_keywords', 'LIKE', "%{$q}%");
            });

        $query = $this->applyFilters($query, $request);

        $products = $query->paginate($request->get('per_page', 12))->withQueryString();

        $categories = Category::active()->root()->ordered()->withCount(['products' => fn($qb) => $qb->where('is_active', true)])->get();
        $brands = Brand::active()->ordered()->withCount(['products' => fn($qb) => $qb->where('is_active', true)])->get();
        $attributeGroups = AttributeGroup::with(['attributes'])->orderBy('sort_order')->get();
        $tags = Tag::withCount('products')->having('products_count', '>', 0)->orderByDesc('products_count')->take(15)->get();
        $priceRange = Product::active()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        $searchQuery = $q;

        return view('pages.shop.index', compact('products', 'categories', 'brands', 'attributeGroups', 'tags', 'priceRange', 'searchQuery'));
    }

    public function quickView(Product $product)
    {
        $product->load(['images', 'variants.attributes', 'category', 'brand', 'flashSaleProducts.flashSale']);
        return view('partials.quick-view', compact('product'));
    }

    /**
     * Apply common filters to a product query.
     */
    private function applyFilters($query, Request $request)
    {
        // Category filter
        if ($request->has('category') && $request->category) {
            $cat = Category::where('slug', $request->category)->first();
            if ($cat) {
                $catIds = $cat->children()->active()->pluck('id')->push($cat->id);
                $query->whereIn('category_id', $catIds);
            }
        }

        // Brand filter
        if ($request->has('brand') && $request->brand) {
            $brandSlugs = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
            $brandIds = Brand::whereIn('slug', $brandSlugs)->pluck('id');
            if ($brandIds->isNotEmpty()) {
                $query->whereIn('brand_id', $brandIds);
            }
        }

        // Price range
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('price', '<=', (float)$request->max_price);
        }

        // In stock only
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        // On sale
        if ($request->boolean('on_sale')) {
            $query->onSale();
        }

        // Sort
        $sort = $request->get('sort', 'default');
        $query = match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest' => $query->orderByDesc('created_at'),
            'popularity' => $query->orderByDesc('sold_count'),
            'rating' => $query->withAvg('approvedReviews', 'rating')->orderByDesc('approved_reviews_avg_rating'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderByDesc('is_featured')->orderByDesc('created_at'),
        };

        return $query;
    }
}
