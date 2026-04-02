@extends('emails.layouts.master')
@section('content')
<p>Great news! Your order <strong>#{{ $order->order_number }}</strong> has been shipped.</p>
@if($trackingNumber)
<table cellpadding="8" cellspacing="0" style="margin: 16px 0; background: #f8f8f8; border-radius: 4px; width: 100%;">
    <tr><td style="font-size: 14px; color: #888;">Carrier:</td><td style="font-size: 14px;"><strong>{{ $carrier ?? 'Standard Shipping' }}</strong></td></tr>
    <tr><td style="font-size: 14px; color: #888;">Tracking Number:</td><td style="font-size: 14px;"><strong>{{ $trackingNumber }}</strong></td></tr>
</table>
@endif
<p style="font-size: 14px;">You can track your shipment using the button above or the tracking number provided.</p>
@endsection
