<div class="offcanvas offcanvas-end popup-shopping-cart" id="shoppingCart" wire:ignore.self>
    <div class="canvas-wrapper">
        <div class="popup-header">
            <span class="title fw-semibold h4">Shopping cart</span>
            <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas"></span>
        </div>

        <div class="wrap">
            <div class="tf-mini-cart-wrap list-file-delete wrap-empty_text">
                <div class="tf-mini-cart-main">
                    <div class="tf-mini-cart-sroll">
                        <div class="tf-mini-cart-items">
                            @if (!empty($cart))
                                @foreach ($cart as $cartKey => $item)
                                    <div class="tf-mini-cart-item">
                                        <div class="tf-mini-cart-image">
                                            <img class="lazyload"
                                                 src="{{ asset($item['image_url']) }}"
                                                 alt="{{ $item['name'] }}">
                                        </div>

                                        <div class="tf-mini-cart-info">
                                            <div class="text-small text-main-2 sub">{{ $item['category'] ?? '' }}</div>

                                            <h6 class="title">
                                                <a href="{{ route('product-details', $item['slug']) }}"
                                                   class="link text-line-clamp-1">
                                                    {{ $item['name'] }}
                                                </a>
                                            </h6>

                                            {{-- Attributes --}}
                                            @if (!empty($item['attributes_info']))
                                                <div class="size">
                                                    @foreach ($item['attributes_info'] as $attr)
                                                        <div class="text-small text-main-2 sub">
                                                            {{ $attr['name'] }}: {{ $attr['value'] }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="h6 fw-semibold">
                                                    <span class="number">{{ $item['quantity'] }}x</span>
                                                    <span class="price text-primary tf-mini-card-price">
                                                        ${{ $item['offer_price'] }}
                                                    </span>
                                                </div>

                                                <i class="icon link icon-trash text-danger" style="cursor: pointer;" wire:click="removeItem('{{ $cartKey }}')"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="box-text_empty type-shop_cart">
                                    <div class="shop-empty_top">
                                        <span class="icon">
                                            <i class="icon-shopping-cart-simple"></i>
                                        </span>
                                        <h3 class="text-emp fw-normal">Your cart is empty</h3>
                                        <p class="h6 text-main">Your cart is currently empty. Let us assist you in finding the right product</p>
                                    </div>
                                    <div class="shop-empty_bot">
                                        <a href="{{ route('shop') }}" class="tf-btn animate-btn">Shopping</a>
                                        <a href="{{ route('homepage') }}" class="tf-btn style-line">Back to home</a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- Bottom Summary --}}
                @if (!empty($cart))
                    <div class="tf-mini-cart-bottom box-empty_clear">
                        <div class="tf-mini-cart-threshold">
                            <div class="text">
                                <h6 class="subtotal">
                                    Subtotal (<span class="prd-count">{{ array_sum(array_column($cart, 'quantity')) }}</span> items)
                                </h6>
                                <h4 class="text-primary total-price tf-totals-total-value">
                                    ${{ number_format($this->getTotalAmount(), 2) }}
                                </h4>
                            </div>
                        </div>

                        <div class="tf-mini-cart-bottom-wrap">
                            <div class="tf-mini-cart-view-checkout mb-0">
                                <a href="{{ route('cart') }}" class="tf-btn btn-white animate-btn animate-dark line">
                                    View cart
                                </a>

                                <a href="{{ route('checkout') }}"
                                   class="tf-btn animate-btn d-inline-flex bg-dark-2 w-100 justify-content-center">
                                    <span>Check out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
