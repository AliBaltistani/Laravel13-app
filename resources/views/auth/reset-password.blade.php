@extends('layouts.minimal')

@section('meta_title', 'Reset Password - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('auth-content')
<div class="container login-container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="heading mb-1">
                <h2 class="title">Set New Password</h2>
            </div>

            <div class="alert alert-success mb-3" style="font-size: 13px;">
                <i class="fas fa-check-circle mr-1"></i> Email verified successfully! Please set your new password.
            </div>

            <form method="POST" action="{{ route('password.update') }}" id="reset-password-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-3" style="background: #f8f9fa; border-radius: 8px; padding: 10px 16px;">
                    <small class="text-muted d-block mb-0">Resetting password for</small>
                    <span class="font-weight-bold" style="color: #1e2a3a;">{{ $email }}</span>
                </div>

                <label for="password">New Password <span class="required">*</span></label>
                <input type="password" class="form-input form-wide @error('password') is-invalid @enderror"
                       id="password" name="password" required
                       minlength="{{ Setting::get('auth.password_min_length', 8) }}" />
                <small class="text-muted d-block mb-2">Minimum {{ Setting::get('auth.password_min_length', 8) }} characters</small>
                @error('password')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror

                <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                <input type="password" class="form-input form-wide" id="password_confirmation" name="password_confirmation" required />

                <div class="form-footer mb-2">
                    <button type="submit" class="btn btn-dark btn-md w-100">Reset Password</button>
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
