<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); }
        .header { width: 100%; margin-bottom: 20px; }
        .header td { padding-bottom: 20px; }
        .header .title { font-size: 32px; font-weight: bold; color: #333; }
        .header .company-details { text-align: right; }
        .invoice-details { width: 100%; margin-bottom: 30px; }
        .invoice-details td { padding: 5px; }
        .invoice-details .info-block { width: 50%; vertical-align: top; }
        table.items { width: 100%; text-align: left; border-collapse: collapse; margin-bottom: 20px; }
        table.items th { background: #eee; padding: 10px; border-bottom: 1px solid #ddd; }
        table.items td { padding: 10px; border-bottom: 1px solid #eee; }
        table.items td.right, table.items th.right { text-align: right; }
        table.items td.center, table.items th.center { text-align: center; }
        .totals { width: 100%; border-collapse: collapse; }
        .totals td { padding: 5px 10px; text-align: right; }
        .totals .total-label { font-weight: bold; width: 80%; }
        .totals .total-amount { font-weight: bold; font-size: 16px; border-top: 2px solid #333; padding-top: 5px; }
        .footer { text-align: center; color: #777; border-top: 1px solid #aaa; padding-top: 15px; margin-top: 40px; font-size: 12px; }
        .badge { display: inline-block; padding: 3px 8px; font-size: 12px; font-weight: bold; color: #fff; border-radius: 3px; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-danger { background-color: #dc3545; }
        .bg-info { background-color: #17a2b8; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header">
            <tr>
                <td class="title">
                    INVOICE
                </td>
                <td class="company-details">
                    <strong>{{ $settings['site_name'] }}</strong><br>
                    {{ $settings['address'] }}<br>
                    Phone: {{ $settings['phone'] }}<br>
                    Email: {{ $settings['email'] }}
                </td>
            </tr>
        </table>

        <table class="invoice-details">
            <tr>
                <td class="info-block" style="padding-right: 20px;">
                    <strong>Billed To:</strong><br>
                    {{ $order->billing_first_name }} {{ $order->billing_last_name }}<br>
                    {{ $order->billing_address_line1 }} {{ $order->billing_address_line2 }}<br>
                    {{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}<br>
                    Phone: {{ $order->billing_phone }}<br>
                    Email: {{ $order->user->email ?? 'N/A' }}
                </td>
                <td class="info-block">
                    <strong>Invoice #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}<br>
                    <strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}<br>
                    <strong>Order Status:</strong> {{ ucfirst($order->status) }}<br>
                    <strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}
                </td>
            </tr>
        </table>

        @if($order->shipping_address_line1)
        <div style="margin-bottom: 20px; border-top: 1px solid #eee; padding-top: 10px;">
            <strong>Shipping Address:</strong><br>
            {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}<br>
            {{ $order->shipping_address_line1 }} {{ $order->shipping_address_line2 }}<br>
            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}
        </div>
        @endif

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="center">Qty</th>
                    <th class="right">Unit Price</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_name)
                            <br><small style="color: #666;">Variant: {{ $item->variant_name }}</small>
                        @endif
                    </td>
                    <td class="center">{{ $item->quantity }}</td>
                    <td class="right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="right">${{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td class="total-label">Subtotal:</td>
                <td>${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr>
                <td class="total-label">Discount ({{ $order->coupon_code }}):</td>
                <td style="color: red;">-${{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td class="total-label">Shipping ({{ $order->shipping_method_name }}):</td>
                <td>${{ number_format($order->shipping_amount, 2) }}</td>
            </tr>
            @if($order->tax_amount > 0)
            <tr>
                <td class="total-label">Tax:</td>
                <td>${{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td class="total-label">Total:</td>
                <td class="total-amount">${{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        @if($order->customer_notes)
        <div style="margin-top: 30px; padding: 15px; background: #f9f9f9; border-left: 4px solid #ddd;">
            <strong>Customer Notes:</strong><br>
            {{ $order->customer_notes }}
        </div>
        @endif

        <div class="footer">
            Thank you for shopping with {{ $settings['site_name'] }}!<br>
            If you have any questions concerning this invoice, please contact {{ $settings['email'] }} or {{ $settings['phone'] }}.
        </div>
    </div>
</body>
</html>
