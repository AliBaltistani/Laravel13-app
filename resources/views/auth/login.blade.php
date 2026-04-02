@extends('layouts.minimal')

@section('meta_title', 'Login - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('auth-content')
<div class="container login-container">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="row">
                {{-- Login Form --}}
                <div class="col-md-6">
                    <div class="heading mb-1">
                        <h2 class="title">Login</h2>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <label for="login-email">
                            Username or email address
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
                </div>

                {{-- Register Form --}}
                <div class="col-md-6">
                    <div class="heading mb-1">
                        <h2 class="title">Register</h2>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <label for="register-name">
                            Full Name
                            <span class="required">*</span>
                        </label>
                        <input type="text" class="form-input form-wide @error('name') is-invalid @enderror"
                               id="register-name" name="name" value="{{ old('name') }}" required />
                        @error('name')
                            <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                        @enderror

                        <label for="register-email">
                            Email address
                            <span class="required">*</span>
                        </label>
                        <input type="email" class="form-input form-wide @error('register_email') is-invalid @enderror"
                               id="register-email" name="register_email" value="{{ old('register_email') }}" required />
                        @error('register_email')
                            <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                        @enderror

                        <label for="register-password">
                            Password
                            <span class="required">*</span>
                        </label>
                        <input type="password" class="form-input form-wide @error('register_password') is-invalid @enderror"
                               id="register-password" name="register_password" required />
                        @error('register_password')
                            <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                        @enderror

                        <label for="register-password-confirm">
                            Confirm Password
                            <span class="required">*</span>
                        </label>
                        <input type="password" class="form-input form-wide"
                               id="register-password-confirm" name="register_password_confirmation" required />

                        <div class="form-footer mb-2">
                            <button type="submit" class="btn btn-dark btn-md w-100 mr-0">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
