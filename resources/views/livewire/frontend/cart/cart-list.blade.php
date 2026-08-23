<div>

    <section class="cart-section">
        <div class="container">
            <h1 class="cart-title">Shopping Cart</h1>

            <div class="cart-wrapper">

                <!-- ── Cart Table ── -->
                <div class="cart-table-card">
                    <div class="table-scroll-wrap">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th class="col-action">Action</th>
                                    <th class="col-image">Image</th>
                                    <th class="col-name">Product Name</th>
                                    <th class="col-qty">Quantity</th>
                                    <th class="col-price">Unit Price</th>
                                    <th class="col-total">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $cartKey => $item)
                                <tr>
                                    <td class="col-action text-center">
                                        <button type="button" class="remove-btn"
                                            wire:click="removeItem('{{ $cartKey }}')">
                                            ✕
                                        </button>
                                    </td>

                                    <td class="col-image text-center">
                                        <a href="{{ route('product-details', $item['slug']) }}">
                                            <img src="{{ asset($item['image_url']) }}" alt="{{ $item['name'] }}"
                                                class="product-thumb" />
                                        </a>
                                    </td>

                                    <td class="col-name">
                                        <a href="{{ route('product-details', $item['slug']) }}" class="product-link">
                                            {{ Str::limit($item['name'],50) }}
                                        </a>
                                        @if (!empty($item['attributes_info']))
                                        <div class="product-attrs">
                                            @foreach ($item['attributes_info'] as $attr)
                                            <small>{{ $attr['name'] }}: {{ $attr['value'] }}@if(!$loop->last) —
                                                @endif</small>
                                            @endforeach
                                        </div>
                                        @endif
                                    </td>

                                    <td class="col-qty text-center">
                                        <div class="qty-stepper">
                                            <button type="button" class="qty-btn"
                                                wire:click="decrementQuantity('{{ $cartKey }}')">−</button>
                                            <input type="text" class="qty-input" min="1"
                                                wire:model.lazy="quantities.{{ $cartKey }}"
                                                wire:change="updateQuantities('{{ $cartKey }}', $event.target.value)"
                                                value="{{ $item['quantity'] }}">
                                            <button type="button" class="qty-btn"
                                                wire:click="incrementQuantity('{{ $cartKey }}')">+</button>
                                        </div>
                                    </td>

                                    <td class="col-price text-right">
                                        TK {{ format_price($item['offer_price']) }}
                                    </td>

                                    <td class="col-total text-right">
                                        TK {{ format_price($item['offer_price'] * $item['quantity']) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ── Cart Totals ── -->
                <div class="cart-totals-card">
                    <h2 class="totals-heading">Cart Totals</h2>

                    <div class="totals-row">
                        <span class="totals-label">Total Quantity</span>
                        <span class="totals-value">{{ array_sum(array_column($cart, 'quantity')) }}</span>
                    </div>

                    <div class="totals-row">
                        <span class="totals-label">Subtotal</span>
                        <span class="totals-value">TK {{ format_price($this->getTotalAmount()) }}</span>
                    </div>

                    <div class="totals-row totals-row--total">
                        <span class="totals-label">Total</span>
                        <span class="totals-value">TK {{ format_price($this->getTotalAmount()) }}</span>
                    </div>

                    @if( config('website_settings.guest_checkout') == 1 && Auth::check() )
                    <a href="{{ route('checkout') }}" class="btn-checkout">Proceed to Checkout</a>
                    @elseif( config('website_settings.guest_checkout') == 0 && !Auth::check() )
                    <button class="btn-checkout" onclick="message('warning', 'Please log in at first to checkout')">
                        Proceed to Checkout
                    </button>
                    @else
                    <a href="{{ route('checkout') }}" class="btn-checkout">Proceed to Checkout</a>
                    @endif

                    <a href="{{ url('/') }}" class="btn-continue">← Continue Shopping</a>
                </div>

            </div>
        </div>
    </section>
</div>
