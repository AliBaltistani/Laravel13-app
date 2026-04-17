{{-- Checkout Page Livewire View - DEVOGUE Accordion Checkout Redesign --}}
<div>
    @if($errorMessage)
        <div class="ck-alert ck-alert--error" role="alert">
            <div class="ck-alert__icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="ck-alert__body">
                <strong>Oops!</strong> {{ $errorMessage }}
            </div>
            <button type="button" class="ck-alert__close" data-dismiss="alert"><i class="fas fa-times"></i></button>
        </div>
    @endif

    @if($items->isEmpty())
        <div class="ck-empty-state">
            <div class="ck-empty-state__icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added anything yet.<br>Start shopping and find something you love!</p>
            <a href="{{ url('/shop') }}" class="btn-luxury btn-luxury--filled">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
        </div>
    @else
    <div class="ck-layout" x-data="{
        activeStep: 1,
        completedSteps: [],
        openStep(step) {
            this.activeStep = step;
        },
        completeStep(step) {
            if (!this.completedSteps.includes(step)) {
                this.completedSteps.push(step);
            }
            this.activeStep = step + 1;
        },
        isCompleted(step) {
            return this.completedSteps.includes(step);
        }
    }">
        {{-- ══════ Left Column: Accordion Steps ══════ --}}
        <div class="ck-steps-col">

            {{-- ────── STEP 1: Billing Details ────── --}}
            <div class="ck-accordion" :class="{ 'is-active': activeStep === 1, 'is-completed': isCompleted(1) }">
                <button class="ck-accordion__header" @click="openStep(1)" type="button">
                    <div class="ck-accordion__step-badge" :class="{ 'completed': isCompleted(1) }">
                        <span x-show="!isCompleted(1)">1</span>
                        <i class="fas fa-check" x-show="isCompleted(1)" x-cloak></i>
                    </div>
                    <div class="ck-accordion__title-wrap">
                        <h3 class="ck-accordion__title">Billing Details</h3>
                        <p class="ck-accordion__subtitle">Where should we send the invoice?</p>
                    </div>
                    <div class="ck-accordion__toggle">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div class="ck-accordion__body" x-show="activeStep === 1" x-cloak x-transition:enter="ck-transition-enter" x-transition:enter-start="ck-transition-enter-start" x-transition:enter-end="ck-transition-enter-end">
                    <div class="ck-accordion__content">

                        {{-- Saved Addresses --}}
                        @if($savedAddresses->count())
                        <div class="ck-saved-addresses">
                            <label class="ck-field-label">
                                <i class="fas fa-bookmark"></i> Your Saved Addresses
                            </label>
                            <div class="ck-saved-addresses__grid">
                                @foreach($savedAddresses as $addr)
                                    <div class="ck-saved-addr {{ $saved_address_id == $addr->id ? 'is-selected' : '' }}"
                                         wire:click="loadSavedAddress({{ $addr->id }})">
                                        <div class="ck-saved-addr__radio">
                                            <span class="ck-radio {{ $saved_address_id == $addr->id ? 'checked' : '' }}"></span>
                                        </div>
                                        <div class="ck-saved-addr__info">
                                            <div class="ck-saved-addr__name">{{ $addr->first_name }} {{ $addr->last_name }}</div>
                                            <div class="ck-saved-addr__detail">
                                                {{ $addr->address_line1 }}, {{ $addr->city }}, {{ $addr->state }} {{ $addr->postal_code }}
                                            </div>
                                            @if($addr->is_default_shipping)
                                                <span class="ck-saved-addr__badge">
                                                    <i class="fas fa-star"></i> Default
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <form id="checkout-form">
                            <div class="ck-form-row">
                                <div class="ck-form-group">
                                    <label class="ck-label">First Name <span class="ck-required">*</span></label>
                                    <div class="ck-input-wrap">
                                        <i class="fas fa-user ck-input-icon"></i>
                                        <input type="text" class="ck-input @error('billing_first_name') is-invalid @enderror" wire:model="billing_first_name" placeholder="John" required />
                                    </div>
                                    @error('billing_first_name') <span class="ck-error">{{ $message }}</span> @enderror
                                </div>
                                <div class="ck-form-group">
                                    <label class="ck-label">Last Name <span class="ck-required">*</span></label>
                                    <div class="ck-input-wrap">
                                        <i class="fas fa-user ck-input-icon"></i>
                                        <input type="text" class="ck-input @error('billing_last_name') is-invalid @enderror" wire:model="billing_last_name" placeholder="Doe" required />
                                    </div>
                                    @error('billing_last_name') <span class="ck-error">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="ck-form-group">
                                <label class="ck-label">Street Address <span class="ck-required">*</span></label>
                                <div class="ck-input-wrap">
                                    <i class="fas fa-map-marker-alt ck-input-icon"></i>
                                    <input type="text" class="ck-input @error('billing_address_line1') is-invalid @enderror" wire:model="billing_address_line1" placeholder="House number and street name" required />
                                </div>
                                @error('billing_address_line1') <span class="ck-error">{{ $message }}</span> @enderror
                                <input type="text" class="ck-input mt-2" wire:model="billing_address_line2" placeholder="Apartment, suite, unit, etc. (optional)" style="padding-left: 14px;" />
                            </div>

                            <div class="ck-form-row">
                                <div class="ck-form-group">
                                    <label class="ck-label">Town / City <span class="ck-required">*</span></label>
                                    <div class="ck-input-wrap">
                                        <i class="fas fa-city ck-input-icon"></i>
                                        <input type="text" class="ck-input @error('billing_city') is-invalid @enderror" wire:model="billing_city" required />
                                    </div>
                                    @error('billing_city') <span class="ck-error">{{ $message }}</span> @enderror
                                </div>
                                <div class="ck-form-group">
                                    <label class="ck-label">State <span class="ck-required">*</span></label>
                                    <div class="ck-input-wrap">
                                        <i class="fas fa-map ck-input-icon"></i>
                                        <input type="text" class="ck-input @error('billing_state') is-invalid @enderror" wire:model="billing_state" required />
                                    </div>
                                    @error('billing_state') <span class="ck-error">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="ck-form-row">
                                <div class="ck-form-group">
                                    <label class="ck-label">Postcode / ZIP <span class="ck-required">*</span></label>
                                    <div class="ck-input-wrap">
                                        <i class="fas fa-hashtag ck-input-icon"></i>
                                        <input type="text" class="ck-input @error('billing_postal_code') is-invalid @enderror" wire:model="billing_postal_code" required />
                                    </div>
                                    @error('billing_postal_code') <span class="ck-error">{{ $message }}</span> @enderror
                                </div>
                                <div class="ck-form-group">
                                    <label class="ck-label">Phone</label>
                                    <div class="ck-input-wrap">
                                        <i class="fas fa-phone ck-input-icon"></i>
                                        <input type="tel" class="ck-input" wire:model="billing_phone" placeholder="+1 (234) 567-8900" />
                                    </div>
                                </div>
                            </div>

                            <div class="ck-form-group">
                                <label class="ck-label">Email Address <span class="ck-required">*</span></label>
                                <div class="ck-input-wrap">
                                    <i class="fas fa-envelope ck-input-icon"></i>
                                    <input type="email" class="ck-input @error('billing_email') is-invalid @enderror" wire:model="billing_email" placeholder="your@email.com" required />
                                </div>
                                @error('billing_email') <span class="ck-error">{{ $message }}</span> @enderror
                                <span class="ck-hint"><i class="fas fa-info-circle"></i> Order confirmation will be sent to this email</span>
                            </div>
                        </form>

                        <div class="ck-accordion__footer">
                            <button type="button" class="ck-btn ck-btn--primary" @click="completeStep(1)">
                                Continue to Shipping <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────── STEP 2: Shipping ────── --}}
            <div class="ck-accordion" :class="{ 'is-active': activeStep === 2, 'is-completed': isCompleted(2) }">
                <button class="ck-accordion__header" @click="openStep(2)" type="button">
                    <div class="ck-accordion__step-badge" :class="{ 'completed': isCompleted(2) }">
                        <span x-show="!isCompleted(2)">2</span>
                        <i class="fas fa-check" x-show="isCompleted(2)" x-cloak></i>
                    </div>
                    <div class="ck-accordion__title-wrap">
                        <h3 class="ck-accordion__title">Shipping Address</h3>
                        <p class="ck-accordion__subtitle">Where should we deliver your order?</p>
                    </div>
                    <div class="ck-accordion__toggle">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div class="ck-accordion__body" x-show="activeStep === 2" x-cloak x-transition:enter="ck-transition-enter" x-transition:enter-start="ck-transition-enter-start" x-transition:enter-end="ck-transition-enter-end">
                    <div class="ck-accordion__content">

                        {{-- Same as billing toggle --}}
                        <div class="ck-ship-toggle">
                            <div class="ck-ship-toggle__option {{ !$shipToDifferentAddress ? 'is-active' : '' }}"
                                 wire:click="$set('shipToDifferentAddress', false)">
                                <span class="ck-radio {{ !$shipToDifferentAddress ? 'checked' : '' }}"></span>
                                <div>
                                    <strong><i class="fas fa-home"></i> Same as billing address</strong>
                                    <p>Ship to my billing address above</p>
                                </div>
                            </div>

                            <div class="ck-ship-toggle__option {{ $shipToDifferentAddress ? 'is-active' : '' }}"
                                 wire:click="$set('shipToDifferentAddress', true)">
                                <span class="ck-radio {{ $shipToDifferentAddress ? 'checked' : '' }}"></span>
                                <div>
                                    <strong><i class="fas fa-truck"></i> Ship to a different address</strong>
                                    <p>Enter a new shipping address</p>
                                </div>
                            </div>
                        </div>

                        @if($shipToDifferentAddress)
                        <div class="ck-diff-shipping" style="margin-top: 20px;">
                            <div class="ck-form-row">
                                <div class="ck-form-group">
                                    <label class="ck-label">First Name <span class="ck-required">*</span></label>
                                    <div class="ck-input-wrap">
                                        <i class="fas fa-user ck-input-icon"></i>
                                        <input type="text" class="ck-input" wire:model="shipping_first_name" required />
                                    </div>
                                </div>
                                <div class="ck-form-group">
                                    <label class="ck-label">Last Name <span class="ck-required">*</span></label>
                                    <div class="ck-input-wrap">
                                        <i class="fas fa-user ck-input-icon"></i>
                                        <input type="text" class="ck-input" wire:model="shipping_last_name" required />
                                    </div>
                                </div>
                            </div>
                            <div class="ck-form-group">
                                <label class="ck-label">Street Address <span class="ck-required">*</span></label>
                                <div class="ck-input-wrap">
                                    <i class="fas fa-map-marker-alt ck-input-icon"></i>
                                    <input type="text" class="ck-input" wire:model="shipping_address_line1" placeholder="House number and street name" required />
                                </div>
                                <input type="text" class="ck-input mt-2" wire:model="shipping_address_line2" placeholder="Apartment, suite, etc. (optional)" style="padding-left: 14px;" />
                            </div>
                            <div class="ck-form-row ck-form-row--3">
                                <div class="ck-form-group">
                                    <label class="ck-label">City <span class="ck-required">*</span></label>
                                    <input type="text" class="ck-input" wire:model="shipping_city" required />
                                </div>
                                <div class="ck-form-group">
                                    <label class="ck-label">State <span class="ck-required">*</span></label>
                                    <input type="text" class="ck-input" wire:model="shipping_state" required />
                                </div>
                                <div class="ck-form-group">
                                    <label class="ck-label">Postcode <span class="ck-required">*</span></label>
                                    <input type="text" class="ck-input" wire:model="shipping_postal_code" required />
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Order Notes --}}
                        <div class="ck-notes-section">
                            <label class="ck-label"><i class="fas fa-sticky-note"></i> Order Notes <span class="ck-optional">(optional)</span></label>
                            <textarea class="ck-textarea" wire:model="customer_notes" placeholder="Any special instructions? e.g. Leave at the door, ring doorbell twice..."></textarea>
                        </div>

                        <div class="ck-accordion__footer">
                            <button type="button" class="ck-btn ck-btn--ghost" @click="openStep(1)">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" class="ck-btn ck-btn--primary" @click="completeStep(2)">
                                Continue to Payment <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────── STEP 3: Shipping Method & Payment ────── --}}
            <div class="ck-accordion" :class="{ 'is-active': activeStep === 3, 'is-completed': isCompleted(3) }">
                <button class="ck-accordion__header" @click="openStep(3)" type="button">
                    <div class="ck-accordion__step-badge" :class="{ 'completed': isCompleted(3) }">
                        <span x-show="!isCompleted(3)">3</span>
                        <i class="fas fa-check" x-show="isCompleted(3)" x-cloak></i>
                    </div>
                    <div class="ck-accordion__title-wrap">
                        <h3 class="ck-accordion__title">Delivery Method</h3>
                        <p class="ck-accordion__subtitle">Choose how to get your order delivered</p>
                    </div>
                    <div class="ck-accordion__toggle">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div class="ck-accordion__body" x-show="activeStep === 3" x-cloak x-transition:enter="ck-transition-enter" x-transition:enter-start="ck-transition-enter-start" x-transition:enter-end="ck-transition-enter-end">
                    <div class="ck-accordion__content">

                        {{-- Shipping Methods --}}
                        <div class="ck-section-block">
                            <h4 class="ck-section-label"><i class="fas fa-truck"></i> Shipping Method</h4>
                            <div class="ck-shipping-options">
                                @if($shippingMethods->count())
                                    @foreach($shippingMethods as $method)
                                        <label class="ck-shipping-card {{ $shipping_method_id == $method->id ? 'is-selected' : '' }}" for="shipping-{{ $method->id }}">
                                            <input type="radio" name="shipping_method" id="shipping-{{ $method->id }}"
                                                   value="{{ $method->id }}" wire:model.live="shipping_method_id"
                                                   {{ $shipping_method_id == $method->id ? 'checked' : '' }}
                                                   class="ck-radio-input">
                                            <span class="ck-radio"></span>
                                            <div class="ck-shipping-card__info">
                                                <div class="ck-shipping-card__name">{{ $method->name }}</div>
                                                @if($method->estimated_days)
                                                    <div class="ck-shipping-card__eta">
                                                        <i class="far fa-clock"></i> {{ $method->estimated_days }} business days
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ck-shipping-card__price">
                                                @if($method->type === 'free')
                                                    <span class="ck-free-badge">FREE</span>
                                                @else
                                                    @price($method->price)
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                @else
                                    <label class="ck-shipping-card is-selected">
                                        <span class="ck-radio checked"></span>
                                        <div class="ck-shipping-card__info">
                                            <div class="ck-shipping-card__name">Free Shipping</div>
                                            <div class="ck-shipping-card__eta"><i class="far fa-clock"></i> Standard delivery</div>
                                        </div>
                                        <div class="ck-shipping-card__price">
                                            <span class="ck-free-badge">FREE</span>
                                        </div>
                                    </label>
                                @endif
                            </div>
                        </div>



                        <div class="ck-accordion__footer">
                            <button type="button" class="ck-btn ck-btn--ghost" @click="openStep(2)">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══════ Right Column: Sticky Order Summary ══════ --}}
        <div class="ck-summary-col">
            <div class="ck-summary">
                <div class="ck-summary__header">
                    <h3><i class="fas fa-shopping-bag"></i> Order Summary</h3>
                    <span class="ck-summary__count">{{ $items->count() }} {{ Str::plural('item', $items->count()) }}</span>
                </div>

                {{-- Order Items --}}
                <div class="ck-summary__items">
                    @foreach($items as $item)
                        <div class="ck-summary__item">
                            <div class="ck-summary__item-info">
                                <span class="ck-summary__item-name">{{ $item->product->name }}</span>
                                <span class="ck-summary__item-qty">Qty: {{ $item->quantity }}</span>
                            </div>
                            <span class="ck-summary__item-price">@price($item->unit_price * $item->quantity)</span>
                        </div>
                    @endforeach
                </div>

                {{-- Divider --}}
                <div class="ck-summary__divider"></div>

                {{-- Totals --}}
                <div class="ck-summary__totals">
                    <div class="ck-summary__row">
                        <span>Subtotal</span>
                        <span>@price($subtotal)</span>
                    </div>
                    @if($discount > 0)
                        <div class="ck-summary__row ck-summary__row--discount">
                            <span><i class="fas fa-tag"></i> Discount</span>
                            <span>-@price($discount)</span>
                        </div>
                    @endif
                    <div class="ck-summary__row">
                        <span>Shipping</span>
                        <span>
                            @php
                                $selectedShipping = $shippingMethods->firstWhere('id', $shipping_method_id);
                            @endphp
                            @if($selectedShipping && $selectedShipping->type === 'free')
                                <span class="ck-free-badge-sm">FREE</span>
                            @elseif($selectedShipping)
                                @price($selectedShipping->price)
                            @else
                                <span class="ck-free-badge-sm">FREE</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="ck-summary__divider ck-summary__divider--bold"></div>

                {{-- Grand Total --}}
                <div class="ck-summary__grand-total">
                    <span>Total</span>
                    <span class="ck-summary__grand-price">@price($total)</span>
                </div>

                {{-- Payment Methods --}}
                <div class="ck-summary__payment">
                    <h4 class="ck-section-label"><i class="fas fa-shield-alt"></i> Payment Method</h4>
                    <div class="ck-payment-options">
                        @forelse($enabledPaymentMethods as $methodKey => $methodLabel)
                            <label class="ck-payment-card {{ $payment_method === $methodKey ? 'is-selected' : '' }}" for="payment-{{ $methodKey }}">
                                <input type="radio" name="payment_method" id="payment-{{ $methodKey }}"
                                       value="{{ $methodKey }}" wire:model.live="payment_method"
                                       {{ $loop->first && !$payment_method ? 'checked' : '' }}
                                       class="ck-radio-input">
                                <span class="ck-radio"></span>
                                <div class="ck-payment-card__icon">
                                    @if($methodKey === 'cod') <i class="fas fa-money-bill-wave"></i>
                                    @elseif($methodKey === 'bank_transfer') <i class="fas fa-university"></i>
                                    @elseif($methodKey === 'stripe') <i class="fab fa-cc-stripe"></i>
                                    @elseif($methodKey === 'paypal') <i class="fab fa-cc-paypal"></i>
                                    @else <i class="fas fa-credit-card"></i>
                                    @endif
                                </div>
                                <div class="ck-payment-card__info">
                                    <div class="ck-payment-card__name">{{ $methodLabel }}</div>
                                    <div class="ck-payment-card__desc">
                                        @if($methodKey === 'cod')
                                            {{ $codInstructions ?: 'Pay with cash upon delivery.' }}
                                        @elseif($methodKey === 'bank_transfer')
                                            {!! nl2br(e($bankTransferDetails ?: 'Make your payment directly into our bank account.')) !!}
                                        @elseif($methodKey === 'stripe')
                                            Pay securely with your credit or debit card.
                                        @elseif($methodKey === 'paypal')
                                            You will be redirected to PayPal.
                                        @endif
                                    </div>
                                </div>
                            </label>

                            {{-- Stripe Card Element --}}
                            @if($methodKey === 'stripe')
                                <div x-data="{ method: $wire.entangle('payment_method') }"
                                     x-show="method === 'stripe'"
                                     wire:ignore
                                     class="ck-stripe-wrapper"
                                     id="stripe-card-container">
                                    <div id="stripe-loading" class="ck-stripe-loading" style="display:none;">
                                        <i class="fas fa-spinner fa-spin"></i> Loading Secure Payment...
                                    </div>
                                    <div class="ck-stripe-label"><i class="fas fa-lock"></i> Card Details</div>
                                    <div id="stripe-card-element" class="ck-stripe-element"></div>
                                    <div id="stripe-card-errors" class="ck-error"></div>
                                </div>
                            @endif
                        @empty
                            <div class="ck-no-payment">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p>No payment methods available. Please contact support.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Place Order --}}
                <button type="button" class="ck-place-order"
                        id="checkout-button" onclick="processCheckout()"
                        wire:loading.attr="disabled"
                        {{ $processing ? 'disabled' : '' }}
                        @if(count($enabledPaymentMethods) === 0) disabled @endif>
                    <span wire:loading.remove wire:target="placeOrder">
                        <i class="fas fa-lock"></i> Place Order — @price($total)
                    </span>
                    <span wire:loading wire:target="placeOrder">
                        <i class="fas fa-spinner fa-spin"></i> Processing your order...
                    </span>
                </button>

                {{-- Trust Indicators --}}
                <div class="ck-trust">
                    <div class="ck-trust__item">
                        <i class="fas fa-shield-alt"></i>
                        <span>256-bit SSL Encrypted</span>
                    </div>
                    <div class="ck-trust__item">
                        <i class="fas fa-undo"></i>
                        <span>Easy Returns</span>
                    </div>
                    <div class="ck-trust__item">
                        <i class="fas fa-headset"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>

                {{-- Accepted Payment Icons --}}
                <div class="ck-accepted">
                    <span>We Accept</span>
                    <div class="ck-accepted__icons">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-paypal"></i>
                        <i class="fab fa-cc-stripe"></i>
                        <i class="fab fa-cc-amex"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Stripe JS --}}
@if(isset($enabledPaymentMethods['stripe']))
@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('livewire:initialized', function() {
        let stripeKey = @json($stripePublishableKey);
        if (!stripeKey) return;

        let stripe = Stripe(stripeKey);
        let elements = stripe.elements();
        let cardElement = null;

        function mountStripeCard() {
            let container = document.getElementById('stripe-card-element');
            if (container && !cardElement) {
                let loadingEl = document.getElementById('stripe-loading');
                if (loadingEl) loadingEl.style.display = 'block';

                cardElement = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '15px',
                            color: '#1a1d2e',
                            fontFamily: '"Inter", "Open Sans", sans-serif',
                            fontWeight: '500',
                            '::placeholder': { color: '#b0b4c4' }
                        }
                    }
                });
                cardElement.mount('#stripe-card-element');

                cardElement.on('ready', function() {
                    if (loadingEl) loadingEl.style.display = 'none';
                });

                cardElement.on('change', function(event) {
                    let errorsEl = document.getElementById('stripe-card-errors');
                    if (errorsEl) {
                        errorsEl.textContent = event.error ? event.error.message : '';
                    }
                });
            }
        }

        setTimeout(mountStripeCard, 300);

        window.processCheckout = async function() {
            let paymentMethod = await @this.get('payment_method');

            if (paymentMethod === 'stripe' && cardElement) {
                @this.set('processing', true);
                @this.set('errorMessage', '');

                const { paymentMethod: pm, error } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                });

                if (error) {
                    @this.set('errorMessage', error.message);
                    @this.set('processing', false);
                    return false;
                }

                await @this.set('stripe_payment_method_id', pm.id);
            }

            @this.call('placeOrder');
        };
    });
</script>
@endpush
@endif
