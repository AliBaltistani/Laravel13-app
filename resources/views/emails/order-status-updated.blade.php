@extends('emails.layouts.master')
@section('content')
<p>Your order <strong>#{{ $order->order_number }}</strong> status has been updated.</p>
<table cellpadding="8" cellspacing="0" style="margin: 16px 0;">
    <tr><td style="font-size: 14px; color: #888;">Previous Status:</td><td style="font-size: 14px;"><strong>{{ ucfirst($oldStatus) }}</strong></td></tr>
    <tr><td style="font-size: 14px; color: #888;">New Status:</td><td style="font-size: 14px; color: {{ $order->status === 'delivered' ? '#28a745' : '#08c' }};"><strong>{{ ucfirst($order->status) }}</strong></td></tr>
</table>
@if($comment)
<p style="font-size: 14px; background: #f8f8f8; padding: 12px; border-radius: 4px;">{{ $comment }}</p>
@endif
<p style="font-size: 14px;"><strong>Order Total:</strong> ${{ number_format($order->total, 2) }}</p>
@endsection
