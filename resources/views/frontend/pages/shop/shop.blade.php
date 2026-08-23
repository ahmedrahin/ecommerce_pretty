@extends('frontend.layout.app')

@section('page-title')
    Shop Products
@endsection

@section('page-css')
    <style>
        #category .filter-group-check .list-item:not(:last-child){
            margin-bottom: 7px;
        }

        #category {
            list-style: none;
            padding: 0;
        }

        #category li {
            position: relative;
            display: block !important;
        }

        #category a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 10px;
            text-decoration: none;
            color: #333;
        }

        #category .arrow {
            font-size: 10px;
            transition: 0.2s;
        }

        /* HIDE sub menus initially */
        .sub-category,
        .sub-sub-category {
            display: none;
            margin-left: 20px;
            border-left: 1px solid #ececec;
            padding-left: 15px;
        }

        /* SHOW on hover */
        .list-item:hover > .sub-category {
            display: block;
        }

        .sub-category > li:hover > .sub-sub-category {
            display: block;
        }

        /* Rotate arrow when open */
        .list-item:hover > a .arrow,
        .sub-category > li:hover > a .arrow {
            transform: rotate(90deg);
        }

    </style>
@endsection

@section('body-content')
    
    <div id="wrapper">
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">Shop Products</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('homepage') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">Shop</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <div class="flat-spacing pb-0" id="featured_category">
            <div class="container">
                <div dir="ltr" class="swiper tf-swiper" data-preview="5" data-tablet="4" data-mobile-sm="3" data-mobile="2" data-space-lg="40"
                    data-space-md="24" data-space="12" data-pagination="2" data-pagination-sm="3" data-pagination-md="4" data-pagination-lg="5">
                    <div class="swiper-wrapper">
                        @foreach (App\Models\Category::where('featured', 1)->where('status', 1)->latest()->get() as $category)
                            <div class="swiper-slide">
                                <div class="box-image_category style-2 hover-img">
                                    <a href="{{ route('category.products', $category->slug) }}" class="box-image_image img-style">
                                        <img class="lazyload" src="{{ asset($category->image ?? 'frontend/images/noimg.jpg') }}" data-src="{{ asset($category->image ?? 'frontend/images/noimg.jpg') }}" alt="">
                                    </a>
                                    <div class="box-image_content">
                                        <a href="{{ route('category.products', $category->slug) }}" class="tf-btn btn-white animate-btn animate-dark">
                                            <span class="h5 fw-medium">
                                                {{ $category->name }}
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="sw-dot-default tf-sw-pagination"></div>
                </div>
            </div>
        </div>

        <div class="flat-spacing-3" style="margin-bottom: 10px;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3">
                        @include('frontend.pages.shop.filter')
                    </div>
                    
                    <div class="col-xl-9">
                        <div class="tf-shop-control">
                            @include('frontend.pages.shop.filter-tags')
                            <div class="tf-control-sorting">
                                <p class="h6 d-none d-lg-block">Sort by:</p>
                                <div class="custom-select">
                                    <select id="input-sort">
                                        <option value="" {{ request('sort')=='' ? 'selected' : '' }}>Sorting..</option>
                                        <option value="best_selling" {{ request('sort')=='best_selling' ? 'selected' : '' }}>Best Selling</option>
                                        <option value="offer_price" {{ request('sort')=='offer_price' ? 'selected' : '' }}>Price, low to high
                                        </option>
                                        <option value="offer_price_desc" {{ request('sort')=='offer_price_desc' ? 'selected' : '' }}>Price, high to
                                            low</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="wrapper-control-shop gridLayout-wrapper">

                            <div class="wrapper-shop tf-grid-layout tf-col-3" id="productLayout">
                                @include('frontend.pages.shop.product-list')
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overlay-filter" id="overlay-filter"></div>
    </div>

    <div id="ajax-loader">
        <div style="width: 100%;height:100%;display:flex;align-items: center;justify-content: center;">
            <div class="spinner"></div>
        </div>
    </div>

@endsection


@section('page-script')

    <script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('frontend/js/shop.js') }}"></script>
    <script>
         window.addEventListener('load', function () {
            document.getElementById('featured_category').style.display = 'block';
        });
    </script>

@endsection
