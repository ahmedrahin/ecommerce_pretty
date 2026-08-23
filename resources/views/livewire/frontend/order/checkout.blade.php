<section class="flat-spacing">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="tf-page-checkout mb-lg-0">
                    <!-- Coupon Section -->
                    <div class="wrap-coupon">
                        <h5 class="mb-12">Have a coupon? <span class="text-primary">Enter your code</span></h5>
                        <form>
                            <div class="ip-discount-code mb-0">
                                @if (empty($appliedCoupon))
                                    <input type="text" placeholder="Enter your code" wire:model="couponCode">
                                    <button class="tf-btn animate-btn" type="button" wire:click="applyCoupon">
                                        <span wire:loading.remove wire:target="applyCoupon">Apply Code</span>
                                        <span wire:loading wire:target="applyCoupon" class="formloader"></span>
                                    </button>
                                @else
                                    <input type="text" value="{{ $appliedCoupon['code'] }}" readonly>
                                    <button class="tf-btn animate-btn" type="button" wire:click="removeCoupon">
                                        <span wire:loading.remove wire:target="removeCoupon">Cancel</span>
                                        <span wire:loading wire:target="removeCoupon" class="formloader"></span>
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Main Checkout Form -->
                    <form class="tf-checkout-cart-main" wire:submit.prevent="order">
                        <!-- Customer Information -->
                        <div class="box-ip-checkout estimate-shipping">
                            <h2 class="title type-semibold">Customer Information</h2>
                            <div class="form_content">
                                <div class="cols tf-grid-layout sm-col-2">
                                    <fieldset>
                                        <input type="text" placeholder="Full Name*" wire:model="name"
                                               class="@error('name') error_border @enderror">
                                        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </fieldset>
                                    <fieldset>
                                        <input type="text" placeholder="Phone*" wire:model="phone"
                                               class="@error('phone') error_border @enderror">
                                        @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </fieldset>
                                </div>
                                <div class="cols tf-grid-layout sm-col-2">
                                    <fieldset>
                                        <input type="email" placeholder="Email*" wire:model="email"
                                               class="@error('email') error_border @enderror">
                                        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </fieldset>
                                    <fieldset>
                                        <input type="text" placeholder="Address*" wire:model="shipping_address"
                                               class="@error('shipping_address') error_border @enderror">
                                        @error('shipping_address') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </fieldset>
                                </div>

                                <div class="cols tf-grid-layout sm-col-2">
                                    {{-- <fieldset>
                                        <div class="tf-select">
                                            <select wire:model="district_id"
                                                    class="@error('district_id') error_border @enderror">
                                                <option value="">Select City</option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}">
                                                        {{ $district->name }} - {{ $district->bn_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('district_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                        </div>
                                    </fieldset> --}}

                                    <fieldset>
                                        <select wire:model="state_id" class="@error('state_id') error_border @enderror">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('state_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </fieldset>

                                    <fieldset>
                                        <input type="text" placeholder="Postal code" class="@error('zip_code') error_border @enderror" wire:model="zip_code">
                                        @error('zip_code') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </fieldset>
                                </div>

                                <textarea placeholder="Note about your order" style="height: 180px;" wire:model="note"></textarea>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="box-ip-payment">
                            <h2 class="title type-semibold">Choose Payment Option</h2>
                            {{-- <div class="payment-method-box" id="payment-method-box">
                                <div class="payment_accordion">
                                    <label for="cash-on" class="payment_check checkbox-wrap">
                                        <input type="radio" name="payment-method" class="tf-check-rounded style-2"
                                               id="cash-on" wire:model="payment_type" value="cod">
                                        <span class="pay-title">Cash On Delivery</span>
                                    </label>
                                </div>
                            </div> --}}
                            <div class="payment-method-box" id="payment-method-box">
                                <div class="payment_accordion">
                                    <label for="stripe" class="payment_check checkbox-wrap">
                                        <input type="radio" name="payment-method" class="tf-check-rounded style-2"
                                               id="stripe" wire:model="payment_type" value="stripe">
                                        <span class="pay-title">Pay with Stripe</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Terms & Submit -->
                        {{-- <div class="agree-text mb-3">
                            <label class="checkbox-wrap">
                                <input type="checkbox" name="agree" checked>
                                <span class="checkmark"></span>
                                I have read and agree to the
                                <a href="{{ route('terms') }}" target="_blank"><b>Terms & Conditions</b></a>,
                                <a href="{{ route('privacy.policy') }}" target="_blank"><b>Privacy Policy</b></a>,
                                and <a href="{{ route('refund.policy') }}" target="_blank"><b>Refund Policy</b></a>
                            </label>
                        </div> --}}

                        <div class="button_submit">
                            <button type="submit" class="tf-btn animate-btn w-100">
                                <span wire:loading.remove wire:target="order">Confirm Order</span>
                                <span wire:loading wire:target="order" class="formloader"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-5">
                <div class="fl-sidebar-cart sticky-top">
                    <div class="box-your-order">
                        <div class="d-flex align-items-center justify-content-between">
                            <h2 class="title type-semibold">Your Order</h2>
                            <a href="{{ route('cart') }}" class="text-primary" style="text-decoration: underline;">Update Cart</a>
                        </div>
                        <ul class="list-order-product">
                            @foreach ($cart as $item)
                                <li class="order-item">
                                    <a href="{{ route('product-details', $item['slug']) }}" class="img-prd">
                                        <img class="lazyload" src="{{ asset($item['image_url']) }}"
                                             data-src="{{ asset($item['image_url']) }}" alt="{{ $item['name'] }}">
                                    </a>
                                    <div class="infor-prd">
                                        <h6 class="prd_name">
                                            <a href="{{ route('product-details', $item['slug']) }}" class="link">
                                                {{ $item['name'] }}
                                            </a>
                                        </h6>
                                        @if (!empty($item['attributes_info']))
                                            <div class="prd_select text-small">
                                                @foreach ($item['attributes_info'] as $attr)
                                                    {{ $attr['name'] }}: {{ $attr['value'] }}
                                                    @if(!$loop->last) | @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="quantity">Qty: {{ $item['quantity'] }}</div>
                                    </div>
                                    <p class="price-prd h6">
                                        ${{ ($item['offer_price'] * $item['quantity']) }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                        <ul class="list-total">
                            <li class="total-item h6">
                                <span class="fw-bold text-black">Subtotal</span>
                                <span>${{ $this->getTotalAmount() }}</span>
                            </li>

                            @if (!empty($appliedCoupon))
                                <li class="total-item h6">
                                    <span class="fw-bold text-black">Coupon Discount</span>
                                    <span style="color:#ef4a23;">-${{ number_format($appliedCoupon['discount'], 2) }}</span>
                                </li>
                            @endif

                            <!-- Shipping Method -->
                            @if(!empty($shippingMethods))
                                <div class="box-ip-shipping pb-2" style="margin-bottom: 25px;border-bottom:1px solid var(--line);">
                                    <span class="fw-bold text-black mb-14 d-block mt-3">Shipping Method</span>
                                    @foreach($shippingMethods as $method)
                                        <label class="check-ship mb-12">
                                            <input type="radio"
                                                class="tf-check-rounded style-2 line-black"
                                                wire:model="selectedShippingMethodType"
                                                value="{{ $method['id'] }}">

                                            <span class="text h6">
                                                <span>{{ $method['name'] }}</span>
                                                <span class="price">${{ $method['rate'] }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                            
                        </ul>
                        <div class="last-total h5 fw-medium text-black">
                            <span>Total</span>
                            <span>${{ number_format($this->grandTotal(), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="ajax-loader" wire:loading wire:target="state_id">
        <div style="width: 100%;height:100%;display:flex;align-items:center;justify-content:center;">
            <div class="spinner"></div>
        </div>
    </div>
    
</section>
