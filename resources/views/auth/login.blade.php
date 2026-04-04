@extends('layouts.minimal')

@section('meta_title', 'Login - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('auth-content')
<div class="container login-container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="heading mb-1">
                <h2 class="title">Login</h2>
            </div>

            @if(session('status'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle mr-1"></i> {{ session('info') }}
                </div>
            @endif

            <p class="mb-3 text-muted">Welcome back! Please enter your credentials to access your account.</p>

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf
                <label for="login-email">
                    Email address
                    <span class="required">*</span>
                </label>
                <input type="email" class="form-input form-wide @error('email') is-invalid @enderror"
                       id="login-email" name="email" value="{{ old('email') }}" required autofocus />
                @error('email')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror

                <label for="login-password">
                    Password
                    <span class="required">*</span>
                </label>
                <input type="password" class="form-input form-wide @error('password') is-invalid @enderror"
                       id="login-password" name="password" required />
                @error('password')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror

                <div class="form-footer">
                    <div class="custom-control custom-checkbox mb-0">
                        <input type="checkbox" class="custom-control-input" id="remember-me" name="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label mb-0" for="remember-me">Remember me</label>
                    </div>

                    <a href="{{ route('password.request') }}"
                       class="forget-password text-dark form-footer-right">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-dark btn-md w-100">LOGIN</button>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <p class="mb-0">Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary font-weight-bold">Create Account</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
