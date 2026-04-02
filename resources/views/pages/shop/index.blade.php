@extends('layouts.app')

@section('meta_title', isset($category) ? $category->name . ' - ' : (isset($searchQuery) ? 'Search: ' . $searchQuery . ' - ' : (isset($currentBrand) ? $currentBrand->name . ' - ' : 'Shop - ')) . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    {{-- Breadcrumb --}}
    <div class="container">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ url('/shop') }}">Shop</a></li>
                @if(isset($category))
                    @if($category->parent)
                        <li class="breadcrumb-item"><a href="{{ url('/shop/category/' . $category->parent->slug) }}">{{ $category->parent->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                @elseif(isset($searchQuery))
                    <li class="breadcrumb-item active" aria-current="page">Search: "{{ $searchQuery }}"</li>
                @elseif(isset($currentBrand))
                    <li class="breadcrumb-item active" aria-current="page">{{ $currentBrand->name }}</li>
                @endif
            </ol>
        </nav>

        <div class="row">
            {{-- Main Content --}}
            <div class="col-lg-9 main-content">
                @if(isset($searchQuery))
                    <h3 class="mb-3">Search results for: "{{ $searchQuery }}"</h3>
                @endif

                {{-- Toolbar --}}
                <nav class="toolbox sticky-header" data-sticky-options="{'mobile': true}">
                    <div class="toolbox-left">
                        <a href="#" class="sidebar-toggle">
                            <svg data-name="Layer 3" id="Layer_3" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                <line x1="15" x2="26" y1="9" y2="9" class="cls-1"></line>
                                <line x1="6" x2="9" y1="9" y2="9" class="cls-1"></line>
                                <line x1="23" x2="26" y1="16" y2="16" class="cls-1"></line>
                                <line x1="6" x2="17" y1="16" y2="16" class="cls-1"></line>
                                <line x1="17" x2="26" y1="23" y2="23" class="cls-1"></line>
                                <line x1="6" x2="11" y1="23" y2="23" class="cls-1"></line>
                                <path d="M14.5,8.92A2.6,2.6,0,0,1,12,11.5,2.6,2.6,0,0,1,9.5,8.92a2.5,2.5,0,0,1,5,0Z" class="cls-2"></path>
                                <path d="M22.5,15.92a2.5,2.5,0,1,1-5,0,2.5,2.5,0,0,1,5,0Z" class="cls-2"></path>
                                <path d="M16.5,22.92A2.6,2.6,0,0,1,14,25.5a2.6,2.6,0,0,1-2.5-2.58,2.5,2.5,0,0,1,5,0Z" class="cls-2"></path>
                            </svg>
                            <span>Filter</span>
                        </a>

                        <div class="toolbox-item toolbox-sort">
                            <label>Sort By:</label>
                            <div class="select-custom">
                                <select name="orderby" class="form-control" id="shop-sort" onchange="updateSort(this.value)">
                                    <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Default sorting</option>
                                    <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Sort by popularity</option>
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Sort by average rating</option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort by newness</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Sort by price: low to high</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Sort by price: high to low</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="toolbox-right">
                        <div class="toolbox-item toolbox-show">
                            <label>Show:</label>
                            <div class="select-custom">
                                <select name="count" class="form-control" onchange="updatePerPage(this.value)">
                                    <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12</option>
                                    <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                                    <option value="36" {{ request('per_page') == 36 ? 'selected' : '' }}>36</option>
                                </select>
                            </div>
                        </div>

                        <div class="toolbox-item layout-modes">
                            <a href="#" class="layout-btn btn-grid active" title="Grid" onclick="setViewMode('grid')">
                                <i class="icon-mode-grid"></i>
                            </a>
                            <a href="#" class="layout-btn btn-list" title="List" onclick="setViewMode('list')">
                                <i class="icon-mode-list"></i>
                            </a>
                        </div>
                    </div>
                </nav>

                {{-- Products Grid --}}
                <div class="row" id="products-grid">
                    @forelse($products as $product)
                        <div class="col-6 col-sm-4">
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="icon-bag-1" style="font-size: 48px; color: #ccc;"></i>
                                <h4 class="mt-3 text-muted">No products found</h4>
                                <p class="text-muted">Try adjusting your filters or <a href="{{ url('/shop') }}">browse all products</a>.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                    <nav class="toolbox toolbox-pagination">
                        <div class="toolbox-item toolbox-show">
                            <label>Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results</label>
                        </div>
                        <ul class="pagination">
                            @if($products->onFirstPage())
                                <li class="page-item disabled"><span class="page-link page-link-btn"><i class="icon-angle-left"></i></span></li>
                            @else
                                <li class="page-item"><a class="page-link page-link-btn" href="{{ $products->previousPageUrl() }}"><i class="icon-angle-left"></i></a></li>
                            @endif

                            @foreach($products->getUrlRange(max(1, $products->currentPage()-2), min($products->lastPage(), $products->currentPage()+2)) as $page => $url)
                                <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            @if($products->hasMorePages())
                                <li class="page-item"><a class="page-link page-link-btn" href="{{ $products->nextPageUrl() }}"><i class="icon-angle-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link page-link-btn"><i class="icon-angle-right"></i></span></li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="sidebar-shop col-lg-3 order-lg-first mobile-sidebar">
                <div class="sidebar-wrapper">
                    {{-- Category Filter --}}
                    <div class="widget">
                        <h3 class="widget-title"><a data-toggle="collapse" href="#widget-body-cat" role="button" aria-expanded="true" aria-controls="widget-body-cat">Categories</a></h3>
                        <div class="collapse show" id="widget-body-cat">
                            <div class="widget-body">
                                <ul class="cat-list">
                                    @foreach($categories as $cat)
                                        <li {{ isset($category) && $category->id == $cat->id ? 'class=active' : '' }}>
                                            <a href="{{ url('/shop/category/' . $cat->slug) }}">{{ $cat->name }} <span class="products-count">({{ $cat->products_count }})</span></a>
                                            @if($cat->children->count())
                                                <ul class="cat-sublist">
                                                    @foreach($cat->children()->active()->ordered()->withCount(['products' => fn($q) => $q->where('is_active', true)])->get() as $child)
                                                        <li {{ isset($category) && $category->id == $child->id ? 'class=active' : '' }}>
                                                            <a href="{{ url('/shop/category/' . $child->slug) }}">{{ $child->name }} <span class="products-count">({{ $child->products_count }})</span></a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Price Range Filter --}}
                    <div class="widget">
                        <h3 class="widget-title"><a data-toggle="collapse" href="#widget-body-price" role="button" aria-expanded="true" aria-controls="widget-body-price">Price</a></h3>
                        <div class="collapse show" id="widget-body-price">
                            <div class="widget-body pb-0">
                                <form action="{{ url()->current() }}" method="GET" id="price-filter-form">
                                    @foreach(request()->except(['min_price', 'max_price', 'page']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <div class="price-range-wrapper">
                                        <input type="number" class="form-control form-control-sm d-inline-block" style="width: 45%"
                                               name="min_price" placeholder="Min" value="{{ request('min_price', '') }}" min="0">
                                        <span class="mx-1">—</span>
                                        <input type="number" class="form-control form-control-sm d-inline-block" style="width: 45%"
                                               name="max_price" placeholder="Max" value="{{ request('max_price', '') }}" min="0">
                                    </div>
                                    <button type="submit" class="btn btn-dark btn-sm btn-block mt-2">Filter Price</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Brand Filter --}}
                    @if($brands->count())
                    <div class="widget">
                        <h3 class="widget-title"><a data-toggle="collapse" href="#widget-body-brand" role="button" aria-expanded="true" aria-controls="widget-body-brand">Brands</a></h3>
                        <div class="collapse show" id="widget-body-brand">
                            <div class="widget-body pb-0">
                                <ul class="cat-list">
                                    @foreach($brands as $brand)
                                        <li {{ isset($currentBrand) && $currentBrand->id == $brand->id ? 'class=active' : '' }}>
                                            <a href="{{ url('/shop/brand/' . $brand->slug) }}">{{ $brand->name }} <span class="products-count">({{ $brand->products_count }})</span></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Tags --}}
                    @if(isset($tags) && $tags->count())
                    <div class="widget">
                        <h3 class="widget-title">Popular Tags</h3>
                        <div class="widget-body">
                            <div class="tags">
                                @foreach($tags as $tag)
                                    <a href="{{ url('/shop?tag=' . $tag->slug) }}" class="tag">{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function updateSort(value) {
        var url = new URL(window.location.href);
        url.searchParams.set('sort', value);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
    function updatePerPage(value) {
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
</script>
@endpush
