@extends('emails.layouts.master')

@section('content')
<h2 style="color: #1e2a3a; font-size: 22px; font-weight: 700; margin: 0 0 15px;">Password Reset Request</h2>
<p style="color: #495057; font-size: 15px; line-height: 1.6; margin: 0 0 25px;">
    You have requested to reset your password. Use the following One-Time Password (OTP) to proceed:
</p>

{{-- OTP Code Display --}}
<div style="background: linear-gradient(135deg, #0d6efd 0%, #0056d2 100%); border-radius: 12px; padding: 30px; text-align: center; margin: 0 0 25px;">
    <p style="color: rgba(255,255,255,0.8); font-size: 12px; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 10px;">Your Verification Code</p>
    <p style="color: #ffffff; font-size: 40px; font-weight: 800; letter-spacing: 12px; margin: 0; font-family: 'Courier New', monospace;">{{ $otp }}</p>
</div>

<div style="background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px; padding: 12px 16px; margin: 0 0 25px;">
    <p style="color: #856404; font-size: 13px; margin: 0;">
        <strong>⏱ This code expires in {{ $expiryMinutes }} minutes.</strong> Do not share this code with anyone.
    </p>
</div>

<p style="color: #6c757d; font-size: 13px; line-height: 1.5; margin: 0;">
    If you did not request a password reset, please ignore this email. Your account remains secure.
</p>
@endsection
