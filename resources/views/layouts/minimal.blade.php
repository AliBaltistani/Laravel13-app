{{-- Minimal layout for auth pages (login, register, forgot password) --}}
@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['title' => $title ?? 'My Account', 'items' => [['label' => 'Shop', 'url' => url('/shop')]]])

    @yield('auth-content')
@endsection
