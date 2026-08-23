<div class="flat-spacing each-list-prd">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-xl-8">
                <form>
                    <table class="tf-table-page-cart">
                        <thead>
                            <tr>
                                <th class="h6">Product</th>
                                <th class="h6">Price</th>
                                <th class="h6">Quantity</th>
                                <th class="h6">Total price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $cartKey => $item)
                            <tr class="tf-cart_item each-prd file-delete">
                                <td>
                                    <div class="cart_product">
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
                                                @if(!$loop->last) <span>|</span> @endif
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="cart_price h6 each-price" data-cart-title="Price">
                                    ${{ $item['offer_price'] }}
                                </td>
                                <td class="cart_quantity" data-cart-title="Quantity">
                                    <div class="wg-quantity">
                                        <button class="btn-quantity minus-quantity" type="button"
                                            wire:click="decrementQuantity('{{ $cartKey }}')">
                                            <i class="icon-minus fs-14"></i>
                                        </button>
                                        <input class="quantity-product" type="text" name="number" 
                                            wire:model.lazy="quantities.{{ $cartKey }}"
                                            wire:change="updateQuantities('{{ $cartKey }}', $event.target.value)"
                                            value="{{ $item['quantity'] }}">
                                        <button class="btn-quantity plus-quantity" type="button"
                                            wire:click="incrementQuantity('{{ $cartKey }}')">
                                            <i class="icon-plus fs-14"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="cart_total h6 each-subtotal-price" data-cart-title="Total">
                                    ${{ $item['offer_price'] * $item['quantity'] }}
                                </td>
                                <td class="cart_remove remove link" data-cart-title="Remove"
                                    wire:click="removeItem('{{ $cartKey }}')">
                                    <i class="icon icon-close"></i>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="col-xxl-3 col-xl-4">
                <div class="fl-sidebar-cart bg-white-smoke sticky-top">
                    <div class="box-order-summary">
                        <h4 class="title fw-semibold">Order Summary</h4>
                        <div class="subtotal h6 text-button d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold">Total Cart Items:</h6>
                            <span class="total">{{ count($cart) }}</span>
                        </div>
                        <div class="discount text-button d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold">Total Cart Qty:</h6>
                            <span class="total h6">{{ array_sum(array_column($cart, 'quantity')) }}</span>
                        </div>
                        
                        <h5 class="total-order d-flex justify-content-between align-items-center">
                            <span>Total</span>
                            <span class="total each-total-price">${{ $this->getTotalAmount() }}</span>
                        </h5>

                        <div class="list-ver">
                            @if( config('website_settings.guest_checkout') == 1 && Auth::check() )
                            <a href="{{ route('checkout') }}" class="tf-btn w-100 animate-btn">
                                Process to checkout
                                <i class="icon icon-arrow-right"></i>
                            </a>
                            @elseif( config('website_settings.guest_checkout') == 0 && !Auth::check() )
                            <button class="tf-btn w-100 animate-btn"
                                onclick="message('warning', 'Please log in at first to checkout')">
                                Process to checkout
                                <i class="icon icon-arrow-right"></i>
                            </button>
                            @else
                            <a href="{{ route('checkout') }}" class="tf-btn w-100 animate-btn">
                                Process to checkout
                                <i class="icon icon-arrow-right"></i>
                            </a>
                            @endif

                            <a href="{{ route('shop') }}" class="tf-btn btn-white animate-btn animate-dark w-100">
                                Continue shopping
                                <i class="icon icon-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>