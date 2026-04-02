@extends('layouts.app')

@section('meta_title', 'Checkout - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', ['title' => 'Checkout', 'items' => [['label' => 'Shop', 'url' => url('/shop')], ['label' => 'Cart', 'url' => url('/cart')]]])

    <div class="container">
        @livewire('checkout-page')
    </div>
@endsection
