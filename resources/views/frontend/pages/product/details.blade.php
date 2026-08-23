@extends('frontend.layout.app')

@section('page-title')
    {{ $product->name }}
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('frontend/css/drift-basic.min.css') }}">
    <link rel="stylesheet" href="{{asset('frontend/css/photoswipe.css')  }}">
    <style>
        .s-page-title{
            padding-top: 110px !important;
        }
        .s-page-title.style-2 .content{
            padding-bottom: 40px;
        }
        .pagination{
            justify-content: center !important;
            gap: 10px !important;
        }
        .selectrating {
            border: none;
            display: inline-flex;
            flex-direction: row-reverse; 
            margin-top: 10px;
        }

        .selectrating > input {
            display: none;
        }

        .selectrating > label:before {
        margin: 5px;
        font-size: 24px;
            font-family: FontAwesome;
            display: inline-block;
            content: "\f005";
        }

        .selectrating > label {
            color: #ddd;
            cursor: pointer;
        }

        /* Highlight stars on hover and selection */
        .selectrating > input:checked ~ label,
        .selectrating > label:hover,
        .selectrating > label:hover ~ label {
            color: #EF9122;
        }
    </style>
    <style>
        .btn-quantity .icon {
            pointer-events: none;
        }

        /* Visual for disabled buttons */
        .btn-quantity:disabled {
            cursor: not-allowed;
            opacity: 0.6;
            pointer-events: none;
        }

        .btn-quantity {
            cursor: pointer;
        }
    </style>

@endsection


@section('body-content')

    <section class="s-page-title style-2" style="padding-top: 30px !important;">
        <div class="container">
            <div class="content">
                <ul class="breadcrumbs-page">
                    <li><a href="{{ url('/') }}" class="h6 link">Home</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li><a href="{{ route('shop') }}" class="h6 link">Shop</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li>
                        <h6 class="current-page fw-normal">{{ $product->name }}</h6>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Product Main -->
    <section class="flat-single-product flat-spacing-3">
        <div class="tf-main-product section-image-zoom">
            <div class="container">
                <div class="row">
                    <!-- Product Images -->
                    <div class="col-md-6">
                        <div class="tf-product-media-wrap sticky-top">
                            <div class="product-thumbs-slider">
                                <div dir="ltr" class="swiper tf-product-media-thumbs other-image-zoom" data-direction="vertical" data-preview="4.7">
                                    <div class="swiper-wrapper stagger-wrap">

                                        {{-- Main Thumbnail --}}
                                        <div class="swiper-slide stagger-item">
                                            <div class="item">
                                                <img class="lazyload" 
                                                    data-src="{{ asset($product->thumb_image) }}"
                                                    src="{{ asset($product->thumb_image) }}" 
                                                    alt="{{ $product->name }}">
                                            </div>
                                        </div>

                                        {{-- Gallery Images --}}
                                        @foreach ($product->galleryImages ?? [] as $gallery)
                                            <div class="swiper-slide stagger-item">
                                                <div class="item">
                                                    <img class="lazyload" 
                                                        data-src="{{ asset($gallery->image) }}"
                                                        src="{{ asset($gallery->image) }}" 
                                                        alt="{{ $product->name }}">
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- Variation Images --}}
                                        @php
                                            $variationImages = $product->productStock()->whereNotNull('image')->pluck('image');
                                        @endphp

                                        @foreach ($variationImages as $vImage)
                                            <div class="swiper-slide stagger-item">
                                                <div class="item">
                                                    <img class="lazyload" 
                                                        data-src="{{ asset($vImage) }}"
                                                        src="{{ asset($vImage) }}" 
                                                        alt="{{ $product->name }} Variation">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flat-wrap-media-product">
                                    <div dir="ltr" class="swiper tf-product-media-main" id="gallery-swiper-started">
                                        <div class="swiper-wrapper">

                                            {{-- Main Image --}}
                                            <div class="swiper-slide">
                                                <a href="{{ asset($product->thumb_image) }}" target="_blank" class="item"
                                                    data-pswp-width="860px" data-pswp-height="1146px">
                                                    <img class="tf-image-zoom lazyload"
                                                        data-zoom="{{ asset($product->thumb_image) }}"
                                                        data-src="{{ asset($product->thumb_image) }}"
                                                        src="{{ asset($product->thumb_image) }}"
                                                        alt="{{ $product->name }}">
                                                </a>
                                            </div>

                                            {{-- Gallery Images --}}
                                            @foreach ($product->galleryImages ?? [] as $gallery)
                                                <div class="swiper-slide">
                                                    <a href="{{ asset($gallery->image) }}" target="_blank" class="item"
                                                        data-pswp-width="860px" data-pswp-height="1146px">
                                                        <img class="tf-image-zoom lazyload"
                                                            data-zoom="{{ asset($gallery->image) }}"
                                                            data-src="{{ asset($gallery->image) }}"
                                                            src="{{ asset($gallery->image) }}"
                                                            alt="{{ $product->name }}">
                                                    </a>
                                                </div>
                                            @endforeach

                                            {{-- Variation Images --}}
                                            @foreach ($variationImages as $vImage)
                                                <div class="swiper-slide">
                                                    <a href="{{ asset($vImage) }}" target="_blank" class="item"
                                                        data-pswp-width="860px" data-pswp-height="1146px">
                                                        <img class="tf-image-zoom lazyload"
                                                            data-zoom="{{ asset($vImage) }}"
                                                            data-src="{{ asset($vImage) }}"
                                                            src="{{ asset($vImage) }}"
                                                            alt="{{ $product->name }} Variation">
                                                    </a>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Product Images -->

                    <!-- Product Info -->
                    <div class="col-md-6">
                        <div class="tf-product-info-wrap position-relative">
                            <div class="tf-zoom-main sticky-top"></div>
                            <div class="tf-product-info-list other-image-zoom">
                                <h2 class="product-info-name">{{ $product->name }}</h2>
                                <div class="product-info-meta">
                                    @php
                                        $averageRating = round($product->reviews->avg('rating'), 1);
                                    @endphp

                                    <div class="rating">
                                        <div class="d-flex gap-4">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @php
                                                    $isFull = $averageRating >= $i;
                                                    $isHalf = !$isFull && $averageRating > ($i - 1) && $averageRating < $i;
                                                @endphp

                                                @if ($isFull)
                                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="#EF9122" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z"/>
                                                    </svg>
                                                @elseif ($isHalf)
                                                    <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                                                        <defs>
                                                            <linearGradient id="half-star-{{ $i }}">
                                                                <stop offset="50%" stop-color="#EF9122"/>
                                                                <stop offset="50%" stop-color="#ccc"/>
                                                            </linearGradient>
                                                        </defs>
                                                        <path d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z"
                                                            fill="url(#half-star-{{ $i }})"/>
                                                    </svg>
                                                @else
                                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="#ccc" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>

                                        <div class="reviews text-main">
                                            ({{ $product->reviews->count() }} reviews)
                                        </div>
                                    </div>

                                </div>
                                <div class="tf-product-heading">
                                    <div class="product-info-price price-wrap">
                                        <span class="price-new price-on-sale h2 fw-4">$ {{ $product->offer_price }}</span>
                                        @if ($product->discount_option != 1)
                                            <span class="price-old compare-at-price h6">${{ $product->base_price }}</span>
                                            @php
                                                $discountPercentage = round(
                                                    ($product->discount_amount / $product->base_price) * 100,
                                                );
                                            @endphp
                                            <p class="badges-on-sale h6 fw-semibold">
                                                <span class="number-sale" data-person-sale="29">
                                                    -{{ $discountPercentage }} %
                                                </span>
                                            </p>
                                        @endif
                                    </div>

                                    @if($product->expire_date && config('website_settings.show_expire') == true)
                                        <div class="tf-product-info-countdown">
                                            <div class="countdown-title">
                                                <h5>Hurry up</h5>
                                                <p class="text-main">Offer ends in:</p>
                                            </div>
                                            <div class="tf-countdown style-1">
                                                <div class="js-countdown" 
                                                    data-countdown="{{ \Carbon\Carbon::parse($product->expire_date)->format('Y-m-d H:i:s') }}" 
                                                    data-labels="Days,Hours,Mins,Secs">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>                               

                                <livewire:frontend.cart.add-cart :productId="$product->id" />

                                <div class="tf-product-extra-link">
                                    @if(config('website_settings.ask_qustion') == true)
                                        <a href="#askQuestion" data-bs-toggle="modal" class="product-extra-icon link">
                                            <i class="icon icon-ques"></i>Ask a question
                                        </a>
                                        <livewire:frontend.product.product-question :productId="$product->id" />
                                    @endif
                                    @if(config('website_settings.share') == true)
                                        <a href="#shareWith" data-bs-toggle="modal" class="product-extra-icon link">
                                            <i class="icon icon-share"></i>Share
                                        </a>
                                    @endif
                                </div>
                                
                                <ul class="tf-product-cate-sku">
                                    @if(!is_null($product->sku_code))
                                        <li class="item-cate-sku h6">
                                            <span class="label fw-6 text-black">SKU:</span>
                                            <span class="value link text-main-2">{{ $product->sku_code }}</span>
                                        </li>
                                    @endif
                                    @if(!is_null($product->brand_id) && isset($product->brand))
                                        <li class="item-cate-sku h6">
                                            <span class="label fw-6 text-black">Brand:</span>
                                            <a href="{{ url('shop') }}?filter[]=brand:{{ $product->brand->id }}" class="value link text-main-2">{{ $product->brand->name ?? '' }}</a>
                                        </li>
                                    @endif
                                    @if(!is_null($product->category_id) && isset($product->category))
                                        <li class="item-cate-sku h6">
                                            <span class="label fw-6 text-black">Categories:</span>
                                            <a href="{{ route('category.products', [$product->category->slug]) }}" class="value link text-main-2">{{ $product->category->name }}</a>
                                            @if(!is_null($product->subcategory_id) && isset($product->subcategory))
                                                <i class="bi bi-arrow-right-short"></i>  
                                                <a href="{{ route('category.products', [$product->category->slug, $product->subcategory->slug]) }}" class="value link text-main-2">{{ $product->subcategory->name }}</a>
                                            @endif
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /Product Info -->

                </div>
            </div>
        </div> 
    </section>
    <!-- /Product Main -->
    
    <livewire:frontend.product.product-review :productId="$product->id" />

    <!-- Size Guide -->
    <div class="modal modalCentered fade modal-size-guide" id="size-guide">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content widget-tabs style-2">
                <div class="header">
                    <ul class="widget-menu-tab">
                        <li class="item-title active">
                            <span class="inner h3">Size Guide</span>
                        </li>
                    </ul>
                    <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
                </div>
                <div class="wrap">
                    <div class="widget-content-tab">
                        <div class="widget-content-inner active">
                            <table class="tab-sizeguide-table">
                                <thead>
                                    <tr>
                                        <th>Size</th>
                                        <th>US</th>
                                        <th>Bust</th>
                                        <th>Waist</th>
                                        <th>Low Hip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>XS</td>
                                        <td>2</td>
                                        <td>32</td>
                                        <td>24 - 25</td>
                                        <td>33 - 34</td>
                                    </tr>
                                    <tr>
                                        <td>S</td>
                                        <td>4</td>
                                        <td>26 - 27</td>
                                        <td>34 - 35</td>
                                        <td>35 - 26</td>
                                    </tr>
                                    <tr>
                                        <td>M</td>
                                        <td>6</td>
                                        <td>28 - 29</td>
                                        <td>36 - 37</td>
                                        <td>38 - 40</td>
                                    </tr>
                                    <tr>
                                        <td>L</td>
                                        <td>8</td>
                                        <td>30 - 31</td>
                                        <td>38 - 29</td>
                                        <td>42 - 44</td>
                                    </tr>
                                    <tr>
                                        <td>XL</td>
                                        <td>10</td>
                                        <td>32 - 33</td>
                                        <td>40 - 41</td>
                                        <td>45 - 47</td>
                                    </tr>
                                    <tr>
                                        <td>XXL</td>
                                        <td>12</td>
                                        <td>34 - 35</td>
                                        <td>42 - 43</td>
                                        <td>48 - 50</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Size Guide -->

    <!-- Share -->
    <div class="modal modalCentered fade modal-share" id="shareWith">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-heading">
                    <h2 class="fw-normal">Share</h2>
                    <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
                </div>

                {{-- Social Icons --}}
                <ul class="tf-social-icon">
                    <li>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}"
                        target="_blank" class="social-facebook">
                            <span class="icon"><i class="icon-fb"></i></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}"
                        target="_blank" class="social-x">
                            <span class="icon"><i class="icon-x"></i></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/"
                        target="_blank" class="social-instagram">
                            <span class="icon"><i class="icon-instagram-logo"></i></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.tiktok.com/"
                        target="_blank" class="social-tiktok">
                            <span class="icon"><i class="icon-tiktok"></i></span>
                        </a>
                    </li>
                </ul>

                <div class="wrap-code style-1">
                    <p class="coppyText h6" id="coppyText">{{ Request::fullUrl() }}</p>
                    <button class="btn-coppy-text tf-btn" id="btnCopy">Copy</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Share -->

@endsection

@section('page-script')
    <script src="{{ asset('frontend/js/photoswipe-lightbox.umd.min.js') }}"></script>
    <script src="{{ asset('frontend/js/photoswipe.umd.min.js') }}"></script>
    <script src="{{ asset('frontend/js/zoom.js') }}"></script>
    <script src="{{ asset('frontend/js/drift.min.js') }}"></script>
    <script src="{{ asset('frontend/js/count-down.js') }}"></script>
    
    {{-- copy url --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const copyButton = document.getElementById("btnCopy");
            const copyText = document.getElementById("coppyText");

            copyButton.addEventListener("click", function () {
                navigator.clipboard.writeText(copyText.textContent).then(() => {
                    copyButton.textContent = "Copied!";
                    copyButton.classList.add("copied");
                    setTimeout(() => {
                        copyButton.textContent = "Copy";
                        copyButton.classList.remove("copied");
                    }, 2000);
                }).catch(err => {
                    console.error("Failed to copy:", err);
                });
            });
        });
    </script>

    {{-- modals --}}
    <script>
        let modals = document.querySelectorAll('#ReviewsModal, #askQuestion');
        modals.forEach((modal) => {
            modal.addEventListener('show.bs.modal', (e) => {
                Livewire.emit('open_add_modal');
            });
        });

        document.addEventListener('livewire:load', function() {
            Livewire.on('success', function() {
                const cancelButtons = document.querySelectorAll('.icon-close');
                cancelButtons.forEach(button => {
                    setTimeout(() => {
                        button.click();
                    }, 1000);
                });
            });
        });
    </script>

@endsection
