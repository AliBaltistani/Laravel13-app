@extends('emails.layouts.master')
@section('content')
<p>Thank you for reaching out to us. We have received your message and will get back to you as soon as possible.</p>
<p style="font-size: 14px; color: #888;">Here's a copy of what you sent us:</p>
<div style="font-size: 14px; background: #f8f8f8; padding: 16px; border-radius: 4px; margin: 12px 0;">
    <p style="margin: 0 0 8px 0;"><strong>Subject:</strong> {{ $contactMessage->subject }}</p>
    <p style="margin: 0; white-space: pre-wrap;">{{ $contactMessage->message }}</p>
</div>
<p style="font-size: 14px;">Our team typically responds within 24-48 business hours.</p>
@endsection
