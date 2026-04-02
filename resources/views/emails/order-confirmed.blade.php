@extends('emails.layouts.master')
@section('content')
<p>Your order <strong>#{{ $order->order_number }}</strong> has been confirmed and is now being processed.</p>

<table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin: 16px 0;">
    <thead>
        <tr style="background-color: #f8f8f8;">
            <th align="left" style="border-bottom: 2px solid #ddd; font-size: 13px;">Product</th>
            <th align="center" style="border-bottom: 2px solid #ddd; font-size: 13px;">Qty</th>
            <th align="right" style="border-bottom: 2px solid #ddd; font-size: 13px;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td style="border-bottom: 1px solid #eee; font-size: 14px;">{{ $item->product_name }}</td>
            <td align="center" style="border-bottom: 1px solid #eee; font-size: 14px;">{{ $item->quantity }}</td>
            <td align="right" style="border-bottom: 1px solid #eee; font-size: 14px;">${{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr><td colspan="2" style="font-size: 14px;"><strong>Subtotal</strong></td><td align="right" style="font-size: 14px;">${{ number_format($order->subtotal, 2) }}</td></tr>
        @if($order->discount_amount > 0)
        <tr><td colspan="2" style="font-size: 14px; color: #28a745;">Discount</td><td align="right" style="font-size: 14px; color: #28a745;">-${{ number_format($order->discount_amount, 2) }}</td></tr>
        @endif
        <tr><td colspan="2" style="font-size: 14px;">Shipping</td><td align="right" style="font-size: 14px;">${{ number_format($order->shipping_amount, 2) }}</td></tr>
        <tr><td colspan="2" style="font-size: 16px; font-weight: bold; border-top: 2px solid #ddd; padding-top: 8px;">Total</td><td align="right" style="font-size: 16px; font-weight: bold; border-top: 2px solid #ddd; padding-top: 8px;">${{ number_format($order->total, 2) }}</td></tr>
    </tfoot>
</table>

<p style="font-size: 14px;"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
<p style="font-size: 14px;"><strong>Shipping To:</strong> {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}, {{ $order->shipping_address_line1 }}, {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>

<p style="font-size: 13px; color: #888; margin-top: 16px;">We will send you tracking information once your order ships.</p>
@endsection
