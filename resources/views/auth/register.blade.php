@extends('layouts.minimal')

@section('meta_title', 'Create Account - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('auth-content')
<div class="container login-container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="heading mb-1">
                <h2 class="title">Create Account</h2>
            </div>

            <p class="mb-3 text-muted">Join us today! Fill in your details to create your account.</p>

            <form method="POST" action="{{ route('register.post') }}" id="register-form">
                @csrf

                <label for="register-name">
                    Full Name
                    <span class="required">*</span>
                </label>
                <input type="text" class="form-input form-wide @error('name') is-invalid @enderror"
                       id="register-name" name="name" value="{{ old('name') }}" required autofocus />
                @error('name')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror

                <label for="register-email">
                    Email address
                    <span class="required">*</span>
                </label>
                <input type="email" class="form-input form-wide @error('email') is-invalid @enderror"
                       id="register-email" name="email" value="{{ old('email') }}" required />
                @error('email')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror

                <label for="register-password">
                    Password
                    <span class="required">*</span>
                </label>
                <input type="password" class="form-input form-wide @error('password') is-invalid @enderror"
                       id="register-password" name="password" required
                       minlength="{{ Setting::get('auth.password_min_length', 8) }}" />
                @error('password')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror

                <label for="register-password-confirm">
                    Confirm Password
                    <span class="required">*</span>
                </label>
                <input type="password" class="form-input form-wide"
                       id="register-password-confirm" name="password_confirmation" required />

                {{-- Terms & Conditions --}}
                @if(Setting::get('auth.terms_required', true))
                <div class="custom-control custom-checkbox mt-3 mb-2">
                    <input type="checkbox" class="custom-control-input @error('terms') is-invalid @enderror"
                           id="accept-terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="accept-terms">
                        I agree to the
                        <a href="{{ route('terms') }}" target="_blank" class="text-primary">Terms & Conditions</a>
                        and
                        <a href="{{ route('privacy') }}" target="_blank" class="text-primary">Privacy Policy</a>
                        <span class="required">*</span>
                    </label>
                </div>
                @error('terms')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror
                @endif

                {{-- Newsletter --}}
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input"
                           id="newsletter-subscribe" name="newsletter" value="1" {{ old('newsletter') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="newsletter-subscribe">
                        Subscribe to our newsletter for updates and offers
                    </label>
                </div>

                <div class="form-footer mb-2">
                    <button type="submit" class="btn btn-dark btn-md w-100 mr-0">CREATE ACCOUNT</button>
                </div>
            </form>

            <div class="text-center mt-3 pt-3 border-top">
                <p class="mb-0">Already have an account?
                    <a href="{{ route('login') }}" class="text-primary font-weight-bold">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
