<!DOCTYPE html>
<html><head>
<meta charset="UTF-8">
<title>Invoice #{{ $order->order_number }}</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 13px; color: #333; margin: 30px; }
    .header { display: flex; justify-content: space-between; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
    .header h1 { margin: 0; font-size: 28px; }
    .header .info { text-align: right; }
    .addresses { display: flex; gap: 40px; margin-bottom: 25px; }
    .addresses .addr { flex: 1; }
    .addresses h3 { font-size: 14px; margin: 0 0 8px; text-transform: uppercase; color: #666; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th { background: #f5f5f5; text-align: left; padding: 8px 10px; border-bottom: 2px solid #ddd; font-size: 11px; text-transform: uppercase; }
    td { padding: 8px 10px; border-bottom: 1px solid #eee; }
    .totals { width: 300px; margin-left: auto; }
    .totals td { padding: 5px 10px; }
    .totals .grand td { font-size: 16px; font-weight: bold; border-top: 2px solid #333; padding-top: 10px; }
    .footer { margin-top: 40px; text-align: center; color: #999; font-size: 11px; }
    @@media print { body { margin: 0; } .no-print { display: none; } }
</style>
</head><body>
<div class="no-print" style="margin-bottom:15px;">
    <button onclick="window.print()" style="padding:8px 20px;background:#0d6efd;color:#fff;border:none;border-radius:4px;cursor:pointer;">Print Invoice</button>
    <a href="{{ route('admin.orders.show', $order) }}" style="margin-left:10px;">← Back to Order</a>
</div>

<div class="header">
    <div><h1>INVOICE</h1><p style="color:#666;margin:5px 0;">{{ \App\Models\Setting::get('general.site_name', 'Porto Shop') }}</p></div>
    <div class="info">
        <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</p>
    </div>
</div>

<div class="addresses">
    <div class="addr"><h3>Bill To</h3>
        <p><strong>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</strong></p>
        <p>{{ $order->billing_address_line1 }}</p>
        <p>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}</p>
        <p>{{ $order->billing_phone }}</p>
    </div>
    <div class="addr"><h3>Ship To</h3>
        <p><strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong></p>
        <p>{{ $order->shipping_address_line1 }}</p>
        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
    </div>
</div>

<table>
    <thead><tr><th>Product</th><th>SKU</th><th>Price</th><th>Qty</th><th style="text-align:right;">Total</th></tr></thead>
    <tbody>
    @foreach($order->items as $item)
    <tr>
        <td>{{ $item->product_name }}@if($item->variant_name) <small>({{ $item->variant_name }})</small>@endif</td>
        <td>{{ $item->product_sku }}</td>
        <td>${{ number_format($item->unit_price, 2) }}</td>
        <td>{{ $item->quantity }}</td>
        <td style="text-align:right;">${{ number_format($item->total, 2) }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<table class="totals">
    <tr><td>Subtotal:</td><td style="text-align:right;">${{ number_format($order->subtotal, 2) }}</td></tr>
    @if($order->discount_amount > 0)<tr><td>Discount:</td><td style="text-align:right;color:green;">-${{ number_format($order->discount_amount, 2) }}</td></tr>@endif
    @if($order->shipping_amount > 0)<tr><td>Shipping:</td><td style="text-align:right;">${{ number_format($order->shipping_amount, 2) }}</td></tr>@endif
    @if($order->tax_amount > 0)<tr><td>Tax:</td><td style="text-align:right;">${{ number_format($order->tax_amount, 2) }}</td></tr>@endif
    <tr class="grand"><td>Total:</td><td style="text-align:right;">${{ number_format($order->total, 2) }}</td></tr>
</table>

@if($order->admin_notes)<div style="margin-top:20px;"><h3 style="font-size:14px;color:#666;">Notes</h3><p>{{ $order->admin_notes }}</p></div>@endif

<div class="footer"><p>Thank you for your business!</p><p>{{ \App\Models\Setting::get('contact.address', '') }} | {{ \App\Models\Setting::get('contact.email', '') }} | {{ \App\Models\Setting::get('contact.phone', '') }}</p></div>
</body></html>
