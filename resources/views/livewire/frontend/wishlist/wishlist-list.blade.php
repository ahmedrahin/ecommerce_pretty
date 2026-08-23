
 <div class="flat-spacing">
    <div class="container">
        <div class="tf-grid-layout tf-col-2 md-col-3 xl-col-4 wrapper-wishlist">
            @if (!$wishlistItems->isEmpty())
                @foreach ($wishlistItems as $item)
                    <div class="card-product grid style-2">
                        <div class="card-product_wrapper">
                            <a href="{{ route('product-details', $item->product->slug) }}" class="product-img">
                                <img class="lazyload img-product"
                                    src="{{ asset($item->product->thumb_image ?? 'frontend/images/noimg.jpg') }}"
                                    data-src="{{ asset($item->product->thumb_image ?? 'frontend/images/noimg.jpg') }}"
                                    alt="{{ $item->product->name }}">
                            </a>
                            <span class=" box-icon" wire:click="removeFromWishlist({{ $item->id }})">
                                <i class="icon icon-trash"></i>
                            </span>
                        </div>

                        {{-- Product Info --}}
                        <div class="card-product_info">
                            <a href="{{ route('product-details', $item->product->slug) }}" class="name-product h4 link">
                                {{ $item->product->name }}
                            </a>

                            <div class="price-wrap">
                                <span class="price-new h6">${{ format_price($item->product->offer_price) }}</span>
                                @if ($item->product->discount_option != 1)
                                    <span class="price-old h6 fw-normal">${{ format_price($item->product->base_price) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="tf-wishlist-empty text-center">
                    <p class="text-notice text-danger">NO PRODUCTS WERE ADDED TO THE WISHLIST.</p>
                    <a href="{{ route('shop') }}" class="tf-btn animate-btn btn-fill btn-back-shop">BACK TO SHOPPING</a>
                </div>
            @endif
        </div>
    </div>
</div>