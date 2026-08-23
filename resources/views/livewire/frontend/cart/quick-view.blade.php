<div class="modal theme-modal fade" id="quick-view" tabindex="-1" role="dialog" aria-modal="true" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <button title="Close (Esc)" type="button" data-bs-dismiss="modal" aria-label="Close" class="mfp-close btn-close"></button>
            @if ($on)
                <div class="product product-single product-popup">

                    <div class="row gutter-lg">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="product-gallery product-gallery-sticky">
                                <div class="swiper-container product-single-swiper swiper-theme nav-inner">
                                    <div class="swiper-wrapper row cols-1 gutter-no">
                                        <div class="swiper-slide">
                                            <figure class="product-image">
                                                <img src="{{ asset($product->thumb_image) }}" width="800"
                                                    height="900">
                                            </figure>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 overflow-hidden p-relative">
                            <div class="product-details scrollable pl-0">
                                <h2 class="product-title">
                                    <a href="{{ route('product-details', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h2>
                                <div class="product-bm-wrapper">
                                    @if ($product->brand_id && $product->brand)
                                        <figure class="brand">
                                            <img src="{{ asset($product->brand->image) }}" alt="Brand" width="102"
                                                height="48" />
                                        </figure>
                                    @endif
                                    <div class="product-meta">
                                        @if ($product->category_id && $product->category)
                                            <div class="product-categories mb-2">
                                                Category:
                                                <span class="product-category"><a
                                                        href="{{ url('/shop') }}?categories[0]={{ $product->category_id }}">{{ $product->category->name }}</a></span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <hr class="product-divider">

                                <div class="product-price">
                                    {{ $product->offer_price }}৳
                                    @if ($product->discount_option == 2 || $product->discount_option == 3)
                                        <del class="text-danger opacity-80"
                                            style="font-size: 14px;padding-left:3px;">{{ $product->base_price }}৳
                                        </del>
                                    @endif
                                </div>

                                @php
                                    $reviews = \App\Models\Review::where('product_id', $product->id)->get();
                                    $averageRating = round($reviews->avg('rating'), 1);
                                    $ratingPercentage = ($averageRating / 5) * 100;
                                @endphp

                                <div class="ratings-container">
                                    <div class="ratings-full">
                                        <span class="ratings" style="width: {{ $ratingPercentage }}%;"></span>
                                        <span class="tooltiptext tooltip-top">{{ $averageRating }}</span>
                                    </div>
                                    <a href="#" class="rating-reviews">({{ $reviews->count() }} Reviews)</a>
                                </div>

                                @if (!is_null($product->short_description) && $product->short_description !== '<p><br></p>')
                                    <div class="product-short-desc">
                                        {!! $product->short_description !!}
                                    </div>
                                @endif

                                <hr class="product-divider">

                                @php
                                    $productStocks = $product->productStock ?? collect();
                                    $attributesList = $attributes->keyBy('id');
                                    $attributesValuesList = $attributesValues->keyBy('id');
                                    $groupedAttributes = [];

                                    $singleVariationStocks = $productStocks->filter(function ($productStock) {
                                        return $productStock->attributeOptions->count() === 1;
                                    });
                                @endphp

                                @if ($singleVariationStocks->isNotEmpty())
                                    @foreach ($singleVariationStocks as $productStock)
                                        @foreach ($productStock->attributeOptions as $attributeOption)
                                            @php
                                                $groupedAttributes[$attributeOption->attribute_id][] =
                                                    $attributesValuesList[$attributeOption->attribute_value_id] ?? '';
                                            @endphp
                                        @endforeach
                                    @endforeach

                                    {{-- Product Color Swatch --}}
                                    @php
                                        $colorAttribute = $attributesList->firstWhere('attr_name', 'Color');
                                        $colorValues = $colorAttribute
                                            ? $groupedAttributes[$colorAttribute->id] ?? []
                                            : [];
                                    @endphp

                                    @if (!empty($colorValues))
                                        <div class="product-form product-variation-form product-color-swatch">
                                            <label>Color:</label>
                                            <div class="d-flex align-items-center product-variations">
                                                @foreach ($colorValues as $color)
                                                    <a href="javascript:void(0)"
                                                        class="color {{ $color->attr_value == $selectedColor ? 'active' : '' }}"
                                                        style="background-color: {{ $color->option ?: $color->attr_value }};"
                                                        data-color="{{ $color->attr_value }}">
                                                    </a>
                                                @endforeach
                                            </div>
                                            @if ($colorError)
                                                <div class="text-danger variant-error mt-1">{{ $colorError }}</div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Product Size Swatch --}}
                                    @php
                                        $sizeAttribute = $attributesList->firstWhere('attr_name', 'Size');
                                        $sizeValues = $sizeAttribute
                                            ? $groupedAttributes[$sizeAttribute->id] ?? []
                                            : [];
                                    @endphp

                                    @if (!empty($sizeValues))
                                        <div class="product-form product-variation-form product-size-swatch">
                                            <label class="mb-1">{{ $sizeAttribute->attr_name }}:</label>
                                            <div class="flex-wrap d-flex align-items-center product-variations">
                                                @foreach ($sizeValues as $value)
                                                    <a href="javascript:void(0)"
                                                        class="size {{ $value->attr_value == $selectedSize ? 'active' : '' }}"
                                                        data-size="{{ $value->attr_value }}">
                                                        {{ $value->attr_value }}
                                                    </a>
                                                @endforeach
                                            </div>


                                            @if ($sizeError)
                                                <div class="text-danger variant-error mt-1">{{ $sizeError }}</div>
                                            @endif
                                        </div>
                                    @endif
                                @endif

                                <div class="product-form">
                                    @if ($product->quantity > 0)
                                        <div class="product-qty-form">
                                            <div class="input-group quantity-box">
                                                <input class="quntity-filed form-control" type="number"
                                                    wire:model="quantity" min="1"
                                                    data-quantity="{{ $product->quantity }}">
                                                <button class="quantity-plus w-icon-plus plus"></button>
                                                <button class="quantity-minus w-icon-minus minus"></button>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($product->quantity > 0)
                                        <button class="btn btn-primary btn-cart" wire:click="addToCart">
                                            <i class="w-icon-cart"></i>
                                            <span>Add to Cart</span>
                                        </button>
                                    @else
                                        <button class="btn btn-primary btn-cart disabled">
                                            Out of stock!
                                        </button>
                                    @endif

                                </div>


                            </div>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>

</div>
