<div>
    @if (!empty($expireProducts) && $expireProducts->count() > 0)
        <div class="title-link-wrapper title-deals after-none appear-animate">
            <h2 class="title">Limited Time Deals</h2>
            <div
                class="product-countdown-container d-flex font-size-sm text-white bg-dark br-xs align-items-center mr-auto mt-1 mb-1">
                <label>Offer Ends in: </label>
                <div class="product-countdown countdown-compact ml-1 font-weight-bold" data-until="+72h"
                    data-relative="true" data-compact="true" data-format="HMS">00:00:00</div>
            </div>
        </div>
        <div class="swiper-container swiper-theme product-deals-wrapper appear-animate"
            data-swiper-options="{
                            'spaceBetween': 20,
                            'slidesPerView': 2,
                            'breakpoints': {
                                '576': {
                                    'slidesPerView': 3
                                },
                                '768': {
                                    'slidesPerView': 4
                                },
                                '992': {
                                    'slidesPerView': 5
                                }
                            }}">
            <div class="swiper-wrapper row cols-lg-5 cols-md-4 cols-sm-3 cols-2">
                @foreach ($expireProducts as $product)
                    <div class="swiper-slide product-wrap">
                        <div class="product text-center position-relative">
                            <figure class="product-media">
                                <a href="{{ route('product-details', $product->slug) }}">
                                    <img src="{{ asset($product->thumb_image) }}" alt="Product" width="300"
                                        height="338" />
                                    @if ($product->back_image)
                                        <img src="{{ asset($product->back_image) }}" alt="Product" width="300"
                                            height="338" />
                                    @endif
                                </a>

                            </figure>

                            <div class="product-details">
                                @if ($product->category_id)
                                    <div class="product-cat">
                                        <a
                                            href="{{ url('/shop') }}?categories[0]={{ $product->category_id }}">{{ $product->category->name ?? '' }}</a>
                                    </div>
                                @endif
                                <h3 class="product-name">
                                    <a href="{{ route('product-details', $product->slug) }}">
                                        {{ Str::limit($product->name, 20, '...') }}
                                    </a>
                                </h3>

                                @if (config('website_settings.show_review') == true)
                                    @php
                                        $reviews = \App\Models\Review::where('product_id', $product->id)->get();
                                        $averageRating = round($reviews->avg('rating'), 1);
                                        $ratingPercentage = ($averageRating / 5) * 100;
                                    @endphp

                                    <div class="ratings-container">
                                        <div class="ratings-full">
                                            <span class="ratings" style="width: {{ $ratingPercentage }}%;"></span>
                                            <span class="tooltiptext tooltip-top">{{ $averageRating }} out of 5</span>
                                        </div>
                                        <span class="rating-reviews">
                                            ({{ $reviews->count() }} reviews)
                                        </span>
                                    </div>
                                @endif

                                <div class="product-pa-wrapper">
                                    <div class="product-price">
                                        {{ $product->offer_price }}৳
                                        @if ($product->discount_option == 2 || $product->discount_option == 3)
                                            <del class="text-danger opacity-80"
                                                style="font-size: 14px;padding-left:3px;">
                                                {{ $product->base_price }}৳
                                            </del>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($product->discount_option == 2 || $product->discount_option == 3)
                            @php
                                $discountPercentage = round(($product->discount_amount / $product->base_price) * 100);
                            @endphp
                            <span class="discountvalue">-{{ $discountPercentage }}%</span>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    @endif


    @if (!empty($offerProducts) && $offerProducts->count() > 0)
        <div class="special-offer">
            <div class="container">
                <h2 class="title mt-3 mb-5 appear-animate fadeIn appear-animation-visible"
                    style="animation-duration: 1.2s;">
                    Special Offers
                </h2>
                <div class="bg-white">

                    <!-- End of Banner-wrapper -->
                    <div class="swiper-container swiper-theme product-wrapper appear-animate mb-1"
                        data-swiper-options="{
                                'spaceBetween': 20,
                                'slidesPerView': 2,
                                'breakpoints': {
                                    '576': {
                                        'slidesPerView': 3
                                    },
                                    '768': {
                                        'slidesPerView': 4
                                    },
                                    '992': {
                                        'slidesPerView': 5
                                    }
                                }
                                }">
                        <div class="swiper-wrapper row cols-lg-5 cols-md-4 cols-sm-3 cols-2 offer-products">
                            @foreach ($offerProducts as $product)
                                <div class="swiper-slide product-wrap">
                                    <div class="product text-center position-relative">
                                        <figure class="product-media">
                                            <a href="{{ route('product-details', $product->slug) }}">
                                                <img src="{{ asset($product->thumb_image) }}" alt="Product"
                                                    width="300" height="338" />
                                                @if ($product->back_image)
                                                    <img src="{{ asset($product->back_image) }}" alt="Product"
                                                        width="300" height="338" />
                                                @endif
                                            </a>

                                        </figure>

                                        <div class="product-details">
                                            {{-- @if ($product->category_id)
                                                <div class="product-cat">
                                                    <a
                                                        href="{{ url('/shop') }}?categories[0]={{ $product->category_id }}">{{ $product->category->name }}</a>
                                                </div>
                                            @endif --}}
                                            <h3 class="product-name">
                                                <a href="{{ route('product-details', $product->slug) }}">
                                                    {{ Str::limit($product->name, 20, '...') }}
                                                </a>
                                            </h3>


                                            <div class="product-pa-wrapper">
                                                <div class="product-price">
                                                    {{ number_format($product->offer_price,0) }}৳
                                                    @if ($product->discount_option == 2 || $product->discount_option == 3)
                                                        <del class="text-danger opacity-80"
                                                            style="font-size: 14px;padding-left:3px;">
                                                            {{ number_format($product->base_price,0) }}৳
                                                        </del>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($product->discount_option == 2 || $product->discount_option == 3)
                                        @php
                                            $discountPercentage = round(
                                                ($product->discount_amount / $product->base_price) * 100,
                                            );
                                        @endphp
                                        <span class="discountvalue">-{{ $discountPercentage }}%</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- End of Carousel -->
                </div>
            </div>
        </div>
    @endif

</div>
