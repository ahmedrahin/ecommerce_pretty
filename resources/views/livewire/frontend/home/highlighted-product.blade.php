<div class="product-section justify-content-center row cols-md-3 cols-sm-2 mt-2">
    @if (!empty($featured))
        <div class="mb-4">
            <h2 class="title mb-5">Featured Items</h2>
            <div class="swiper-container swiper-theme product-wrapper appear-animate d-flex"
                data-swiper-options="{
                                'slidesPerView':1,
                                'spaceBetween': 0
                                }">
                <div class="swiper-wrapper">
                    @foreach ($featured as $product)
                        <div class="swiper-slide product-wrap">
                            <div class="product-wrap">
                                <div class="product text-center position-relative">
                                    <figure class="product-media">
                                        <a href="{{ route('product-details', $product->slug) }}">
                                            <img src="{{ asset($product->thumb_image) }}" alt="Product" width="300"
                                                height="338" />
                                            @if ($product->back_image)
                                                <img src="{{ asset($product->back_image) }}" alt="Product"
                                                    width="300" height="338" />
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
                                            <a
                                                href="{{ route('product-details', $product->slug) }}">{{ Str::limit($product->name, 20, '...') }}</a>
                                        </h3>

                                        @if (config('website_settings.show_review') == true)
                                            @php
                                                $reviews = \App\Models\Review::where('product_id', $product->id)->get();
                                                $averageRating = round($reviews->avg('rating'), 1);
                                                $ratingPercentage = ($averageRating / 5) * 100;
                                            @endphp

                                            <div class="ratings-container">
                                                <div class="ratings-full">
                                                    <span class="ratings"
                                                        style="width: {{ $ratingPercentage }}%;"></span>
                                                    <span class="tooltiptext tooltip-top">{{ $averageRating }} out of
                                                        5</span>
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
                                                        style="font-size: 14px;padding-left:3px;">{{ $product->base_price }}৳
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
                        </div>
                    @endforeach
                </div>
                <button class="swiper-button-next"></button>
                <button class="swiper-button-prev"></button>
            </div>
        </div>
    @endif

    @if (!empty($selling))
        <div class="mb-4">
            <h2 class="title mb-5">Best Sellers</h2>
            <div class="swiper-container swiper-theme product-wrapper appear-animate d-flex"
                data-swiper-options="{
                                'nav': true,
                                'dots': false,
                                'slidesPerView':1,
                                'spaceBetween': 0
                                }">
                <div class="swiper-wrapper">
                    @foreach ($selling as $product)
                        <div class="swiper-slide product-wrap">
                            <div class="product-wrap">
                                <div class="product text-center position-relative">
                                    <figure class="product-media">
                                        <a href="{{ route('product-details', $product->slug) }}">
                                            <img src="{{ asset($product->thumb_image) }}" alt="Product" width="300"
                                                height="338" />
                                            @if ($product->back_image)
                                                <img src="{{ asset($product->back_image) }}" alt="Product"
                                                    width="300" height="338" />
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
                                            <a
                                                href="{{ route('product-details', $product->slug) }}">{{ Str::limit($product->name, 20, '...') }}</a>
                                        </h3>

                                        @if (config('website_settings.show_review') == true)
                                            @php
                                                $reviews = \App\Models\Review::where('product_id', $product->id)->get();
                                                $averageRating = round($reviews->avg('rating'), 1);
                                                $ratingPercentage = ($averageRating / 5) * 100;
                                            @endphp

                                            <div class="ratings-container">
                                                <div class="ratings-full">
                                                    <span class="ratings"
                                                        style="width: {{ $ratingPercentage }}%;"></span>
                                                    <span class="tooltiptext tooltip-top">{{ $averageRating }} out of
                                                        5</span>
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
                                                        style="font-size: 14px;padding-left:3px;">{{ $product->base_price }}৳
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
                        </div>
                    @endforeach
                </div>
                <button class="swiper-button-next"></button>
                <button class="swiper-button-prev"></button>
            </div>
        </div>
    @endif

    @if (!empty($newArrivales))
        <div class="mb-4">
            <h2 class="title mb-5">New Arrivals</h2>
            <div class="swiper-container swiper-theme product-wrapper appear-animate d-flex"
                data-swiper-options="{
                                'nav': true,
                                'dots': false,
                                'slidesPerView':1,
                                'spaceBetween': 0
                                }">
                <div class="swiper-wrapper">
                    @foreach ($newArrivales as $product)
                        <div class="swiper-slide product-wrap">
                            <div class="product-wrap">
                                <div class="product text-center position-relative">
                                    <figure class="product-media">
                                        <a href="{{ route('product-details', $product->slug) }}">
                                            <img src="{{ asset($product->thumb_image) }}" alt="Product" width="300"
                                                height="338" />
                                            @if ($product->back_image)
                                                <img src="{{ asset($product->back_image) }}" alt="Product"
                                                    width="300" height="338" />
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
                                            <a
                                                href="{{ route('product-details', $product->slug) }}">{{ Str::limit($product->name, 20, '...') }}</a>
                                        </h3>

                                        @if (config('website_settings.show_review') == true)
                                            @php
                                                $reviews = \App\Models\Review::where('product_id', $product->id)->get();
                                                $averageRating = round($reviews->avg('rating'), 1);
                                                $ratingPercentage = ($averageRating / 5) * 100;
                                            @endphp

                                            <div class="ratings-container">
                                                <div class="ratings-full">
                                                    <span class="ratings"
                                                        style="width: {{ $ratingPercentage }}%;"></span>
                                                    <span class="tooltiptext tooltip-top">{{ $averageRating }} out of
                                                        5</span>
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
                                                        style="font-size: 14px;padding-left:3px;">{{ $product->base_price }}৳
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
                        </div>
                    @endforeach
                </div>
                <button class="swiper-button-next"></button>
                <button class="swiper-button-prev"></button>
            </div>
        </div>
    @endif
</div>
