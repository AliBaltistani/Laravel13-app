@extends('emails.layouts.master')
@section('content')
<p>You have received a new contact message from your website.</p>
<table cellpadding="8" cellspacing="0" style="margin: 16px 0; width: 100%; background: #f8f8f8; border-radius: 4px;">
    <tr><td style="font-size: 14px; color: #888; width: 100px;">Name:</td><td style="font-size: 14px;"><strong>{{ $contactMessage->name }}</strong></td></tr>
    <tr><td style="font-size: 14px; color: #888;">Email:</td><td style="font-size: 14px;"><a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></td></tr>
    @if($contactMessage->phone)<tr><td style="font-size: 14px; color: #888;">Phone:</td><td style="font-size: 14px;">{{ $contactMessage->phone }}</td></tr>@endif
    <tr><td style="font-size: 14px; color: #888;">Subject:</td><td style="font-size: 14px;">{{ $contactMessage->subject }}</td></tr>
</table>
<p style="font-size: 14px; font-weight: bold;">Message:</p>
<div style="font-size: 14px; background: #fff; border: 1px solid #eee; padding: 16px; border-radius: 4px; white-space: pre-wrap;">{{ $contactMessage->message }}</div>
@endsection
