@extends('layouts.app')

@section('meta_title', 'Checkout - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
<main class="main">
    <div class="container checkout-container">
        {{-- Modern Progress Bar --}}
        <div class="checkout-progress-wrapper">
            <ul class="checkout-progress-bar d-flex justify-content-center flex-wrap">
                <li>
                    <a href="{{ url('/cart') }}"><i class="fas fa-shopping-cart mr-1"></i> Shopping Cart</a>
                </li>
                <li class="active">
                    <a href="{{ url('/checkout') }}"><i class="fas fa-credit-card mr-1"></i> Checkout</a>
                </li>
                <li class="disabled">
                    <a href="#"><i class="fas fa-check-circle mr-1"></i> Order Complete</a>
                </li>
            </ul>
        </div>

        @livewire('checkout-page')
    </div>
</main>
@endsection

@push('styles')
<style>
    /* ═══ Modern Checkout Styles ═══ */
    .checkout-container {
        padding-bottom: 60px;
    }

    .checkout-progress-wrapper {
        margin-top: 20px;
        margin-bottom: 35px;
    }

    .checkout-progress-bar {
        font-size: 24px;
        font-weight: 700;
        line-height: 1.2;
        margin: 0;
        list-style: none;
        padding: 0;
        color: #dbdbdb;
    }
    
    .checkout-progress-bar li {
        position: relative;
        padding: 0 25px;
        transition: color .3s ease;
    }
    
    .checkout-progress-bar li::after {
        color: inherit;
        content: '\203A';
        font-size: 32px;
        line-height: 1;
        position: absolute;
        right: -8px;
        top: 50%;
        margin-top: -16px;
    }
    
    .checkout-progress-bar li:last-child::after {
        display: none;
    }
    
    .checkout-progress-bar li a {
        color: inherit;
        text-decoration: none;
    }
    
    .checkout-progress-bar li.active, 
    .checkout-progress-bar li:hover {
        color: #222;
    }
    
    .checkout-progress-bar li.disabled {
        pointer-events: none;
        opacity: 0.8;
    }

    .checkout-progress-bar li.active a i {
        color: #667eea;
    }

    /* ── Billing Section ── */
    .checkout-billing-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        padding: 30px;
        margin-bottom: 24px;
        border: 1px solid #f0f0f0;
        transition: box-shadow 0.3s ease;
    }
    .checkout-billing-card:hover {
        box-shadow: 0 4px 30px rgba(0,0,0,0.09);
    }
    .checkout-billing-card .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 22px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f5f6f8;
    }
    .checkout-billing-card .section-header .section-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
        font-size: 18px;
        flex-shrink: 0;
    }
    .checkout-billing-card .section-header .section-icon.billing {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }
    .checkout-billing-card .section-header .section-icon.shipping-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: #fff;
    }
    .checkout-billing-card .section-header .section-icon.notes-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: #fff;
    }
    .checkout-billing-card .section-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1a1a2e;
        letter-spacing: 0.3px;
    }
    .checkout-billing-card .section-header p {
        margin: 2px 0 0;
        font-size: 12.5px;
        color: #999;
    }

    /* ── Form Enhancements ── */
    .checkout-billing-card .form-control {
        border-radius: 8px;
        border: 1.5px solid #e8e8ef;
        padding: 10px 14px;
        font-size: 14px;
        transition: border-color 0.25s, box-shadow 0.25s;
        background: #fafbfc;
    }
    .checkout-billing-card .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.12);
        background: #fff;
    }
    .checkout-billing-card label {
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 5px;
    }
    .checkout-billing-card label abbr.required {
        color: #f5576c;
        text-decoration: none;
    }
    .checkout-billing-card textarea.form-control {
        min-height: 100px;
    }

    /* ── Saved Address Cards ── */
    .saved-address-card {
        border-radius: 10px;
        border: 2px solid #eee;
        padding: 14px;
        cursor: pointer;
        transition: all 0.25s ease;
        background: #fafbfc;
    }
    .saved-address-card:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102,126,234,0.15);
    }
    .saved-address-card.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
    }
    .saved-address-card .addr-name {
        font-weight: 700;
        color: #1a1a2e;
        font-size: 14px;
    }
    .saved-address-card .addr-detail {
        font-size: 12.5px;
        color: #777;
        line-height: 1.5;
    }

    /* ── Order Summary Card ── */
    .order-summary-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        padding: 28px;
        border: 1px solid #f0f0f0;
        position: sticky;
        top: 80px;
    }
    .order-summary-card h3.order-title {
        font-size: 16px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #1a1a2e;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f5f6f8;
    }
    .order-summary-card .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .order-summary-card .order-item:last-child {
        border-bottom: none;
    }
    .order-summary-card .order-item .item-name {
        font-size: 13.5px;
        color: #333;
        font-weight: 500;
        flex: 1;
    }
    .order-summary-card .order-item .item-name .item-qty {
        display: inline-block;
        background: #667eea;
        color: #fff;
        border-radius: 4px;
        font-size: 11px;
        padding: 1px 6px;
        margin-left: 4px;
        font-weight: 600;
    }
    .order-summary-card .order-item .item-price {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 13.5px;
        white-space: nowrap;
        margin-left: 12px;
    }

    /* ── Totals ── */
    .order-totals-table {
        width: 100%;
        margin-top: 14px;
        border-top: 2px solid #f0f0f0;
    }
    .order-totals-table td {
        padding: 10px 0;
        font-size: 14px;
    }
    .order-totals-table .label-col {
        color: #666;
        font-weight: 500;
    }
    .order-totals-table .value-col {
        text-align: right;
        font-weight: 600;
        color: #333;
    }
    .order-totals-table .total-row td {
        padding-top: 14px;
        border-top: 2px solid #1a1a2e;
        font-size: 17px;
        font-weight: 800;
        color: #1a1a2e;
    }

    /* ── Shipping Methods ── */
    .shipping-methods-section {
        background: #f8f9fb;
        border-radius: 10px;
        padding: 16px;
        margin-top: 14px;
    }
    .shipping-methods-section h5 {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #888;
        margin-bottom: 10px;
    }
    .shipping-method-option {
        display: flex;
        align-items: flex-start;
        padding: 8px 12px;
        border-radius: 8px;
        margin-bottom: 6px;
        transition: background 0.2s;
        cursor: pointer;
    }
    .shipping-method-option:hover {
        background: rgba(102,126,234,0.06);
    }
    .shipping-method-option:last-child {
        margin-bottom: 0;
    }
    .shipping-method-option .method-name {
        font-weight: 600;
        font-size: 13.5px;
        color: #333;
    }
    .shipping-method-option .method-price {
        font-weight: 700;
        color: #667eea;
        margin-left: 6px;
    }
    .shipping-method-option .method-days {
        font-size: 11.5px;
        color: #999;
    }

    /* ── Payment Methods ── */
    .payment-methods-section {
        margin-top: 20px;
    }
    .payment-methods-section h4 {
        font-size: 14px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #1a1a2e;
        margin-bottom: 14px;
    }
    .payment-option {
        border: 1.5px solid #eee;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 8px;
        transition: all 0.25s;
        cursor: pointer;
    }
    .payment-option:hover,
    .payment-option.selected {
        border-color: #667eea;
        background: rgba(102,126,234,0.03);
    }
    .payment-option label {
        margin-bottom: 0;
        cursor: pointer;
        width: 100%;
    }
    .payment-option .payment-label {
        font-weight: 700;
        font-size: 14px;
        color: #1a1a2e;
    }
    .payment-option .payment-desc {
        font-size: 12px;
        color: #999;
        margin-top: 2px;
    }

    /* ── Place Order Button ── */
    .btn-place-order-modern {
        display: block;
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
    }
    .btn-place-order-modern:hover:not(:disabled) {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102,126,234,0.35);
        color: #fff;
    }
    .btn-place-order-modern:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .btn-place-order-modern i {
        margin-left: 8px;
    }

    /* ── Secure Badge ── */
    .secure-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 14px;
        font-size: 12px;
        color: #aaa;
    }
    .secure-badge i {
        color: #27ae60;
    }

    /* ── Animations ── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .checkout-billing-card,
    .order-summary-card {
        animation: fadeInUp 0.5s ease forwards;
    }
    .order-summary-card {
        animation-delay: 0.1s;
    }
</style>
@endpush
