<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Helvetica Neue', sans-serif;
            font-size: 13px;
            line-height: 1.6;
            color: #2c3e50;
            background: #f8fafc;
        }
        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
        }

        /* Header Section */
        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 50px;
            padding-bottom: 30px;
            border-bottom: 2px solid #ecf0f1;
        }
        .invoice-header-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        .invoice-header-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }
        .company-name {
            font-size: 28px;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: 300;
            color: #2c3e50;
            margin-top: 20px;
            letter-spacing: 2px;
        }
        .company-info {
            margin-top: 15px;
            font-size: 12px;
            color: #7f8c8d;
            line-height: 1.8;
        }
        .invoice-details {
            font-size: 13px;
            color: #2c3e50;
        }
        .invoice-details-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        .details-label {
            display: table-cell;
            font-weight: 600;
            color: #7f8c8d;
            width: 40%;
        }
        .details-value {
            display: table-cell;
            color: #2c3e50;
        }

        /* Address Section */
        .addresses-section {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border: 1px solid #ecf0f1;
            border-radius: 6px;
            overflow: hidden;
        }
        .address-block {
            display: table-cell;
            width: 50%;
            padding: 25px;
            border-right: 1px solid #ecf0f1;
            vertical-align: top;
        }
        .address-block:last-child {
            border-right: none;
        }
        .address-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #7f8c8d;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }
        .address-content {
            font-size: 13px;
            color: #2c3e50;
            line-height: 1.8;
        }
        .address-name {
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* Items Table */
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.items-table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #ecf0f1 100%);
            border-bottom: 2px solid #d0d0d0;
        }
        table.items-table th {
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            color: #7f8c8d;
            letter-spacing: 0.5px;
        }
        table.items-table th:last-child {
            text-align: right;
        }
        table.items-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 13px;
            color: #2c3e50;
        }
        table.items-table tbody tr:hover {
            background: #f8fafc;
        }
        table.items-table tbody tr:last-child td {
            border-bottom: 2px solid #d0d0d0;
            padding-bottom: 18px;
        }
        .item-description {
            font-weight: 600;
            color: #2c3e50;
        }
        .item-variant {
            font-size: 11px;
            color: #95a5a6;
            margin-top: 3px;
        }
        .numeric {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }

        /* Totals Section */
        .totals-section {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }
        .totals-spacer {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        .totals-table {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        table.totals {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        table.totals td {
            padding: 10px 15px;
            border: none;
        }
        table.totals .totals-label {
            text-align: left;
            font-weight: 500;
            color: #7f8c8d;
            width: 60%;
        }
        table.totals .totals-value {
            text-align: right;
            color: #2c3e50;
            font-weight: 500;
            font-family: 'Courier New', monospace;
        }
        table.totals .subtotal {
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 12px;
        }
        table.totals .subtotal .totals-value {
            border-bottom: none;
        }
        table.totals .discount-row .totals-value {
            color: #27ae60;
            font-weight: 600;
        }
        table.totals .grand-total {
            border-top: 2px solid #2c3e50;
            padding-top: 15px;
            margin-top: 10px;
        }
        table.totals .grand-total .totals-label {
            font-size: 14px;
            font-weight: 700;
            color: #2c3e50;
        }
        table.totals .grand-total .totals-value {
            font-size: 16px;
            font-weight: 700;
            color: #0d6efd;
        }

        /* Notes Section */
        .notes-section {
            background: linear-gradient(135deg, #f8fafc 0%, #ecf0f1 100%);
            border-left: 4px solid #0d6efd;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 6px;
        }
        .notes-title {
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            color: #7f8c8d;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .notes-content {
            font-size: 13px;
            color: #2c3e50;
            line-height: 1.7;
        }

        /* Footer */
        .invoice-footer {
            text-align: center;
            border-top: 2px solid #ecf0f1;
            padding-top: 25px;
            margin-top: 40px;
            font-size: 12px;
            color: #95a5a6;
            line-height: 1.8;
        }
        .footer-message {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        .footer-contact {
            font-size: 11px;
            color: #95a5a6;
        }

        /* Print Styles */
        @media print {
            body { background: none; }
            .invoice-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="invoice-header-left">
                <div class="company-name">{{ $settings['site_name'] ?? 'Porto Shop' }}</div>
                <div class="invoice-title">INVOICE</div>
                <div class="company-info">
                    {{ $settings['address'] ?? '' }}<br>
                    Phone: {{ $settings['phone'] ?? '' }}<br>
                    Email: {{ $settings['email'] ?? '' }}
                </div>
            </div>
            <div class="invoice-header-right">
                <div class="invoice-details">
                    <div class="invoice-details-row">
                        <div class="details-label">Invoice #</div>
                        <div class="details-value"><strong>{{ $order->order_number }}</strong></div>
                    </div>
                    <div class="invoice-details-row">
                        <div class="details-label">Date</div>
                        <div class="details-value">{{ $order->created_at->format('F d, Y') }}</div>
                    </div>
                    <div class="invoice-details-row">
                        <div class="details-label">Payment Method</div>
                        <div class="details-value">{{ strtoupper($order->payment_method) }}</div>
                    </div>
                    <div class="invoice-details-row">
                        <div class="details-label">Order Status</div>
                        <div class="details-value">{{ ucfirst($order->status) }}</div>
                    </div>
                    <div class="invoice-details-row">
                        <div class="details-label">Payment Status</div>
                        <div class="details-value" style="color: {{ $order->payment_status === 'paid' ? '#27ae60' : '#e74c3c' }};">
                            <strong>{{ ucfirst($order->payment_status) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="addresses-section">
            <div class="address-block">
                <div class="address-title">Bill To</div>
                <div class="address-content">
                    <div class="address-name">{{ $order->billing_first_name }} {{ $order->billing_last_name }}</div>
                    {{ $order->billing_address_line1 }}
                    @if($order->billing_address_line2)
                        <br>{{ $order->billing_address_line2 }}
                    @endif
                    <br>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}
                    <br>Phone: {{ $order->billing_phone }}
                    <br>Email: {{ $order->user->email ?? 'N/A' }}
                </div>
            </div>
            <div class="address-block">
                <div class="address-title">Ship To</div>
                <div class="address-content">
                    @if($order->shipping_address_line1)
                        <div class="address-name">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</div>
                        {{ $order->shipping_address_line1 }}
                        @if($order->shipping_address_line2)
                            <br>{{ $order->shipping_address_line2 }}
                        @endif
                        <br>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}
                    @else
                        <span style="color: #95a5a6;">Same as billing address</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Item</th>
                    <th style="text-align: center; width: 80px;">Qty</th>
                    <th style="text-align: right; width: 100px;">Unit Price</th>
                    <th style="text-align: right; width: 100px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="item-description">{{ $item->product_name }}</div>
                        @if($item->variant_name)
                            <div class="item-variant">{{ $item->variant_name }}</div>
                        @endif
                    </td>
                    <td class="numeric" style="width: 80px;">{{ $item->quantity }}</td>
                    <td class="numeric" style="width: 100px;">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="numeric" style="width: 100px;">${{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-spacer"></div>
            <div class="totals-table">
                <table class="totals">
                    <tr class="subtotal">
                        <td class="totals-label">Subtotal</td>
                        <td class="totals-value">${{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr class="discount-row">
                        <td class="totals-label">Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</td>
                        <td class="totals-value">-${{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->shipping_amount > 0)
                    <tr>
                        <td class="totals-label">Shipping</td>
                        <td class="totals-value">${{ number_format($order->shipping_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->tax_amount > 0)
                    <tr>
                        <td class="totals-label">Tax</td>
                        <td class="totals-value">${{ number_format($order->tax_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="grand-total">
                        <td class="totals-label">Total Due</td>
                        <td class="totals-value">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Notes -->
        @if($order->customer_notes)
        <div class="notes-section">
            <div class="notes-title">Customer Notes</div>
            <div class="notes-content">{{ $order->customer_notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-message">Thank you for your business!</div>
            <div class="footer-contact">
                {{ $settings['site_name'] ?? 'Porto Shop' }} | {{ $settings['address'] ?? '' }}<br>
                {{ $settings['email'] ?? '' }} | {{ $settings['phone'] ?? '' }}
            </div>
        </div>
    </div>
</body>
</html>
