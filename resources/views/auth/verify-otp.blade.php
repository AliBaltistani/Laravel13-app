@extends('layouts.minimal')

@section('meta_title', 'Verify OTP - ' . Setting::get('general.site_name', 'Porto Shop'))

@push('styles')
<style>
    .otp-input-group {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin: 24px 0;
    }
    .otp-input-group input {
        width: 52px;
        height: 58px;
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        outline: none;
        transition: all 0.2s;
        color: #1e2a3a;
    }
    .otp-input-group input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }
    .otp-input-group input.is-invalid {
        border-color: #dc3545;
    }
    .resend-timer {
        color: #6c757d;
        font-size: 14px;
    }
    .resend-timer a {
        color: #0d6efd;
        font-weight: 600;
    }
    .resend-timer a.disabled {
        color: #adb5bd;
        pointer-events: none;
        text-decoration: none;
    }
    .email-badge {
        display: inline-block;
        background: #f0f4ff;
        color: #0d6efd;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 16px;
    }
</style>
@endpush

@section('auth-content')
<div class="container login-container">
    <div class="row">
        <div class="col-lg-6 mx-auto text-center">
            <div class="heading mb-1">
                <h2 class="title">Verify Your Email</h2>
            </div>

            @if(session('status'))
                <div class="alert alert-success text-left" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
                </div>
            @endif

            <div class="email-badge">
                <i class="fas fa-envelope mr-1"></i> {{ $email }}
            </div>

            <p class="mb-1 text-muted">We've sent a 6-digit verification code to your email.</p>
            <p class="mb-3 text-muted" style="font-size: 13px;">Please enter the code below to continue.</p>

            <form method="POST" action="{{ route('password.otp.verify') }}" id="verify-otp-form">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="otp" id="otp-hidden">

                <div class="otp-input-group">
                    <input type="text" maxlength="1" class="otp-digit @error('otp') is-invalid @enderror" data-index="0" autofocus inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-digit @error('otp') is-invalid @enderror" data-index="1" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-digit @error('otp') is-invalid @enderror" data-index="2" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-digit @error('otp') is-invalid @enderror" data-index="3" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-digit @error('otp') is-invalid @enderror" data-index="4" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-digit @error('otp') is-invalid @enderror" data-index="5" inputmode="numeric">
                </div>

                @error('otp')
                    <span class="text-danger d-block mb-3" style="font-size: 13px;">{{ $message }}</span>
                @enderror

                <div class="form-footer mb-2">
                    <button type="submit" class="btn btn-dark btn-md w-100" id="verify-btn">Verify Code</button>
                </div>
            </form>

            <div class="resend-timer mt-3">
                <span id="resend-text">Didn't receive the code? </span>
                <form method="POST" action="{{ route('password.otp.resend') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <a href="#" id="resend-link" class="disabled" onclick="this.closest('form').submit(); return false;">
                        Resend Code
                    </a>
                    <span id="countdown" class="ml-1"></span>
                </form>
            </div>

            <div class="mt-3">
                <a href="{{ route('password.request') }}" class="text-muted" style="font-size: 13px;">
                    <i class="fas fa-arrow-left mr-1"></i> Use a different email
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const digits = document.querySelectorAll('.otp-digit');
    const hiddenInput = document.getElementById('otp-hidden');
    const form = document.getElementById('verify-otp-form');

    function updateHiddenInput() {
        let otp = '';
        digits.forEach(d => otp += d.value);
        hiddenInput.value = otp;
    }

    digits.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            // Only allow digits
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value && index < digits.length - 1) {
                digits[index + 1].focus();
            }
            updateHiddenInput();

            // Auto-submit when all 6 digits entered
            if (index === digits.length - 1 && hiddenInput.value.length === 6) {
                form.submit();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                digits[index - 1].focus();
            }
        });

        // Handle paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            if (pasted.length === 6) {
                digits.forEach((d, i) => d.value = pasted[i] || '');
                digits[5].focus();
                updateHiddenInput();
                setTimeout(() => form.submit(), 200);
            }
        });
    });

    // Countdown timer for resend
    const cooldown = {{ Setting::get('auth.otp_cooldown_seconds', 60) }};
    let remaining = cooldown;
    const countdownEl = document.getElementById('countdown');
    const resendLink = document.getElementById('resend-link');

    function updateCountdown() {
        if (remaining > 0) {
            countdownEl.textContent = '(' + remaining + 's)';
            remaining--;
            setTimeout(updateCountdown, 1000);
        } else {
            countdownEl.textContent = '';
            resendLink.classList.remove('disabled');
        }
    }
    updateCountdown();
});
</script>
@endpush
@endsection
