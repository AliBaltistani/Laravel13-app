@extends('layouts.app')

@section('meta_title', 'Shopping Cart - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
    @include('partials.breadcrumb', ['title' => 'Shopping Cart', 'items' => [['label' => 'Shop', 'url' => url('/shop')]]])

    <div class="container">
        @livewire('cart-page')
    </div>
@endsection
