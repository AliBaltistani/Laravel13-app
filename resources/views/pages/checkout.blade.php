@extends('layouts.app')

@section('meta_title', 'Checkout - ' . Setting::get('general.site_name', 'Porto Shop'))

@section('content')
<main class="main">
    <div class="container checkout-container">
        {{-- Modern Step Indicator --}}
        <div class="ck-progress-wrapper">
            <div class="ck-progress-bar">
                <a href="{{ url('/cart') }}" class="ck-progress-step completed">
                    <span class="ck-progress-step__circle">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="ck-progress-step__label">Cart</span>
                </a>
                <div class="ck-progress-line active"></div>
                <div class="ck-progress-step active">
                    <span class="ck-progress-step__circle">
                        <span>2</span>
                    </span>
                    <span class="ck-progress-step__label">Checkout</span>
                </div>
                <div class="ck-progress-line"></div>
                <div class="ck-progress-step">
                    <span class="ck-progress-step__circle">
                        <span>3</span>
                    </span>
                    <span class="ck-progress-step__label">Confirmation</span>
                </div>
            </div>
        </div>

        @livewire('checkout-page')
    </div>
</main>
@endsection

@push('styles')
<style>
    /* ════════════════════════════════════════════════════════════
       DEVOGUE CHECKOUT — Premium Accordion Checkout Redesign
       ════════════════════════════════════════════════════════════ */

    .checkout-container {
        padding-top: 10px;
        padding-bottom: 80px;
        max-width: 1200px;
    }

    /* ── Progress Bar ── */
    .ck-progress-wrapper {
        margin-top: 36px;
        margin-bottom: 36px;
        padding: 0 20px;
    }
    .ck-progress-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        max-width: 480px;
        margin: 0 auto;
    }
    .ck-progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
        flex-shrink: 0;
    }
    .ck-progress-step__circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-sans);
        font-size: 14px;
        font-weight: 700;
        background: var(--ink-100);
        color: var(--ink-300);
        border: 2px solid var(--ink-100);
        transition: all 0.3s ease;
    }
    .ck-progress-step.active .ck-progress-step__circle {
        background: var(--dv-navy);
        border-color: var(--dv-navy);
        color: #fff;
        box-shadow: 0 4px 14px rgba(43, 54, 116, 0.3);
    }
    .ck-progress-step.completed .ck-progress-step__circle {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }
    .ck-progress-step__label {
        font-family: var(--font-sans);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: var(--ink-300);
    }
    .ck-progress-step.active .ck-progress-step__label {
        color: var(--dv-navy);
        font-weight: 700;
    }
    .ck-progress-step.completed .ck-progress-step__label {
        color: #16a34a;
    }
    .ck-progress-line {
        flex: 1;
        height: 3px;
        background: var(--ink-100);
        margin: 0 6px;
        border-radius: 2px;
        margin-bottom: 26px;
        transition: background 0.3s ease;
    }
    .ck-progress-line.active {
        background: linear-gradient(90deg, #16a34a, var(--dv-navy));
    }

    /* ── Layout ── */
    .ck-layout {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 32px;
        align-items: start;
    }

    /* ── Empty State ── */
    .ck-empty-state {
        text-align: center;
        padding: 80px 20px;
    }
    .ck-empty-state__icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--ink-100);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    .ck-empty-state__icon i {
        font-size: 40px;
        color: var(--ink-300);
    }
    .ck-empty-state h3 {
        font-family: var(--font-serif);
        font-size: 26px;
        font-weight: 700;
        color: var(--dv-navy);
        margin-bottom: 10px;
    }
    .ck-empty-state p {
        color: var(--ink-500);
        font-size: 15px;
        line-height: 1.7;
        margin-bottom: 24px;
    }

    /* ── Alert ── */
    .ck-alert {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        border-radius: var(--radius-lg);
        margin-bottom: 24px;
        animation: ck-slideDown 0.3s ease;
    }
    .ck-alert--error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }
    .ck-alert__icon {
        font-size: 20px;
        flex-shrink: 0;
    }
    .ck-alert__body {
        flex: 1;
        font-size: 14px;
    }
    .ck-alert__close {
        background: none;
        border: none;
        color: #991b1b;
        cursor: pointer;
        padding: 4px;
        opacity: 0.5;
        transition: opacity 0.2s;
    }
    .ck-alert__close:hover { opacity: 1; }

    /* ══════════════════════════════════════════════════════
       ACCORDION STEPS
       ══════════════════════════════════════════════════════ */
    .ck-accordion {
        background: var(--surface);
        border: 2px solid var(--ink-100);
        border-radius: var(--radius-lg);
        margin-bottom: 16px;
        overflow: hidden;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .ck-accordion.is-active {
        border-color: var(--dv-navy);
        box-shadow: 0 4px 24px rgba(43, 54, 116, 0.08);
    }
    .ck-accordion.is-completed {
        border-color: #bbf7d0;
    }
    .ck-accordion.is-completed .ck-accordion__header {
        background: #f0fdf4;
    }

    /* Accordion Header */
    .ck-accordion__header {
        display: flex;
        align-items: center;
        gap: 16px;
        width: 100%;
        padding: 20px 24px;
        border: none;
        background: var(--ink-50);
        cursor: pointer;
        transition: background 0.2s ease;
        text-align: left;
    }
    .ck-accordion__header:hover {
        background: var(--ink-100);
    }
    .ck-accordion.is-active .ck-accordion__header {
        background: linear-gradient(135deg, rgba(43,54,116,0.03), rgba(43,54,116,0.06));
    }

    .ck-accordion__step-badge {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-sans);
        font-size: 15px;
        font-weight: 800;
        background: var(--ink-100);
        color: var(--ink-500);
        flex-shrink: 0;
        transition: all 0.3s ease;
    }
    .ck-accordion.is-active .ck-accordion__step-badge {
        background: var(--dv-navy);
        color: #fff;
        box-shadow: 0 4px 14px rgba(43, 54, 116, 0.25);
    }
    .ck-accordion__step-badge.completed {
        background: #16a34a !important;
        color: #fff !important;
    }

    .ck-accordion__title-wrap {
        flex: 1;
    }
    .ck-accordion__title {
        font-family: var(--font-sans);
        font-size: 17px;
        font-weight: 700;
        color: var(--ink-900);
        margin: 0;
        line-height: 1.3;
    }
    .ck-accordion__subtitle {
        font-size: 13px;
        color: var(--ink-500);
        margin: 2px 0 0;
        font-weight: 400;
    }

    .ck-accordion__toggle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ink-300);
        transition: all 0.3s ease;
    }
    .ck-accordion.is-active .ck-accordion__toggle {
        transform: rotate(180deg);
        color: var(--dv-navy);
    }

    /* Accordion Body */
    .ck-accordion__content {
        padding: 24px 28px 28px;
    }

    .ck-accordion__footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid var(--ink-100);
    }

    /* ══════════════════════════════════════════════════════
       FORM ELEMENTS
       ══════════════════════════════════════════════════════ */
    .ck-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .ck-form-row--3 {
        grid-template-columns: 1fr 1fr 1fr;
    }
    .ck-form-group {
        margin-bottom: 18px;
    }
    .ck-label {
        display: block;
        font-family: var(--font-sans);
        font-size: 13px;
        font-weight: 600;
        color: var(--ink-700);
        margin-bottom: 7px;
        letter-spacing: 0.2px;
    }
    .ck-label i {
        margin-right: 4px;
        color: var(--dv-navy);
        font-size: 12px;
    }
    .ck-required {
        color: #ef4444;
        font-weight: 700;
    }
    .ck-optional {
        color: var(--ink-300);
        font-weight: 400;
        font-size: 12px;
    }
    .ck-field-label {
        display: block;
        font-family: var(--font-sans);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--ink-500);
        margin-bottom: 12px;
    }
    .ck-field-label i {
        color: var(--dv-orange);
        margin-right: 4px;
    }

    .ck-input-wrap {
        position: relative;
    }
    .ck-input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink-300);
        font-size: 14px;
        pointer-events: none;
        transition: color 0.2s;
    }
    .ck-input-wrap:focus-within .ck-input-icon {
        color: var(--dv-navy);
    }

    .ck-input {
        display: block;
        width: 100%;
        padding: 12px 14px 12px 42px;
        font-family: var(--font-sans);
        font-size: 14px;
        font-weight: 500;
        color: var(--ink-900);
        background: var(--ink-50);
        border: 1.5px solid var(--ink-100);
        border-radius: var(--radius-md);
        outline: none;
        transition: border-color 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
    }
    .ck-input:focus {
        border-color: var(--dv-navy);
        box-shadow: 0 0 0 4px rgba(43, 54, 116, 0.08);
        background: var(--surface);
    }
    .ck-input.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
    }
    .ck-input::placeholder {
        color: var(--ink-300);
        font-weight: 400;
    }

    .ck-textarea {
        display: block;
        width: 100%;
        padding: 14px 16px;
        font-family: var(--font-sans);
        font-size: 14px;
        font-weight: 500;
        color: var(--ink-900);
        background: var(--ink-50);
        border: 1.5px solid var(--ink-100);
        border-radius: var(--radius-md);
        outline: none;
        min-height: 100px;
        resize: vertical;
        transition: border-color 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
    }
    .ck-textarea:focus {
        border-color: var(--dv-navy);
        box-shadow: 0 0 0 4px rgba(43, 54, 116, 0.08);
        background: var(--surface);
    }

    .ck-error {
        display: block;
        font-size: 12px;
        color: #ef4444;
        margin-top: 5px;
        font-weight: 500;
    }
    .ck-hint {
        display: block;
        font-size: 12px;
        color: var(--ink-500);
        margin-top: 6px;
    }
    .ck-hint i {
        margin-right: 3px;
        color: var(--dv-navy);
    }

    /* ── Custom Radio ── */
    .ck-radio-input { display: none; }
    .ck-radio {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        border: 2px solid var(--ink-300);
        border-radius: 50%;
        flex-shrink: 0;
        transition: all 0.2s ease;
        position: relative;
    }
    .ck-radio.checked,
    .ck-radio-input:checked + .ck-radio {
        border-color: var(--dv-navy);
    }
    .ck-radio.checked::after,
    .ck-radio-input:checked + .ck-radio::after {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--dv-navy);
    }

    /* ── Buttons ── */
    .ck-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        font-family: var(--font-sans);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.25s ease;
        border: 2px solid transparent;
        text-decoration: none !important;
    }
    .ck-btn--primary {
        background: var(--dv-navy);
        color: #fff;
        border-color: var(--dv-navy);
    }
    .ck-btn--primary:hover {
        background: var(--dv-navy-light);
        border-color: var(--dv-navy-light);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(43, 54, 116, 0.25);
    }
    .ck-btn--ghost {
        background: transparent;
        color: var(--ink-500);
        border-color: var(--ink-100);
    }
    .ck-btn--ghost:hover {
        background: var(--ink-100);
        color: var(--ink-700);
    }

    /* ══════════════════════════════════════════════════════
       SAVED ADDRESSES
       ══════════════════════════════════════════════════════ */
    .ck-saved-addresses {
        margin-bottom: 24px;
    }
    .ck-saved-addresses__grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .ck-saved-addr {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px;
        border: 2px solid var(--ink-100);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.25s ease;
        background: var(--surface);
    }
    .ck-saved-addr:hover {
        border-color: var(--dv-navy);
        box-shadow: 0 4px 12px rgba(43, 54, 116, 0.08);
    }
    .ck-saved-addr.is-selected {
        border-color: var(--dv-navy);
        background: rgba(43, 54, 116, 0.02);
    }
    .ck-saved-addr__radio {
        padding-top: 2px;
    }
    .ck-saved-addr__name {
        font-family: var(--font-sans);
        font-size: 14px;
        font-weight: 700;
        color: var(--ink-900);
    }
    .ck-saved-addr__detail {
        font-size: 12.5px;
        color: var(--ink-500);
        line-height: 1.5;
        margin-top: 2px;
    }
    .ck-saved-addr__badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 6px;
        padding: 2px 10px;
        background: linear-gradient(135deg, var(--dv-navy), var(--dv-navy-light));
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        border-radius: var(--radius-pill);
        letter-spacing: 0.5px;
    }
    .ck-saved-addr__badge i {
        font-size: 8px;
    }

    /* ══════════════════════════════════════════════════════
       SHIP TO DIFFERENT ADDRESS
       ══════════════════════════════════════════════════════ */
    .ck-ship-toggle {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .ck-ship-toggle__option {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 18px;
        border: 2px solid var(--ink-100);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.25s ease;
        background: var(--surface);
    }
    .ck-ship-toggle__option:hover {
        border-color: var(--dv-navy);
    }
    .ck-ship-toggle__option.is-active {
        border-color: var(--dv-navy);
        background: rgba(43, 54, 116, 0.02);
    }
    .ck-ship-toggle__option strong {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: var(--ink-900);
        margin-bottom: 2px;
    }
    .ck-ship-toggle__option strong i {
        margin-right: 6px;
        color: var(--dv-navy);
    }
    .ck-ship-toggle__option p {
        font-size: 12.5px;
        color: var(--ink-500);
        margin: 0;
    }

    /* ── Notes Section ── */
    .ck-notes-section {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--ink-100);
    }

    /* ══════════════════════════════════════════════════════
       SHIPPING METHOD CARDS
       ══════════════════════════════════════════════════════ */
    .ck-section-block {}
    .ck-section-label {
        font-family: var(--font-sans);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--ink-500);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ck-section-label i {
        color: var(--dv-navy);
        font-size: 14px;
    }

    .ck-shipping-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        border: 2px solid var(--ink-100);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.25s ease;
        background: var(--surface);
        margin-bottom: 10px;
    }
    .ck-shipping-card:hover {
        border-color: var(--dv-navy);
        background: rgba(43, 54, 116, 0.01);
    }
    .ck-shipping-card.is-selected {
        border-color: var(--dv-navy);
        background: rgba(43, 54, 116, 0.02);
        box-shadow: 0 2px 8px rgba(43, 54, 116, 0.06);
    }
    .ck-shipping-card__info {
        flex: 1;
    }
    .ck-shipping-card__name {
        font-family: var(--font-sans);
        font-size: 14px;
        font-weight: 700;
        color: var(--ink-900);
    }
    .ck-shipping-card__eta {
        font-size: 12px;
        color: var(--ink-500);
        margin-top: 2px;
    }
    .ck-shipping-card__eta i {
        margin-right: 3px;
    }
    .ck-shipping-card__price {
        font-family: var(--font-sans);
        font-size: 15px;
        font-weight: 800;
        color: var(--dv-navy);
        white-space: nowrap;
    }
    .ck-free-badge {
        display: inline-block;
        padding: 3px 12px;
        background: #dcfce7;
        color: #16a34a;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1px;
        border-radius: var(--radius-pill);
    }
    .ck-free-badge-sm {
        color: #16a34a;
        font-size: 12px;
        font-weight: 700;
    }

    /* ══════════════════════════════════════════════════════
       PAYMENT METHOD CARDS
       ══════════════════════════════════════════════════════ */
    .ck-payment-card {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px 20px;
        border: 2px solid var(--ink-100);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.25s ease;
        background: var(--surface);
        margin-bottom: 10px;
    }
    .ck-payment-card:hover {
        border-color: var(--dv-navy);
    }
    .ck-payment-card.is-selected {
        border-color: var(--dv-navy);
        background: rgba(43, 54, 116, 0.02);
        box-shadow: 0 2px 8px rgba(43, 54, 116, 0.06);
    }
    .ck-payment-card__icon {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        background: var(--ink-50);
        color: var(--dv-navy);
        transition: all 0.25s;
    }
    .ck-payment-card.is-selected .ck-payment-card__icon {
        background: var(--dv-navy);
        color: #fff;
    }
    .ck-payment-card__info {
        flex: 1;
    }
    .ck-payment-card__name {
        font-family: var(--font-sans);
        font-size: 14px;
        font-weight: 700;
        color: var(--ink-900);
    }
    .ck-payment-card__desc {
        font-size: 12.5px;
        color: var(--ink-500);
        margin-top: 3px;
        line-height: 1.5;
    }

    .ck-no-payment {
        text-align: center;
        padding: 24px;
        color: var(--ink-500);
    }
    .ck-no-payment i {
        font-size: 28px;
        color: var(--dv-orange);
        margin-bottom: 10px;
    }

    /* ── Stripe ── */
    .ck-stripe-wrapper {
        margin: -4px 0 10px;
        padding: 20px;
        background: var(--ink-50);
        border: 1.5px solid var(--ink-100);
        border-radius: 0 0 var(--radius-md) var(--radius-md);
        margin-left: 34px;
    }
    .ck-stripe-loading {
        text-align: center;
        padding: 10px;
        color: var(--ink-500);
        font-size: 13px;
    }
    .ck-stripe-loading i {
        color: var(--dv-navy);
        margin-right: 6px;
    }
    .ck-stripe-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--ink-500);
        margin-bottom: 10px;
    }
    .ck-stripe-label i {
        margin-right: 4px;
        color: #16a34a;
    }
    .ck-stripe-element {
        padding: 14px 16px;
        border: 1.5px solid var(--ink-100);
        border-radius: var(--radius-md);
        background: var(--surface);
    }

    /* ══════════════════════════════════════════════════════
       ORDER SUMMARY — STICKY SIDEBAR
       ══════════════════════════════════════════════════════ */
    .ck-summary-col {
        position: sticky;
        top: 20px;
        align-self: start;
    }
    .ck-summary {
        background: var(--surface);
        border: 2px solid var(--ink-100);
        border-radius: var(--radius-lg);
        padding: 28px;
        box-shadow: 0 4px 24px rgba(43, 54, 116, 0.06);
    }
    .ck-summary__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid var(--ink-100);
    }
    .ck-summary__header h3 {
        font-family: var(--font-sans);
        font-size: 16px;
        font-weight: 800;
        color: var(--dv-navy);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ck-summary__header h3 i {
        color: var(--dv-orange);
    }
    .ck-summary__count {
        font-size: 12px;
        font-weight: 600;
        color: var(--ink-500);
        background: var(--ink-50);
        padding: 4px 12px;
        border-radius: var(--radius-pill);
    }

    /* Items */
    .ck-summary__items {
        max-height: 260px;
        overflow-y: auto;
        margin-bottom: 4px;
        scrollbar-width: thin;
        scrollbar-color: var(--ink-100) transparent;
    }
    .ck-summary__items::-webkit-scrollbar {
        width: 4px;
    }
    .ck-summary__items::-webkit-scrollbar-thumb {
        background: var(--ink-100);
        border-radius: 4px;
    }
    .ck-summary__item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--ink-100);
    }
    .ck-summary__item:last-child {
        border-bottom: none;
    }
    .ck-summary__item-info {
        flex: 1;
        min-width: 0;
    }
    .ck-summary__item-name {
        display: block;
        font-family: var(--font-sans);
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink-900);
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .ck-summary__item-qty {
        display: block;
        font-size: 12px;
        color: var(--ink-500);
        margin-top: 2px;
    }
    .ck-summary__item-price {
        font-family: var(--font-sans);
        font-size: 14px;
        font-weight: 700;
        color: var(--dv-navy);
        white-space: nowrap;
        margin-left: 16px;
    }

    /* Dividers */
    .ck-summary__divider {
        height: 1px;
        background: var(--ink-100);
        margin: 14px 0;
    }
    .ck-summary__divider--bold {
        height: 2px;
        background: var(--ink-900);
    }

    /* Totals */
    .ck-summary__totals {}
    .ck-summary__row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 14px;
        color: var(--ink-500);
    }
    .ck-summary__row span:last-child {
        font-weight: 600;
        color: var(--ink-700);
    }
    .ck-summary__row--discount span:last-child {
        color: #16a34a;
        font-weight: 700;
    }

    /* Grand Total */
    .ck-summary__grand-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 4px 0 0;
    }
    .ck-summary__grand-total span:first-child {
        font-family: var(--font-sans);
        font-size: 16px;
        font-weight: 700;
        color: var(--ink-900);
    }
    .ck-summary__grand-price {
        font-family: var(--font-sans);
        font-size: 24px;
        font-weight: 800;
        color: var(--dv-navy);
        letter-spacing: -0.5px;
    }

    /* Place Order */
    .ck-place-order {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 16px 24px;
        margin-top: 24px;
        border: none;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, var(--dv-navy), var(--dv-navy-dark));
        color: #fff;
        font-family: var(--font-sans);
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .ck-place-order::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--dv-orange), var(--dv-orange-dark));
        opacity: 0;
        transition: opacity 0.35s ease;
    }
    .ck-place-order:hover::before {
        opacity: 1;
    }
    .ck-place-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(245, 166, 35, 0.3);
        color: #fff;
    }
    .ck-place-order span {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ck-place-order:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }
    .ck-place-order:disabled::before {
        display: none;
    }

    /* Trust Indicators */
    .ck-trust {
        display: flex;
        justify-content: center;
        gap: 16px;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid var(--ink-100);
    }
    .ck-trust__item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        color: var(--ink-500);
        font-weight: 500;
    }
    .ck-trust__item i {
        color: #16a34a;
        font-size: 13px;
    }

    /* Accepted Payments */
    .ck-accepted {
        text-align: center;
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid var(--ink-100);
    }
    .ck-accepted span {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--ink-300);
        display: block;
        margin-bottom: 8px;
    }
    .ck-accepted__icons {
        display: flex;
        justify-content: center;
        gap: 12px;
    }
    .ck-accepted__icons i {
        font-size: 26px;
        color: var(--ink-300);
        transition: color 0.2s;
    }
    .ck-accepted__icons i:hover {
        color: var(--dv-navy);
    }

    /* ══════════════════════════════════════════════════════
       ANIMATIONS
       ══════════════════════════════════════════════════════ */
    @keyframes ck-slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .ck-accordion {
        animation: ck-fadeIn 0.4s ease forwards;
    }
    .ck-accordion:nth-child(2) { animation-delay: 0.08s; }
    .ck-accordion:nth-child(3) { animation-delay: 0.16s; }

    @keyframes ck-fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .ck-summary {
        animation: ck-fadeIn 0.4s ease 0.1s both;
    }

    /* Accordion transition */
    .ck-transition-enter {
        transition: all 0.35s ease;
    }
    .ck-transition-enter-start {
        opacity: 0;
        transform: translateY(-8px);
    }
    .ck-transition-enter-end {
        opacity: 1;
        transform: translateY(0);
    }
    [x-cloak] { display: none !important; }

    /* ══════════════════════════════════════════════════════
       RESPONSIVE
       ══════════════════════════════════════════════════════ */
    @media (max-width: 992px) {
        .ck-layout {
            grid-template-columns: 1fr;
        }
        .ck-summary-col {
            position: static;
            order: -1;
        }
        .ck-summary__items {
            max-height: 180px;
        }
    }

    @media (max-width: 768px) {
        .checkout-container {
            padding-left: 12px;
            padding-right: 12px;
        }
        .ck-progress-wrapper {
            padding: 0;
        }
        .ck-progress-step__label {
            font-size: 9px;
        }
        .ck-progress-step__circle {
            width: 34px;
            height: 34px;
            font-size: 12px;
        }
        .ck-accordion__header {
            padding: 16px 18px;
        }
        .ck-accordion__content {
            padding: 18px 18px 22px;
        }
        .ck-form-row {
            grid-template-columns: 1fr;
        }
        .ck-form-row--3 {
            grid-template-columns: 1fr;
        }
        .ck-ship-toggle {
            grid-template-columns: 1fr;
        }
        .ck-saved-addresses__grid {
            grid-template-columns: 1fr;
        }
        .ck-summary {
            padding: 20px;
        }
        .ck-trust {
            flex-wrap: wrap;
            gap: 10px;
        }
        .ck-accordion__footer {
            flex-direction: column;
            gap: 10px;
        }
        .ck-accordion__footer .ck-btn {
            width: 100%;
            justify-content: center;
        }
        .ck-summary__grand-price {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .ck-accordion__title {
            font-size: 15px;
        }
        .ck-accordion__subtitle {
            font-size: 12px;
        }
        .ck-accordion__step-badge {
            width: 34px;
            height: 34px;
            font-size: 13px;
        }
    }
</style>
@endpush
