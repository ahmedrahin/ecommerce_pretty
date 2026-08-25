@extends('frontend.layout.app')

@section('page-title')
    Home
@endsection

@section('page-css')
    <style>
        .sect-title {
            margin-bottom: 40px;
        }
        @media screen and (max-width: 800px) {
            .p-2 {
                padding: 0.4rem !important;
            }
            .productBoxItem{
                padding-bottom: 25px !important;
            }
            .tab-product_list{
                flex-wrap: wrap;
            }
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendor/nouislider/nouislider.min.css') }}">
@endsection

@section('body-content')

    <!-- Banner Slider -->
    <div class="tf-slideshow tf-btn-swiper-main">
        <div dir="ltr" class="swiper tf-swiper sw-slide-show slider_effect_fade" data-preview="1.33" data-tablet="1.2" data-auto="true" data-delay="3000" data-loop="true" data-center="true" data-space="8">
            <div class="swiper-wrapper">
                @php
                    $sliderBanners = collect($banners);
                    if ($sliderBanners->count() > 0 && $sliderBanners->count() < 4) {
                        while ($sliderBanners->count() < 4) {
                            $sliderBanners = $sliderBanners->concat($banners);
                        }
                    }
                @endphp
                @foreach ($sliderBanners as $banner)
                    <div class="swiper-slide">
                        <div class="slider-wrap">
                            <div class="sld_image">
                                <img src="{{ asset($banner->image) }}" data-src="{{ asset($banner->image) }}" alt="Slider" class="lazyload scale-item scale-item-1">
                            </div>
                            @isset($banner->content)
                                <div class="sld_content type-2">
                                    <div class="content-sld_wrap">
                                        <h2 class="title_sld type-semibold fade-item fade-item-1">
                                            <a href="{{ $banner->content->link ?? 'javascript:void(0)' }}" class="link">
                                                {{ $banner->content->title ?? '' }}
                                            </a>
                                        </h2>
                                        @isset($banner->content->description)
                                            <div class="price-wrap fade-item fade-item-2">
                                                <span class="price-new h6">{{ $banner->content->description ?? '' }}</span>
                                            </div>
                                        @endisset
                                        <span class="br-line width-item width-item-3"></span>
                                        @isset($banner->content->link)
                                            <div class="fade-item fade-item-4">
                                                <a href="{{ $banner->content->link }}" class="tf-btn-link link h6 fw-semibold">
                                                    Shop now
                                                    <i class="icon icon-arrow-right"></i>
                                                </a>
                                            </div>
                                        @endisset
                                    </div>
                                </div>
                            @endisset
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="sw-dot-default tf-sw-pagination"></div>
        </div>
    </div>
    <!-- /Banner Slider -->

    <!-- Box Icon -->
    <div class="flat-spacing">
        <div class="container">
            <div dir="ltr" class="swiper tf-swiper" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="1" data-space-lg="97" data-space-md="33" data-space="13" data-pagination="1" data-pagination-sm="2" data-pagination-md="3" data-pagination-lg="4">
                <div class="swiper-wrapper">
                    <!-- item 1 -->
                    <div class="swiper-slide">
                        <div class="box-icon_V01 wow fadeInLeft">
                            <span class="icon">
                                <i class="icon-package"></i>
                            </span>
                            <div class="content">
                                <h4 class="title fw-normal">30 days return</h4>
                                <p class="text">30 day money back guarantee</p>
                            </div>
                        </div>
                    </div>
                    <!-- item 2 -->
                    <div class="swiper-slide">
                        <div class="box-icon_V01 wow fadeInLeft" data-wow-delay="0.1s">
                            <span class="icon">
                                <i class="icon-calender"></i>
                            </span>
                            <div class="content">
                                <h4 class="title fw-normal">3 year warranty</h4>
                                <p class="text">Manufacturer's defect</p>
                            </div>
                        </div>
                    </div>
                    <!-- item 3 -->
                    <div class="swiper-slide">
                        <div class="box-icon_V01 wow fadeInLeft" data-wow-delay="0.2s">
                            <span class="icon">
                                <i class="icon-boat"></i>
                            </span>
                            <div class="content">
                                <h4 class="title fw-normal">Free shipping</h4>
                                <p class="text">Free Shipping for orders over $150</p>
                            </div>
                        </div>
                    </div>
                    <!-- item 4 -->
                    <div class="swiper-slide">
                        <div class="box-icon_V01 wow fadeInLeft" data-wow-delay="0.3s">
                            <span class="icon">
                                <i class="icon-headset"></i>
                            </span>
                            <div class="content">
                                <h4 class="title fw-normal">Online support</h4>
                                <p class="text">24 hours a day, 7 days a week</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sw-dot-default tf-sw-pagination"></div>
            </div>
        </div>
    </div>
    <!-- /Box Icon -->

    <!-- Category -->
    <section class="themesFlat" id="featured_category" style="margin-bottom: 60px;">
        <div class="container">
            <div class="sect-title text-center wow fadeInUp">
                <h1 class="title mb-8">Popular Category</h1>
                <p class="s-subtitle h6">Explore our wide selection of top featured categories</p>
            </div>
            <div dir="ltr" class="swiper tf-swiper wow fadeInUp" data-preview="6" data-tablet="4" data-mobile-sm="3" data-mobile="2" data-space-lg="48" data-space-md="32" data-space="12" data-pagination="2" data-pagination-sm="3" data-pagination-md="4" data-pagination-lg="6">
                <div class="swiper-wrapper">
                    @foreach ($featuredCategories as $category)
                        <div class="swiper-slide">
                            <a href="{{ route('category.products', $category->slug) }}" class="widget-collection style-circle hover-img">
                                <div class="collection_image img-style">
                                    <img class="lazyload" src="{{ asset($category->image ?? 'frontend/images/noimg.jpg') }}" data-src="{{ asset($category->image ?? 'frontend/images/noimg.jpg') }}" alt="{{ $category->name }}">
                                </div>
                                <div class="collection_content">
                                    <p class="collection_name h4 link">{{ $category->name }}</p>
                                    @php
                                        $count = isset($category->product_count) ? $category->product_count : (isset($category->products_count) ? $category->products_count : ($category->product ? $category->product->count() : 0));
                                    @endphp
                                    <span class="collection_count h6 text-main-2">{{ $count }} product</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- /Category -->

    @include('frontend.includes.home-tap')

    {{-- <div style="padding: 50px 0;"></div> --}}

    @livewire('frontend.home.special-product')

    @if(!empty($featuredReviews))
        <section class="flat-spacing bg-white-smoke">
            <div class="container">
                <div class="sect-title text-center wow fadeInUp">
                    <h1 class="s-title mb-8">Customer Reviews</h1>
                    <p class="s-subtitle h6">What our customer say about us</p>
                </div>
                <div class="tf-btn-swiper-main pst-2">
                    <div dir="ltr" class="swiper tf-swiper" data-preview="3" data-tablet="2" data-mobile-sm="1" data-mobile="1" data-space-lg="48" data-space-md="32" data-space="12" data-pagination="1" data-pagination-sm="1" data-pagination-md="2" data-pagination-lg="3">
                        <div class="swiper-wrapper">
                            @foreach ($featuredReviews as $review)
                                <div class="swiper-slide">
                                    <div class="testimonial-V01 border-0 wow fadeInLeft">
                                        <div class="">
                                            <p class="tes_text h4">
                                                “{{ $review->comment }}“
                                            </p>
                                            <div class="tes_author">
                                                <p class="author-name h4">
                                                    {{ $review->user_id && optional($review->user)->name ? $review->user->name : $review->name }}
                                                </p>
                                                <i class="author-verified icon-check-circle fs-24"></i>
                                            </div>
                                            <div class="rate_wrap">
                                                @php
                                                    $rating = $review->rating;
                                                @endphp

                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($rating >= $i)
                                                        <i class="icon-star text-star"></i>
                                                    @elseif($rating > ($i - 1) && $rating < $i)
                                                        <i class="icon-star text-star-half"></i>
                                                    @else
                                                        <i class="icon-star text-star-empty"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="br-line"></span>
                                        @if($review->product)
                                            <div class="tes_product">
                                                <div class="product-image">
                                                    <img class="lazyload" src="{{ asset($review->product->thumb_image) }}" data-src="{{ asset($review->product->thumb_image) }}" alt="{{ $review->product->name }}">
                                                </div>
                                                <div class="product-infor">
                                                    <h5 class="prd_name">
                                                        <a href="{{ route('product-details', $review->product->slug) }}" class="link">{{ $review->product->name }}</a>
                                                    </h5>
                                                    <h6 class="prd_price">${{ format_price($review->product->offer_price) }}</h6>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="sw-dot-default tf-sw-pagination"></div>
                    </div>
                    <div class="tf-sw-nav nav-prev-swiper">
                        <i class="icon icon-caret-left"></i>
                    </div>
                    <div class="tf-sw-nav nav-next-swiper">
                        <i class="icon icon-caret-right"></i>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(isset($reviewImages) && count($reviewImages) > 0)
        <!-- Gallery / Shop Instagram -->
        <section class="flat-spacing pt-10">
            <div class="container">
                <div class="sect-title text-center wow fadeInUp">
                    <h1 class="title mb-8">Shop Instagram</h1>
                    <p class="s-subtitle h6">Tag us on Instagram to be featured on our page</p>
                </div>
                <div dir="ltr" class="swiper tf-swiper wow fadeInUp" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2" data-space="0" data-pagination="2" data-pagination-sm="2" data-pagination-md="3" data-pagination-lg="4">
                    <div class="swiper-wrapper">
                        @foreach ($reviewImages as $revImg)
                            <div class="swiper-slide">
                                <div class="gallery-item hover-img hover-overlay">
                                    <div class="image img-style">
                                        <img class="lazyload" src="{{ asset($revImg->image) }}" data-src="{{ asset($revImg->image) }}" alt="Instagram Image">
                                    </div>
                                    <a href="{{ config('app.instra') ?? 'https://www.instagram.com/' }}" target="_blank" class="box-icon hover-tooltip">
                                        <span class="icon icon-instagram-logo"></span>
                                        <span class="tooltip">View on Instagram</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="sw-dot-default tf-sw-pagination"></div>
                </div>
            </div>
        </section>
    @endif
@endsection

@section('page-script')
     <script>
         window.addEventListener('load', function () {
            document.getElementById('featured_category').style.display = 'block';
        });
    </script>
@endsection
