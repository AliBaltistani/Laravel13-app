@extends('layouts.minimal')

@section('meta_title', 'Forgot Password - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('auth-content')
<div class="container login-container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="heading mb-1">
                <h2 class="title">Forgot Password</h2>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
                </div>
            @endif

            <p class="mb-3 text-muted">
                Enter your email address and we'll send you a one-time verification code to reset your password.
            </p>

            <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form">
                @csrf
                <label for="reset-email">
                    Email address
                    <span class="required">*</span>
                </label>
                <input type="email" class="form-input form-wide @error('email') is-invalid @enderror"
                       id="reset-email" name="email" value="{{ old('email') }}" required autofocus />
                @error('email')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror

                <div class="form-footer mb-2">
                    <button type="submit" class="btn btn-dark btn-md w-100">Send Verification Code</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-muted">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
