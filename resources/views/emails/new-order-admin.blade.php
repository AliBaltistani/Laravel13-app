@extends('emails.layouts.master')
@section('content')
<p>A new order has been placed on your store.</p>
<table cellpadding="8" cellspacing="0" style="margin: 16px 0; width: 100%;">
    <tr><td style="font-size: 14px; color: #888;">Order Number:</td><td style="font-size: 14px;"><strong>{{ $order->order_number }}</strong></td></tr>
    <tr><td style="font-size: 14px; color: #888;">Customer:</td><td style="font-size: 14px;">{{ $order->billing_first_name }} {{ $order->billing_last_name }}</td></tr>
    <tr><td style="font-size: 14px; color: #888;">Payment:</td><td style="font-size: 14px;">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td></tr>
    <tr><td style="font-size: 14px; color: #888;">Total:</td><td style="font-size: 14px; font-weight: bold; color: #28a745;">{{ \App\Helpers\CurrencyHelper::format($order->total) }}</td></tr>
    <tr><td style="font-size: 14px; color: #888;">Items:</td><td style="font-size: 14px;">{{ $order->items->count() }} item(s)</td></tr>
</table>
@foreach($order->items as $item)
<p style="font-size: 13px; margin: 2px 0;">• {{ $item->product_name }} × {{ $item->quantity }} = {{ \App\Helpers\CurrencyHelper::format($item->total) }}</p>
@endforeach
@endsection
