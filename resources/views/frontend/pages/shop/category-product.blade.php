@extends('frontend.layout.app')

@section('page-title')
    {{ $category->name }}'s Products
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendor/nouislider/nouislider.min.css') }}">
     <style>
        /* Base */
        #category {
            list-style: none;
            padding: 0;
        }

        #category li {
            position: relative;
            display: block !important;
        }

        /* Link */
        #category a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 10px;
            text-decoration: none;
            color: #333;
        }

        #category .active > a {
            background: #f1f1f1;
            font-weight: bold;
            color: #C8102E;
            border-radius: 4px;
        }
        #category .filter-group-check .list-item:not(:last-child){
            margin-bottom: 7px;
        }

        /* Arrow */
        #category .arrow {
            font-size: 10px;
            transition: transform .25s ease;
        }

        /* Dropdown hidden state */
        .sub-category,
        .sub-sub-category {
            max-height: 0;
            opacity: 0;
            visibility: hidden;
            overflow: hidden;
            margin-left: 20px;
            padding-left: 15px;
            border-left: 1px solid #ececec;
            transition: all .35s ease;
        }

        /* On Hover — Smooth dropdown */
        .list-item:hover > .sub-category {
            max-height: 500px;
            opacity: 1;
            visibility: visible;
        }

        .sub-category > li:hover > .sub-sub-category {
            max-height: 500px;
            opacity: 1;
            visibility: visible;
        }

        /* Arrow rotate on hover */
        .list-item:hover > a .arrow,
        .sub-category > li:hover > a .arrow {
            transform: rotate(90deg);
        }
        /* Keep active category open */
        .list-item.active > .sub-category, .list-item.subActive > .sub-category, .subsubActive > .sub-sub-category {
            max-height: 500px;
            opacity: 1;
            visibility: visible;
        }

        /* Keep active sub-category open */
        .sub-category > li.active > .sub-sub-category {
            max-height: 500px;
            opacity: 1;
            visibility: visible;
        }

        .list-item.subActive > a, .subsubActive a {
            font-weight: 600;
            color: #C8102E !important;
        }
        
        /* Active rotate (Main category) */
        .list-item.active > a .arrow,
        .list-item.subActive > a .arrow {
            transform: rotate(90deg);
        }

        /* Active rotate (Sub category) */
        .sub-category > li.active > a .arrow,
        .sub-category > li.subsubActive > a .arrow {
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
                    <h1 class="title-page">Category Products</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('homepage') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li><a href="{{ route('shop') }}" class="h6 link">Shop</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">{{ $category->name }}</h6>
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
                                        <option value="offer_price_desc" {{ request('sort')=='offer_price_desc' ? 'selected' : '' }}>Price, high to low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="wrapper-control-shop gridLayout-wrapper">

                            <div class="wrapper-shop tf-grid-layout tf-col-3" id="gridLayout">
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

