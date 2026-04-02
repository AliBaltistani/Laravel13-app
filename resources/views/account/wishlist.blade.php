@extends('layouts.account')

@section('meta_title', 'Wishlist - ' . Setting::get('general.site_name', 'Porto Shop'))

@php $title = 'Wishlist'; @endphp

@section('account-content')
<div class="tab-pane fade show active">
    <h3 class="account-sub-title d-none d-md-block mb-3">
        <i class="icon-wishlist-2 align-middle mr-2"></i>My Wishlist
    </h3>

    @if($wishlistItems->count())
    <div class="table-responsive">
        <table class="table table-bordered table-wishlist">
            <thead>
                <tr>
                    <th class="thumbnail-col"></th>
                    <th class="product-col">Product</th>
                    <th>Price</th>
                    <th>Stock Status</th>
                    <th class="action-col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wishlistItems as $item)
                    @php
                        $product = $item->product;
                        if (!$product) continue;
                        $img = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                        $imgPath = $img ? asset('storage/' . $img->image_path) : asset('themes/porto/images/products/product-1.jpg');
                    @endphp
                    <tr>
                        <td class="thumbnail-col">
                            <figure>
                                <a href="{{ url('/product/' . $product->slug) }}">
                                    <img src="{{ $imgPath }}" width="80" height="80" alt="{{ $product->name }}">
                                </a>
                            </figure>
                        </td>
                        <td class="product-col">
                            <h5 class="product-title">
                                <a href="{{ url('/product/' . $product->slug) }}">{{ $product->name }}</a>
                            </h5>
                            @if($product->category)
                                <small class="text-muted">{{ $product->category->name }}</small>
                            @endif
                        </td>
                        <td class="price-box">
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <span class="old-price">${{ number_format($product->compare_price, 2) }}</span>
                            @endif
                            <span class="product-price">${{ number_format($product->effectivePrice(), 2) }}</span>
                        </td>
                        <td>
                            @if($product->isInStock())
                                <span class="text-success">In Stock</span>
                            @else
                                <span class="text-danger">Out of Stock</span>
                            @endif
                        </td>
                        <td class="action-col">
                            @if($product->isInStock())
                                <button class="btn btn-sm btn-dark" onclick="Livewire.dispatch('addToCart', { productId: {{ $product->id }} })">
                                    <i class="icon-shopping-cart"></i> Add to Cart
                                </button>
                            @endif
                            <form action="{{ url('/wishlist/' . $item->id . '/remove') }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-4">
        <i class="icon-wishlist-2" style="font-size: 48px; color: #ddd;"></i>
        <h4 class="mt-3 text-muted">Your wishlist is empty</h4>
        <a href="{{ url('/shop') }}" class="btn btn-dark mt-2">Browse Products</a>
    </div>
    @endif
</div>
@endsection
