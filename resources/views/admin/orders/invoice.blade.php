<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Helvetica Neue', sans-serif;
            font-size: 13px;
            line-height: 1.6;
            color: #2c3e50;
            background: #f0f4f8;
        }

        /* Print Button Bar */
        .print-bar {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            padding: 15px 30px;
            display: flex;
            gap: 15px;
            align-items: center;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .print-bar button,
        .print-bar a {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .print-bar button {
            background: white;
            color: #0d6efd;
        }
        .print-bar button:hover {
            background: #f0f4f8;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        .print-bar a {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .print-bar a:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Invoice Container */
        .invoice-wrapper {
            padding: 30px;
        }
        .invoice-container {
            max-width: 950px;
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
            width: 55%;
            vertical-align: top;
        }
        .invoice-header-right {
            display: table-cell;
            width: 45%;
            vertical-align: top;
            text-align: right;
        }
        .company-name {
            font-size: 26px;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }
        .invoice-title {
            font-size: 36px;
            font-weight: 300;
            color: #2c3e50;
            margin-top: 15px;
            letter-spacing: 3px;
        }
        .company-info {
            margin-top: 12px;
            font-size: 12px;
            color: #7f8c8d;
            line-height: 1.7;
        }

        /* Invoice Details */
        .invoice-meta {
            display: table;
            width: 100%;
            font-size: 13px;
            color: #2c3e50;
        }
        .invoice-meta-row {
            display: table-row;
        }
        .invoice-meta-label {
            display: table-cell;
            font-weight: 600;
            color: #7f8c8d;
            width: 45%;
            padding-bottom: 10px;
        }
        .invoice-meta-value {
            display: table-cell;
            padding-bottom: 10px;
            text-align: right;
        }
        .invoice-meta-value strong {
            font-weight: 700;
            color: #2c3e50;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-paid {
            background: rgba(39, 174, 96, 0.1);
            color: #27ae60;
        }
        .status-pending {
            background: rgba(241, 196, 15, 0.1);
            color: #f39c12;
        }
        .status-failed {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        /* Address Section */
        .addresses-section {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border-radius: 6px;
            overflow: hidden;
            background: #f8fafc;
        }
        .address-block {
            display: table-cell;
            width: 50%;
            padding: 28px;
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
            color: #0d6efd;
            margin-bottom: 14px;
            letter-spacing: 1.2px;
        }
        .address-content {
            font-size: 13px;
            color: #2c3e50;
            line-height: 1.8;
        }
        .address-name {
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        /* Items Table */
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.items-table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #ecf0f1 100%);
        }
        table.items-table th {
            padding: 16px 14px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            color: #7f8c8d;
            letter-spacing: 0.5px;
            border: none;
        }
        table.items-table th:last-child {
            text-align: right;
        }
        table.items-table td {
            padding: 16px 14px;
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
        .product-name {
            font-weight: 600;
            color: #2c3e50;
            display: block;
            margin-bottom: 4px;
        }
        .product-variant {
            font-size: 11px;
            color: #95a5a6;
        }
        .product-sku {
            font-size: 11px;
            color: #95a5a6;
            font-family: 'Courier New', monospace;
        }
        .numeric {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }

        /* Totals Section */
        .totals-wrapper {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }
        .totals-spacer {
            display: table-cell;
            width: 60%;
        }
        .totals-box {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        table.totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        table.totals-table td {
            padding: 12px 16px;
            border: none;
        }
        table.totals-table .label {
            text-align: left;
            font-weight: 500;
            color: #7f8c8d;
            width: 55%;
        }
        table.totals-table .value {
            text-align: right;
            color: #2c3e50;
            font-weight: 500;
            font-family: 'Courier New', monospace;
        }
        table.totals-table tr.subtotal {
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 8px;
        }
        table.totals-table tr.discount .value {
            color: #27ae60;
            font-weight: 600;
        }
        table.totals-table tr.grand-total {
            border-top: 2px solid #2c3e50;
            margin-top: 8px;
            padding-top: 14px;
        }
        table.totals-table tr.grand-total .label {
            font-size: 14px;
            font-weight: 700;
            color: #2c3e50;
        }
        table.totals-table tr.grand-total .value {
            font-size: 16px;
            font-weight: 700;
            color: #0d6efd;
        }

        /* Notes Section */
        .notes-section {
            background: linear-gradient(135deg, #f8fafc 0%, #ecf0f1 100%);
            border-left: 4px solid #0d6efd;
            padding: 22px;
            margin-bottom: 30px;
            border-radius: 6px;
        }
        .notes-title {
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            color: #7f8c8d;
            margin-bottom: 10px;
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
            padding-top: 28px;
            margin-top: 40px;
            font-size: 12px;
            color: #95a5a6;
            line-height: 1.8;
        }
        .footer-thank-you {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 13px;
        }
        .footer-contact {
            font-size: 11px;
            color: #95a5a6;
        }

        /* Print Styles */
        @media print {
            body { background: white; }
            .print-bar { display: none; }
            .invoice-wrapper { padding: 0; }
            .invoice-container { box-shadow: none; border-radius: 0; }
            a { color: inherit; text-decoration: none; }
        }
    </style>
</head>
<body>
    <!-- Print Control Bar -->
    <div class="print-bar">
        <button onclick="window.print()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Print Invoice
        </button>
        <a href="{{ route('admin.orders.show', $order) }}">← Back to Order</a>
    </div>

    <!-- Invoice -->
    <div class="invoice-wrapper">
        <div class="invoice-container">
            <!-- Header -->
            <div class="invoice-header">
                <div class="invoice-header-left">
                    <div class="company-name">{{ \App\Models\Setting::get('general.site_name', 'Porto Shop') }}</div>
                    <div class="invoice-title">INVOICE</div>
                    <div class="company-info">
                        {{ \App\Models\Setting::get('contact.address', '') }}<br>
                        {{ \App\Models\Setting::get('contact.email', '') }}<br>
                        {{ \App\Models\Setting::get('contact.phone', '') }}
                    </div>
                </div>
                <div class="invoice-header-right">
                    <table class="invoice-meta">
                        <tr class="invoice-meta-row">
                            <td class="invoice-meta-label">Invoice #</td>
                            <td class="invoice-meta-value"><strong>{{ $order->order_number }}</strong></td>
                        </tr>
                        <tr class="invoice-meta-row">
                            <td class="invoice-meta-label">Date</td>
                            <td class="invoice-meta-value">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr class="invoice-meta-row">
                            <td class="invoice-meta-label">Status</td>
                            <td class="invoice-meta-value">
                                <span class="status-badge status-{{ strtolower($order->payment_status) }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Addresses -->
            <div class="addresses-section">
                <div class="address-block">
                    <div class="address-title">Bill To</div>
                    <div class="address-content">
                        <div class="address-name">{{ $order->billing_first_name }} {{ $order->billing_last_name }}</div>
                        {{ $order->billing_address_line1 }}<br>
                        @if($order->billing_address_line2)
                            {{ $order->billing_address_line2 }}<br>
                        @endif
                        {{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}<br>
                        <strong>{{ $order->billing_phone }}</strong>
                    </div>
                </div>
                <div class="address-block">
                    <div class="address-title">Ship To</div>
                    <div class="address-content">
                        @if($order->shipping_address_line1)
                            <div class="address-name">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</div>
                            {{ $order->shipping_address_line1 }}<br>
                            @if($order->shipping_address_line2)
                                {{ $order->shipping_address_line2 }}<br>
                            @endif
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}
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
                        <th>Product</th>
                        <th>SKU</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Unit Price</th>
                        <th style="text-align: right; width: 100px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div class="product-name">{{ $item->product_name }}</div>
                            @if($item->variant_name)
                                <div class="product-variant">{{ $item->variant_name }}</div>
                            @endif
                        </td>
                        <td><span class="product-sku">{{ $item->product_sku }}</span></td>
                        <td class="numeric" style="text-align: center;">{{ $item->quantity }}</td>
                        <td class="numeric">{{ \App\Helpers\CurrencyHelper::format($item->unit_price) }}</td>
                        <td class="numeric" style="width: 100px;">{{ \App\Helpers\CurrencyHelper::format($item->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <div class="totals-wrapper">
                <div class="totals-spacer"></div>
                <div class="totals-box">
                    <table class="totals-table">
                        <tr class="subtotal">
                            <td class="label">Subtotal</td>
                            <td class="value">{{ \App\Helpers\CurrencyHelper::format($order->subtotal) }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                        <tr class="discount">
                            <td class="label">Discount</td>
                            <td class="value">-{{ \App\Helpers\CurrencyHelper::format($order->discount_amount) }}</td>
                        </tr>
                        @endif
                        @if($order->shipping_amount > 0)
                        <tr>
                            <td class="label">Shipping</td>
                            <td class="value">{{ \App\Helpers\CurrencyHelper::format($order->shipping_amount) }}</td>
                        </tr>
                        @endif
                        @if($order->tax_amount > 0)
                        <tr>
                            <td class="label">Tax</td>
                            <td class="value">{{ \App\Helpers\CurrencyHelper::format($order->tax_amount) }}</td>
                        </tr>
                        @endif
                        <tr class="grand-total">
                            <td class="label">Total</td>
                            <td class="value">{{ \App\Helpers\CurrencyHelper::format($order->total) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Notes -->
            @if($order->admin_notes)
            <div class="notes-section">
                <div class="notes-title">Notes</div>
                <div class="notes-content">{{ $order->admin_notes }}</div>
            </div>
            @endif

            <!-- Footer -->
            <div class="invoice-footer">
                <div class="footer-thank-you">Thank you for your business!</div>
                <div class="footer-contact">
                    {{ \App\Models\Setting::get('general.site_name', 'Porto Shop') }} |
                    {{ \App\Models\Setting::get('contact.address', '') }} |
                    {{ \App\Models\Setting::get('contact.email', '') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
