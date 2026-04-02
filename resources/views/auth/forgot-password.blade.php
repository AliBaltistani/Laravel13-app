@extends('layouts.minimal')

@section('meta_title', 'Forgot Password - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('auth-content')
<div class="container login-container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="heading mb-1">
                <h2 class="title">Reset Password</h2>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <p class="mb-3">Lost your password? Please enter your email address. You will receive a link to create a new password via email.</p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <label for="reset-email">
                    Email address
                    <span class="required">*</span>
                </label>
                <input type="email" class="form-input form-wide @error('email') is-invalid @enderror"
                       id="reset-email" name="email" value="{{ old('email') }}" required />
                @error('email')
                    <span class="invalid-feedback d-block text-danger mb-2">{{ $message }}</span>
                @enderror

                <div class="form-footer mb-2">
                    <button type="submit" class="btn btn-dark btn-md w-100">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
