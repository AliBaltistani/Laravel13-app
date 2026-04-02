@extends('layouts.minimal')

@section('meta_title', 'Reset Password - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('auth-content')
<div class="container login-container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="heading mb-1">
                <h2 class="title">Set New Password</h2>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">

                <label for="email">Email address <span class="required">*</span></label>
                <input type="email" class="form-input form-wide @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email', request('email')) }}" required />
                @error('email')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror

                <label for="password">New Password <span class="required">*</span></label>
                <input type="password" class="form-input form-wide @error('password') is-invalid @enderror"
                       id="password" name="password" required />
                @error('password')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror

                <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                <input type="password" class="form-input form-wide" id="password_confirmation" name="password_confirmation" required />

                <div class="form-footer mb-2">
                    <button type="submit" class="btn btn-dark btn-md w-100">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
