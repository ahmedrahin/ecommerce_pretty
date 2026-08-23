<div class="tab-content">

    <div class="tab-pane fade show active" id="new-product" role="tabpanel" tabindex="0">

        <div class="row-cols-lg-5 row-cols-md-3 row-cols-2 grid-section view-option row g-3 g-xl-4 mt-0" >

            @if( !$featuredProducts->isEmpty() )
                @foreach ($featuredProducts as $product)
                    <div class="mt-2">
                        <div class="product-box-3">
                            <div class="img-wrapper">
                            <div class="label-block">
                                @if(in_array($product->id, $wishlist))
                                    <button class="label-2 wishlist-exist" style="border: none;" wire:click="$emit('removeFromWishlist', {{ $product->id }})">
                                        <i class="fa fa-heart " aria-hidden="true" style="color: #ff00008a;"></i>
                                    </button>
                                @else
                                    <button class="label-2 wishlist-icon" style="border: none;" wire:click="$emit('get_id', {{ $product->id }})">
                                        <i class="fa-regular fa-heart" data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i>
                                    </button>
                                @endif
                            </div>

                                {{-- product thumb or back image --}}
                                <div class="product-image {{ !is_null($product->back_image) ? 'has-back-image' : '' }}">
                                    <a href="{{route('product-details', $product->slug)}}">
                                        <img class="product-thumb" src="{{ asset($product->thumb_image ?? 'frontend/images/blank.jpg') }}" alt="product">
                                        @if(!is_null($product->back_image))
                                            <img class="product-back" src="{{ asset($product->back_image) }}" alt="product-back">
                                        @endif
                                    </a>
                                </div>

                            </div>
                            <div class="product-detail">

                                <div class="rating p-0">
                                    @php
                                        $reviews = App\Models\Review::where('product_id', $product->id)->get();
                                        $averageRating = $reviews->avg('rating');
                                        $averageRating = round($reviews->avg('rating'), 1);
                                        $fullStars = floor($averageRating);
                                        $halfStar = ($averageRating - $fullStars) >= 0.5 ? 1 : 0;
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                    @endphp

                                    <ul class="">
                                        <!-- Full stars -->
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <li><i class="fa-solid fa-star"></i></li>
                                        @endfor

                                        <!-- Half star if needed -->
                                        @if ($halfStar)
                                            <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                        @endif

                                        <!-- Empty stars -->
                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <li><i class="fa-regular fa-star"></i></li>
                                        @endfor

                                        <!-- Review count -->
                                        <li><span>({{ $reviews->count() }})</span></li>
                                    </ul>

                                </div>


                                <a href="{{route('product-details', $product->slug)}}">
                                    <h6 style="font-weight: 700;">{{ Str::limit($product->name, 17, '...') }}</h6>
                                </a>

                                <p>
                                    {{$product->offer_price}}৳
                                    @if( $product->discount_option == 2 || $product->discount_option == 3 )
                                        <del class="text-danger opacity-80">
                                            {{$product->base_price}}৳
                                        </del>
                                        @php
                                            $discountPercentage = round((($product->discount_amount) / $product->base_price) * 100);
                                        @endphp
                                        <span class="discountvalue">-{{ $discountPercentage }}%</span>
                                    @endif
                                </p>
                            </div>

                            @if( config('website_settings.buy_now_button') )
                                <div>
                                    @if( $product->quantity > 0 )
                                        <button class="button btn btn_black btn-buy quickview" data-bs-toggle="modal" data-bs-target="#quick-view" data-product-id="{{$product->id}}"> <i class="fa fa-cart-plus"></i> Buy Now</button>
                                    @else
                                        <button class="button btn btn_black btn-buy stock-out" disabled>Out of stock!!</button>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>

                @endforeach
            @else
                    <div style="color: #ff00008a;font-size: 20px;font-weight: 600;">No products found.</div>
            @endif

        </div>

    </div>

    <div class="tab-pane fade" id="featured-product" role="tabpanel" tabindex="0">
        <div class="row-cols-lg-5 row-cols-md-3 row-cols-2 grid-section view-option row g-3 g-xl-4 mt-0" >

            @if( !$offerProduct->isEmpty() )
                @foreach ($offerProduct as $product)
                    <div class="mt-2">
                        <div class="product-box-3">
                            <div class="img-wrapper">
                            <div class="label-block">
                                @if(in_array($product->id, $wishlist))
                                    <button class="label-2 wishlist-exist" style="border: none;" wire:click="$emit('removeFromWishlist', {{ $product->id }})">
                                        <i class="fa fa-heart " aria-hidden="true" style="color: #ff00008a;"></i>
                                    </button>
                                @else
                                    <button class="label-2 wishlist-icon" style="border: none;" wire:click="$emit('get_id', {{ $product->id }})">
                                        <i class="fa-regular fa-heart" data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i>
                                    </button>
                                @endif
                            </div>

                                {{-- product thumb or back image --}}
                                <div class="product-image {{ !is_null($product->back_image) ? 'has-back-image' : '' }}">
                                    <a href="{{route('product-details', $product->slug)}}">
                                        <img class="product-thumb" src="{{ asset($product->thumb_image ?? 'frontend/images/blank.jpg') }}" alt="product">
                                        @if(!is_null($product->back_image))
                                            <img class="product-back" src="{{ asset($product->back_image) }}" alt="product-back">
                                        @endif
                                    </a>
                                </div>

                            </div>
                            <div class="product-detail">

                                <div class="rating p-0">
                                    @php
                                        $reviews = App\Models\Review::where('product_id', $product->id)->get();
                                        $averageRating = $reviews->avg('rating');
                                        $averageRating = round($reviews->avg('rating'), 1);
                                        $fullStars = floor($averageRating);
                                        $halfStar = ($averageRating - $fullStars) >= 0.5 ? 1 : 0;
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                    @endphp

                                    <ul class="">
                                        <!-- Full stars -->
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <li><i class="fa-solid fa-star"></i></li>
                                        @endfor

                                        <!-- Half star if needed -->
                                        @if ($halfStar)
                                            <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                        @endif

                                        <!-- Empty stars -->
                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <li><i class="fa-regular fa-star"></i></li>
                                        @endfor

                                        <!-- Review count -->
                                        <li><span>({{ $reviews->count() }})</span></li>
                                    </ul>

                                </div>


                                <a href="{{route('product-details', $product->slug)}}">
                                    <h6 style="font-weight: 700;">{{ Str::limit($product->name, 22, '...') }}</h6>
                                </a>

                                <p>
                                    {{$product->offer_price}}৳
                                    @if( $product->discount_option == 2 || $product->discount_option == 3 )
                                        <del class="text-danger opacity-80">
                                            {{$product->base_price}}৳
                                        </del>
                                        @php
                                            $discountPercentage = round((($product->discount_amount) / $product->base_price) * 100);
                                        @endphp
                                        <span class="discountvalue">-{{ $discountPercentage }}%</span>
                                    @endif
                                </p>
                            </div>

                            @if( config('website_settings.buy_now_button') )
                                <div>
                                    @if( $product->quantity > 0 )
                                        <button class="button btn btn_black btn-buy quickview" data-bs-toggle="modal" data-bs-target="#quick-view" data-product-id="{{$product->id}}"> <i class="fa fa-cart-plus"></i> Buy Now</button>
                                    @else
                                        <button class="button btn btn_black btn-buy stock-out" disabled>Out of stock!!</button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                @endforeach
            @else
                    <div style="color: #ff00008a;font-size: 20px;font-weight: 600;">No products found.</div>
            @endif

        </div>
    </div>

  </div>
