{{-- Checkout Page Livewire View - Porto checkout.html markup --}}
<div>
    @if($errorMessage)
        <div class="alert alert-danger alert-dismissible">{{ $errorMessage }}</div>
    @endif

    @if($items->isEmpty())
        <div class="text-center py-5">
            <h3>Your cart is empty</h3>
            <a href="{{ url('/shop') }}" class="btn btn-dark mt-2">Continue Shopping</a>
        </div>
    @else
    <div class="row">
        {{-- Billing & Shipping --}}
        <div class="col-lg-7">
            <h2 class="step-title mb-2">Billing Details</h2>

            {{-- Saved Addresses --}}
            @if($savedAddresses->count())
                <div class="mb-3">
                    <label class="mb-1"><strong>Use saved address:</strong></label>
                    <div class="row">
                        @foreach($savedAddresses as $addr)
                            <div class="col-sm-6 mb-2">
                                <div class="card {{ $saved_address_id == $addr->id ? 'border-primary' : '' }}" style="cursor:pointer" wire:click="loadSavedAddress({{ $addr->id }})">
                                    <div class="card-body p-2">
                                        <strong>{{ $addr->first_name }} {{ $addr->last_name }}</strong><br>
                                        <small>{{ $addr->address_line1 }}, {{ $addr->city }}, {{ $addr->state }} {{ $addr->postal_code }}</small>
                                        @if($addr->is_default_shipping) <span class="badge badge-info ml-1">Default</span> @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>First Name <abbr class="required" title="required">*</abbr></label>
                        <input type="text" class="form-control @error('billing_first_name') is-invalid @enderror" wire:model="billing_first_name" required>
                        @error('billing_first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Last Name <abbr class="required" title="required">*</abbr></label>
                        <input type="text" class="form-control @error('billing_last_name') is-invalid @enderror" wire:model="billing_last_name" required>
                        @error('billing_last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Street Address <abbr class="required" title="required">*</abbr></label>
                <input type="text" class="form-control @error('billing_address_line1') is-invalid @enderror" wire:model="billing_address_line1" placeholder="House number and street name" required>
                @error('billing_address_line1') <span class="invalid-feedback">{{ $message }}</span> @enderror
                <input type="text" class="form-control mt-2" wire:model="billing_address_line2" placeholder="Apartment, suite, unit, etc. (optional)">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Town / City <abbr class="required" title="required">*</abbr></label>
                        <input type="text" class="form-control @error('billing_city') is-invalid @enderror" wire:model="billing_city" required>
                        @error('billing_city') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>State <abbr class="required" title="required">*</abbr></label>
                        <input type="text" class="form-control @error('billing_state') is-invalid @enderror" wire:model="billing_state" required>
                        @error('billing_state') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Postcode / ZIP <abbr class="required" title="required">*</abbr></label>
                        <input type="text" class="form-control @error('billing_postal_code') is-invalid @enderror" wire:model="billing_postal_code" required>
                        @error('billing_postal_code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" class="form-control" wire:model="billing_phone">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address <abbr class="required" title="required">*</abbr></label>
                <input type="email" class="form-control @error('billing_email') is-invalid @enderror" wire:model="billing_email" required>
                @error('billing_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            {{-- Ship to Different Address --}}
            <div class="form-group mt-3">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="ship-different" wire:model.live="shipToDifferentAddress">
                    <label class="custom-control-label" for="ship-different">Ship to a different address?</label>
                </div>
            </div>

            @if($shipToDifferentAddress)
            <div class="shipping-address-form mt-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" wire:model="shipping_first_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" wire:model="shipping_last_name">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Street Address</label>
                    <input type="text" class="form-control" wire:model="shipping_address_line1" placeholder="House number and street name">
                    <input type="text" class="form-control mt-2" wire:model="shipping_address_line2" placeholder="Apartment, suite, etc. (optional)">
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" class="form-control" wire:model="shipping_city">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" class="form-control" wire:model="shipping_state">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Postcode</label>
                            <input type="text" class="form-control" wire:model="shipping_postal_code">
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="form-group mt-3">
                <label>Order Notes (optional)</label>
                <textarea class="form-control" wire:model="customer_notes" rows="3" placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="col-lg-5">
            <div class="order-summary">
                <h3>Your Order</h3>

                <table class="table table-mini-cart">
                    <thead>
                        <tr>
                            <th colspan="2">Product</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td class="product-col">
                                    <h3 class="product-title">{{ $item->product->name }} × <span class="product-qty">{{ $item->quantity }}</span></h3>
                                </td>
                                <td class="price-col">
                                    <span>@price($item->unit_price * $item->quantity)</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="cart-subtotal">
                            <td><h4>Subtotal</h4></td>
                            <td class="price-col"><span>@price($subtotal)</span></td>
                        </tr>
                        @if($discount > 0)
                            <tr>
                                <td><h4>Discount</h4></td>
                                <td class="price-col"><span class="text-success">-@price($discount)</span></td>
                            </tr>
                        @endif

                        {{-- Shipping Methods --}}
                        <tr class="cart-subtotal">
                            <td><h4>Shipping</h4></td>
                            <td class="price-col">
                                @if($shippingMethods->count())
                                    @foreach($shippingMethods as $method)
                                        <div class="custom-control custom-radio mb-1">
                                            <input type="radio" class="custom-control-input" name="shipping_method"
                                                   id="shipping-{{ $method->id }}" value="{{ $method->id }}"
                                                   wire:model.live="shipping_method_id"
                                                   {{ $shipping_method_id == $method->id ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="shipping-{{ $method->id }}">
                                                {{ $method->name }}
                                                @if($method->type === 'free')
                                                    <span class="text-success">Free</span>
                                                @else
                                                    <span>@price($method->price)</span>
                                                @endif
                                                @if($method->estimated_days)
                                                    <small class="text-muted d-block">({{ $method->estimated_days }} days)</small>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <span>Free Shipping</span>
                                @endif
                            </td>
                        </tr>

                        <tr class="order-total">
                            <td><h4>Total</h4></td>
                            <td class="price-col"><span>@price($total)</span></td>
                        </tr>
                    </tfoot>
                </table>

                {{-- Payment Methods (Dynamic from Admin Settings) --}}
                <div class="payment-methods">
                    <h4 class="mb-2">Payment Method</h4>

                    @forelse($enabledPaymentMethods as $methodKey => $methodLabel)
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" class="custom-control-input" name="payment_method"
                                   id="payment-{{ $methodKey }}" value="{{ $methodKey }}"
                                   wire:model.live="payment_method"
                                   {{ $loop->first && !$payment_method ? 'checked' : '' }}>
                            <label class="custom-control-label" for="payment-{{ $methodKey }}">
                                <strong>{{ $methodLabel }}</strong>

                                {{-- COD instructions --}}
                                @if($methodKey === 'cod' && $codInstructions)
                                    <small class="d-block text-muted">{{ $codInstructions }}</small>
                                @elseif($methodKey === 'cod')
                                    <small class="d-block text-muted">Pay with cash upon delivery.</small>
                                @endif

                                {{-- Bank Transfer details --}}
                                @if($methodKey === 'bank_transfer' && $bankTransferDetails)
                                    <small class="d-block text-muted">{!! nl2br(e($bankTransferDetails)) !!}</small>
                                @elseif($methodKey === 'bank_transfer')
                                    <small class="d-block text-muted">Make your payment directly into our bank account.</small>
                                @endif

                                {{-- Stripe --}}
                                @if($methodKey === 'stripe')
                                    <small class="d-block text-muted">Pay securely with your credit or debit card.</small>
                                @endif

                                {{-- PayPal --}}
                                @if($methodKey === 'paypal')
                                    <small class="d-block text-muted">You will be redirected to PayPal to complete your payment.</small>
                                @endif
                            </label>
                        </div>

                        {{-- Stripe Card Element --}}
                        @if($methodKey === 'stripe' && $payment_method === 'stripe')
                            <div class="stripe-card-wrapper ml-4 mb-3 p-3 border rounded bg-light" id="stripe-card-container">
                                <div id="stripe-card-element" class="mb-2" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #fff;"></div>
                                <div id="stripe-card-errors" class="text-danger small"></div>
                            </div>
                        @endif
                    @empty
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            No payment methods are currently available. Please contact the store administrator.
                        </div>
                    @endforelse
                </div>

                <button type="button" class="btn btn-dark btn-place-order btn-block mt-3"
                        wire:click="placeOrder" wire:loading.attr="disabled"
                        {{ $processing ? 'disabled' : '' }}
                        @if(count($enabledPaymentMethods) === 0) disabled @endif>
                    <span wire:loading.remove wire:target="placeOrder">Place Order</span>
                    <span wire:loading wire:target="placeOrder">Processing...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Stripe JS (only loaded when Stripe is an enabled payment method) --}}
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

        // Mount on page load if stripe is already selected
        setTimeout(mountStripeCard, 500);

        // Re-mount when Livewire updates DOM
        Livewire.hook('morph.updated', () => {
            setTimeout(mountStripeCard, 200);
        });

        // Intercept placeOrder to create Stripe payment method first
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
