{{-- Checkout Page Livewire View - Modern Porto Design --}}
<div>
    @if($errorMessage)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ $errorMessage }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($items->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag" style="font-size: 64px; color: #ddd;"></i>
            <h3 class="mt-3" style="color: #1a1a2e;">Your cart is empty</h3>
            <p class="text-muted mb-4">Add some products before proceeding to checkout.</p>
            <a href="{{ url('/shop') }}" class="btn btn-dark mt-2">Continue Shopping</a>
        </div>
    @else
    <div class="row">
        {{-- ══════ Left Column: Billing & Shipping ══════ --}}
        <div class="col-lg-7">

            {{-- Billing Details Card --}}
            <div class="checkout-billing-card">
                <div class="section-header">
                    <div class="section-icon billing">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h3>Billing Details</h3>
                        <p>Enter your billing information</p>
                    </div>
                </div>

                {{-- Saved Addresses --}}
                @if($savedAddresses->count())
                <div class="mb-4">
                    <label class="mb-2" style="font-size:12px;text-transform:uppercase;letter-spacing:0.8px;color:#999;font-weight:700;">
                        <i class="fas fa-bookmark mr-1"></i> Saved Addresses
                    </label>
                    <div class="row">
                        @foreach($savedAddresses as $addr)
                            <div class="col-sm-6 mb-2">
                                <div class="saved-address-card {{ $saved_address_id == $addr->id ? 'selected' : '' }}"
                                     wire:click="loadSavedAddress({{ $addr->id }})">
                                    <div class="addr-name">
                                        <i class="fas fa-user-circle mr-1" style="color:#667eea;"></i>
                                        {{ $addr->first_name }} {{ $addr->last_name }}
                                    </div>
                                    <div class="addr-detail mt-1">
                                        {{ $addr->address_line1 }}, {{ $addr->city }},<br>
                                        {{ $addr->state }} {{ $addr->postal_code }}
                                    </div>
                                    @if($addr->is_default_shipping)
                                        <span class="badge mt-1" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;font-size:10px;padding:3px 8px;border-radius:4px;">
                                            <i class="fas fa-star mr-1" style="font-size:8px;"></i>Default
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <form id="checkout-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First name <abbr class="required" title="required">*</abbr></label>
                                <input type="text" class="form-control @error('billing_first_name') is-invalid @enderror" wire:model="billing_first_name" placeholder="John" required />
                                @error('billing_first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last name <abbr class="required" title="required">*</abbr></label>
                                <input type="text" class="form-control @error('billing_last_name') is-invalid @enderror" wire:model="billing_last_name" placeholder="Doe" required />
                                @error('billing_last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Street address <abbr class="required" title="required">*</abbr></label>
                        <input type="text" class="form-control @error('billing_address_line1') is-invalid @enderror" wire:model="billing_address_line1" placeholder="House number and street name" required />
                        @error('billing_address_line1') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <input type="text" class="form-control mt-2" wire:model="billing_address_line2" placeholder="Apartment, suite, unit, etc. (optional)" />
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Town / City <abbr class="required" title="required">*</abbr></label>
                                <input type="text" class="form-control @error('billing_city') is-invalid @enderror" wire:model="billing_city" required />
                                @error('billing_city') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>State <abbr class="required" title="required">*</abbr></label>
                                <input type="text" class="form-control @error('billing_state') is-invalid @enderror" wire:model="billing_state" required />
                                @error('billing_state') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Postcode / ZIP <abbr class="required" title="required">*</abbr></label>
                                <input type="text" class="form-control @error('billing_postal_code') is-invalid @enderror" wire:model="billing_postal_code" required />
                                @error('billing_postal_code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="tel" class="form-control" wire:model="billing_phone" placeholder="+1 (234) 567-8900" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email address <abbr class="required" title="required">*</abbr></label>
                        <input type="email" class="form-control @error('billing_email') is-invalid @enderror" wire:model="billing_email" placeholder="your@email.com" required />
                        @error('billing_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </form>
            </div>

            {{-- Ship to Different Address Card --}}
            <div class="checkout-billing-card">
                <div class="section-header" style="margin-bottom:0; padding-bottom:0; border-bottom:none;">
                    <div class="section-icon shipping-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="custom-control custom-checkbox" style="margin:0;">
                            <input type="checkbox" class="custom-control-input" id="different-shipping" wire:model.live="shipToDifferentAddress" />
                            <label class="custom-control-label" for="different-shipping" style="font-size:15px;font-weight:700;color:#1a1a2e;">
                                Ship to a different address?
                            </label>
                        </div>
                    </div>
                </div>

                @if($shipToDifferentAddress)
                <div class="mt-3 pt-3" style="border-top: 1px solid #f0f0f0;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First name <abbr class="required">*</abbr></label>
                                <input type="text" class="form-control" wire:model="shipping_first_name" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last name <abbr class="required">*</abbr></label>
                                <input type="text" class="form-control" wire:model="shipping_last_name" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Street address <abbr class="required">*</abbr></label>
                        <input type="text" class="form-control" wire:model="shipping_address_line1" placeholder="House number and street name" required />
                        <input type="text" class="form-control mt-2" wire:model="shipping_address_line2" placeholder="Apartment, suite, etc. (optional)" />
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City <abbr class="required">*</abbr></label>
                                <input type="text" class="form-control" wire:model="shipping_city" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State <abbr class="required">*</abbr></label>
                                <input type="text" class="form-control" wire:model="shipping_state" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Postcode <abbr class="required">*</abbr></label>
                                <input type="text" class="form-control" wire:model="shipping_postal_code" required />
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Order Notes Card --}}
            <div class="checkout-billing-card">
                <div class="section-header">
                    <div class="section-icon notes-icon">
                        <i class="fas fa-sticky-note"></i>
                    </div>
                    <div>
                        <h3>Order Notes</h3>
                        <p>Any special instructions for delivery</p>
                    </div>
                </div>
                <textarea class="form-control" wire:model="customer_notes" placeholder="Notes about your order, e.g. special notes for delivery..."></textarea>
            </div>
        </div>

        {{-- ══════ Right Column: Order Summary ══════ --}}
        <div class="col-lg-5">
            <div class="order-summary-card">
                <h3 class="order-title"><i class="fas fa-receipt mr-2" style="font-size:14px;"></i>Your Order</h3>

                {{-- Order Items --}}
                <div class="order-items-list">
                    @foreach($items as $item)
                        <div class="order-item">
                            <div class="item-name">
                                {{ $item->product->name }}
                                <span class="item-qty">×{{ $item->quantity }}</span>
                            </div>
                            <div class="item-price">@price($item->unit_price * $item->quantity)</div>
                        </div>
                    @endforeach
                </div>

                {{-- Shipping Methods --}}
                <div class="shipping-methods-section">
                    <h5><i class="fas fa-truck mr-1"></i> Shipping</h5>
                    @if($shippingMethods->count())
                        @foreach($shippingMethods as $method)
                            <div class="shipping-method-option">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="shipping_method"
                                           id="shipping-{{ $method->id }}" value="{{ $method->id }}"
                                           wire:model.live="shipping_method_id"
                                           {{ $shipping_method_id == $method->id ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="shipping-{{ $method->id }}">
                                        <span class="method-name">{{ $method->name }}</span>
                                        @if($method->type === 'free')
                                            <span class="method-price" style="color:#27ae60;">Free</span>
                                        @else
                                            <span class="method-price">@price($method->price)</span>
                                        @endif
                                        @if($method->estimated_days)
                                            <span class="method-days d-block">({{ $method->estimated_days }} days)</span>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="shipping-method-option">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" checked>
                                <label class="custom-control-label">
                                    <span class="method-name">Free Shipping</span>
                                    <span class="method-price" style="color:#27ae60;">Free</span>
                                </label>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Totals --}}
                <table class="order-totals-table">
                    <tr>
                        <td class="label-col">Subtotal</td>
                        <td class="value-col">@price($subtotal)</td>
                    </tr>
                    @if($discount > 0)
                        <tr>
                            <td class="label-col">Discount</td>
                            <td class="value-col" style="color:#27ae60;">-@price($discount)</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td>Total</td>
                        <td style="text-align:right;">@price($total)</td>
                    </tr>
                </table>

                {{-- Payment Methods --}}
                <div class="payment-methods-section">
                    <h4><i class="fas fa-lock mr-1" style="font-size:12px;"></i> Payment Method</h4>

                    @forelse($enabledPaymentMethods as $methodKey => $methodLabel)
                        <div class="payment-option {{ $payment_method === $methodKey ? 'selected' : '' }}">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment_method"
                                       id="payment-{{ $methodKey }}" value="{{ $methodKey }}"
                                       wire:model.live="payment_method"
                                       {{ $loop->first && !$payment_method ? 'checked' : '' }}>
                                <label class="custom-control-label" for="payment-{{ $methodKey }}">
                                    <span class="payment-label">
                                        @if($methodKey === 'cod') <i class="fas fa-money-bill-wave mr-1" style="color:#27ae60;"></i>
                                        @elseif($methodKey === 'bank_transfer') <i class="fas fa-university mr-1" style="color:#667eea;"></i>
                                        @elseif($methodKey === 'stripe') <i class="fab fa-cc-stripe mr-1" style="color:#635bff;"></i>
                                        @elseif($methodKey === 'paypal') <i class="fab fa-cc-paypal mr-1" style="color:#003087;"></i>
                                        @else <i class="fas fa-credit-card mr-1"></i>
                                        @endif
                                        {{ $methodLabel }}
                                    </span>

                                    @if($methodKey === 'cod' && $codInstructions)
                                        <span class="payment-desc d-block">{{ $codInstructions }}</span>
                                    @elseif($methodKey === 'cod')
                                        <span class="payment-desc d-block">Pay with cash upon delivery.</span>
                                    @endif
                                    @if($methodKey === 'bank_transfer' && $bankTransferDetails)
                                        <span class="payment-desc d-block">{!! nl2br(e($bankTransferDetails)) !!}</span>
                                    @elseif($methodKey === 'bank_transfer')
                                        <span class="payment-desc d-block">Make your payment directly into our bank account.</span>
                                    @endif
                                    @if($methodKey === 'stripe')
                                        <span class="payment-desc d-block">Pay securely with your credit or debit card.</span>
                                    @endif
                                    @if($methodKey === 'paypal')
                                        <span class="payment-desc d-block">You will be redirected to PayPal.</span>
                                    @endif
                                </label>
                            </div>
                        </div>

                        {{-- Stripe Card Element --}}
                        @if($methodKey === 'stripe' && $payment_method === 'stripe')
                            <div class="p-3 mb-2 border rounded" style="background:#f8f9fb;border-radius:10px !important;" id="stripe-card-container">
                                <div id="stripe-card-element" class="mb-2" style="padding:12px;border:1.5px solid #e8e8ef;border-radius:8px;background:#fff;"></div>
                                <div id="stripe-card-errors" class="text-danger small"></div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-3">
                            <i class="fas fa-exclamation-triangle text-warning mb-2" style="font-size:24px;"></i>
                            <p class="text-muted small mb-0">No payment methods available. Please contact support.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Place Order Button --}}
                <button type="button" class="btn-place-order-modern"
                        wire:click="placeOrder" wire:loading.attr="disabled"
                        {{ $processing ? 'disabled' : '' }}
                        @if(count($enabledPaymentMethods) === 0) disabled @endif>
                    <span wire:loading.remove wire:target="placeOrder">
                        <i class="fas fa-lock mr-2" style="font-size:13px;"></i>Place Order
                    </span>
                    <span wire:loading wire:target="placeOrder">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                    </span>
                </button>

                <div class="secure-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Secure 256-bit SSL Encrypted Checkout</span>
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
                cardElement = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '14px',
                            color: '#333',
                            fontFamily: '"Open Sans", sans-serif',
                            '::placeholder': { color: '#aab7c4' }
                        }
                    }
                });
                cardElement.mount('#stripe-card-element');
                cardElement.on('change', function(event) {
                    let errorsEl = document.getElementById('stripe-card-errors');
                    if (errorsEl) {
                        errorsEl.textContent = event.error ? event.error.message : '';
                    }
                });
            }
        }

        setTimeout(mountStripeCard, 500);

        Livewire.hook('morph.updated', () => {
            setTimeout(mountStripeCard, 200);
        });

        Livewire.on('placeOrder', async () => {
            let paymentMethod = @this.payment_method;
            if (paymentMethod === 'stripe' && cardElement) {
                const { paymentMethod: pm, error } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                });
                if (error) {
                    @this.set('errorMessage', error.message);
                    return false;
                }
                @this.set('stripe_payment_method_id', pm.id);
            }
        });
    });
</script>
@endpush
@endif
