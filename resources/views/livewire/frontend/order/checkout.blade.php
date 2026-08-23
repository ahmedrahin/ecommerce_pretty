
<div>
    <section class="checkout bg-bt-gray p-tb-15">
        <div class="container">
            <h1 class="page-title">Checkout</h1>

            {{-- Coupon Bar --}}
            <div class="coupon-bar" wire:click="$toggle('showCouponForm')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-4 0v2M8 7V5a2 2 0 0 0-4 0"/>
                </svg>
                Have a coupon? Click here to enter your code
            </div>
            <div class="coupon-expand {{ $showCouponForm ? 'open' : '' }}" id="couponExpand">
                <p>If you have a coupon code, please apply it below.</p>
                @if (empty($appliedCoupon))
                    <div class="coupon-row">
                        <input type="text" class="form-control" wire:model.defer="couponCode" placeholder="Coupon code" />
                        <button type="button" class="btn-yellow btncouopn" wire:click="applyCoupon" style="width: 140px;">
                            <span wire:loading.remove wire:target="applyCoupon">Apply coupon</span>
                            <span wire:loading wire:target="applyCoupon" class="formloader"></span>
                        </button>
                    </div>
                @else
                    <div class="coupon-row">
                        <input type="text" class="form-control" value="{{ $appliedCoupon['code'] }}" readonly />
                        <button type="button" class="btn-yellow btn-cancel btncouopn" wire:click="removeCoupon">
                            <span wire:loading.remove wire:target="removeCoupon">Remove coupon</span>
                            <span wire:loading wire:target="removeCoupon" class="formloader"></span>
                        </button>
                    </div>
                    <div class="coupon-applied-note">
                        ✓ Coupon "{{ $appliedCoupon['code'] }}" applied! You save TK {{ $appliedCoupon['discount'] }}.
                    </div>
                @endif
            </div>

            <form class="checkout-content" wire:submit.prevent="order">
                <div class="checkout-grid">

                    {{-- Left: Billing Details --}}
                    <div class="card">
                        <div class="card-title">Billing details</div>

                        <div class="form-group">
                            <label>Full Name <span class="req">*</span></label>
                            <input type="text" class="form-control @error('name') error-border @enderror"
                                wire:model="name" placeholder="Full name" />
                            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone <span class="req">*</span></label>
                                    <input type="tel" class="form-control @error('phone') error-border @enderror"
                                        wire:model="phone" />
                                    @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                             </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email address <span class="req">*</span></label>
                                    <input type="email" class="form-control @error('email') error-border @enderror"
                                        wire:model="email" />
                                    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Street address <span class="req">*</span></label>
                            <input type="text" class="form-control @error('shipping_address') error-border @enderror"
                                wire:model="shipping_address" placeholder="House number and street name" />
                            @error('shipping_address') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>District <span class="req">*</span></label>
                                    <select wire:model="district_id" class="form-control select2 @error('district_id') error-border @enderror">
                                        <option value="">Select an option… *</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }} - {{ $district->bn_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('district_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Postcode / ZIP <span class="opt-label">(optional)</span></label>
                                    <input type="text" class="form-control" wire:model="zip_code" placeholder="zip code" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom:0px;">
                            <label>Order notes <span class="opt-label">(optional)</span></label>
                            <textarea class="form-control" wire:model="note"
                                placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                        </div>
                    </div>

                    {{-- Right: Order Summary --}}
                    <div class="card">
                        <div class="card-title">Your order</div>

                        <div class="col-header">
                            <span>Product</span>
                            <span>Subtotal</span>
                        </div>
                        <hr class="divider"/>

                        @foreach ($cart as $item)
                            <div class="order-line">
                                <div>
                                    <div class="product-name">
                                        <a href="{{ route('product-details', $item['slug']) }}">{{ Str::limit($item['name'],50) }}</a>
                                    </div>
                                    @if (!empty($item['attributes_info']))
                                        <div class="attrs">
                                            @foreach ($item['attributes_info'] as $attr)
                                                <small>{{ $attr['name'] }}: {{ $attr['value'] }}@if(!$loop->last) · @endif</small>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="qty">× {{ $item['quantity'] }}</div>
                                </div>
                                <div class="amount">TK {{ format_price($item['offer_price'] * $item['quantity']) }}</div>
                            </div>
                        @endforeach

                        <div class="order-line">
                            <div class="label">Subtotal</div>
                            <div class="amount">TK {{ format_price($this->getTotalAmount(), 0) }}</div>
                        </div>

                        @if (!empty($appliedCoupon))
                            <div class="order-line discount-line">
                                <div class="label">Coupon Discount</div>
                                <div class="amount text-danger" style="padding:0;">-TK {{ $appliedCoupon['discount'] }}</div>
                            </div>
                        @endif

                        <div class="order-line">
                            <div class="label">Shipment</div>
                            <div class="amount" style="text-align:right;">
                                @if ($selectedShippingCharge)
                                    Flat rate:<br/>TK {{ $selectedShippingCharge }}
                                @else
                                    <span style="color:var(--muted)">Select below</span>
                                @endif
                            </div>
                        </div>

                        <div class="total-line">
                            <span>Total</span>
                            <span>TK {{ number_format($this->grandTotal(), 0) }}</span>
                        </div>

                        {{-- Shipping Method --}}
                        <div class="section-label" style="margin-top:14px;">Delivery Method</div>
                        <div class="payment-options">
                            @foreach ($shippingMethods as $method)
                                <label class="pay-option">
                                    <input type="radio" wire:model="selectedShippingMethodId" value="{{ $method->id }}" />
                                    <span class="pay-label">{{ $method->provider_name }}</span>
                                    <span class="ship-charge">TK {{ $method->provider_charge }}</span>
                                </label>
                            @endforeach
                        </div>

                         {{-- Payment Method --}}
                        <div class="section-label">Payment Method</div>
                        <div class="payment-options">
                            <label class="pay-option">
                                <input type="radio" name="payment_type" wire:model="payment_type" value="cod" />
                                <span class="pay-label">Cash on Delivery</span>
                            </label>
                        </div>

                        {{-- <div class="payment-options">
                            <label class="pay-option">
                                <input type="radio" name="payment_type" wire:model="payment_type" value="sslcommerz" />
                                <span class="pay-label">sslcommerz</span>
                            </label>
                        </div> --}}

                        <div class="terms-row">
                            <input type="checkbox" id="terms" name="agree" checked />
                            <label for="terms">
                                I have read and agree to the website
                                <a href="{{ route('terms') }}" target="_blank">Terms &amp; Conditions</a>,
                                <a href="{{ route('refund.policy') }}" target="_blank">Refund</a> and
                                <a href="{{ route('privacy.policy') }}" target="_blank">Privacy Policies</a>
                                <span class="req">*</span>
                            </label>
                        </div>

                        <button type="submit" class="btn-place-order btncouopn">
                            <span wire:loading.remove wire:target="order">Place order</span>
                            <span wire:loading wire:target="order" class="formloader"></span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </section>
</div>