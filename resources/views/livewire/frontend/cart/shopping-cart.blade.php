<div class="drawer m-cart" id="m-cart" wire:ignore.self>
    <div class="title">
        <p><i class="bi bi-cart-plus-fill" style="font-size: 20px;padding-right: 6px;"></i> YOUR CART</p>
        <span class="mc-toggler loaded close"><i class="bi bi-x material-icons" style="font-size: 26px;line-height: 52px;"></i></span>
    </div>
    <div class="content">
        @if (!empty($cart))
            @foreach ($cart as $cartKey => $item)
                <div class="item">
                    <div class="image">
                        <a href="{{ route('product-details', $item['slug']) }}">
                            <img src="{{ asset($item['image_url']) }}" width="47" height="47">
                        </a>
                    </div>
                    <div class="info">
                        <div class="name">
                            <a href="{{ route('product-details', $item['slug']) }}" style="color:#081621;">
                                {{ Str::limit($item['name'], 50) }}
                            </a>
                        </div>

                        {{-- Show attributes if available --}}
                        @if (!empty($item['attributes_info']))
                            <div class="cart-attributes" style="font-size: 13px; color: #666;">
                                @foreach ($item['attributes_info'] as $attr)
                                    <div><strong>{{ $attr['name'] }}:</strong> {{ $attr['value'] }}</div>
                                @endforeach
                            </div>
                        @endif
                        
                        <span class="total" style="color:var(--s-primary);">TK{{ format_price($item['offer_price'] * $item['quantity']) }}</span>
                    </div>

                    <div class="remove text-danger" wire:click="removeItem('{{ $cartKey }}')" title="Remove">
                        <i class="bi bi-x-circle material-icons"></i>
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-cart">
                <h4 style="margin-bottom: 10px;">Your Cart is Empty</h4>
                <a href="{{ route('homepage') }}" class="btn submit">Continue Shopping</a>
            </div>
        @endif
    </div>

    @if (!empty($cart))
        <div class="footer">

            <div class="total ">
                {{-- <div class="title" style="text-align: start;padding-left:5px;">Total Quantity</div> --}}
                {{-- <div class="amount">{{ array_sum(array_column($cart, 'quantity')) }}</div> --}}
            </div>
            <div class="total" style="padding-bottom: 77px;">
                <div class="title" style="text-align: start;padding-left:5px;">Subtotal:</div>
                <div class="amount">TK {{ number_format($this->getTotalAmount(), 2) }}</div>
            </div>

            <div class="checkout-btn">
                <a href="{{ route('cart') }}">
                    <button class="btn submit" style="background:black;box-shadow: 0 50px rgba(0, 0, 0, 0.2) inset;">View Cart</button>
                </a>
                @if (config('website_settings.guest_checkout') == 1 && Auth::check())
                    <a href="{{ route('checkout') }}">
                        <button class="btn submit">Checkout</button>
                    </a>
                @elseif(config('website_settings.guest_checkout') == 0 && !Auth::check())
                    <a href="javascript:;">
                        <button class="btn submit"
                        onclick="message('warning', 'Please log in at first to checkout')">Checkout</button>
                    </a>
                @else
                    <a href="{{ route('checkout') }}">
                        <button class="btn submit">Checkout</button>
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
