@extends('frontend.layout.app')

@section('page-title')
Product Comparison
@endsection


@section('page-css')
<link href="{{ asset('frontend/style/compare.min.16.css') }}" type="text/css" rel="stylesheet" media="screen" />
<style>
    .btn-primary {
        color: white !important;
    }

    .f-btn {
        display: none;
    }

    .short-desc p {
        color: black !important;
        margin-bottom: 3px;
    }
</style>
@endsection

@section('body-content')

<section class="after-header p-tb-10">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="material-icons" title="Home">home</i></a></li>
            <li><a href="">Product Comparison</a></li>
        </ul>
    </div>
</section>

<section class="cmpr-page bg-bt-gray p-tb-15">
    <div id="content" class="container table-responsive ws-box p-20">
        <div class="cmpr-head">
            <div class="row" style="align-items: end">
                <div class="col-lg-6">
                    <div class="compare-blurb">
                        <h4 class="page-heading">Product Comparison</h4>
                        <p>Find and select products to see the differences and similarities between them</p>
                    </div>
                </div>
                {{-- <div class="col-lg-6">
                    <div class="actions">
                        <a class="btn print" href="https://www.startech.com.bd/product/compare/print">
                            <i class="material-icons">print</i>
                            <span class="action-text">Print</span>
                        </a>
                    </div>
                </div> --}}
            </div>
        </div>


        @php
        $compareProducts = $products ?? collect();
        @endphp

        @if( !empty($compareProducts) )
        <table class="table table-bordered cmpr-table count-4">
            <thead></thead>
            <tbody>
                {{-- Compare Table Header --}}
                <tr class="cmpr-header">
                    <td class="name m-hide">
                        <p>You can add Max 3 Products</p>
                    </td>

                    {{-- Show selected products --}}
                    @foreach($compareProducts as $product)
                    <td class="value">
                        <div class="compare-item-wrapper">

                            @livewire('frontend.compare.compare-search', ['selectedId' => $product->id])

                            <div class="p-item-img">
                                <img src="{{ asset($product->thumb_image) }}" class="img-thumbnail" width="228"
                                    height="228" />
                            </div>

                            <a class="p-item-name" href="{{ route('product-details', $product->slug) }}">
                                <strong>{{ $product->name }}</strong>
                            </a>

                            <div class="p-item-price">
                                @if($product->discount_option != 1)
                                <span class="price-new">{{ format_price($product->offer_price) }}৳</span>
                                <span class="price-old">{{ format_price($product->base_price) }}৳</span>
                                @else
                                <span>{{ format_price($product->base_price) }}৳</span>
                                @endif
                            </div>

                            <a href="{{ route('compare.remove', $product->id) }}" class="remove">
                                <i class="material-icons">close</i>
                            </a>

                        </div>
                    </td>
                    @endforeach

                    {{-- Fill empty slots --}}
                    @for ($i = $compareProducts->count(); $i < 3; $i++) <td class="value blank">
                        @livewire('frontend.compare.compare-search', ['selectedId' => null])
                        <p>Find and select product to compare</p>
                        @endfor
                </tr>
                <tr>
                    <td class="name">Brand</td>
                    @foreach($products as $product)
                        <td class="value" style="text-align:center;">
                            {{ optional($product->brand)->name ?? '-' }}
                        </td>
                    @endforeach

                    @for($i = $products->count(); $i < 3; $i++)
                        <td class="value" style="text-align:center;">-</td>
                    @endfor
                </tr>

                <tr>
                    <td class="name">Availability</td>
                    @foreach($products as $product)
                    <td class="value" style="text-align:center;">{{ $product->quantity > 0 ? 'In Stock' : 'Out of Stock'
                        }}</td>
                    @endforeach
                    @for($i = $products->count(); $i < 3; $i++) <td class="value" style="text-align:center;">-</td>
                        @endfor
                </tr>
                <tr>
                    <td class="name">Rating</td>

                    @foreach($products as $product)
                    <td class="value rating" style="display: flex;justify-content: center;">
                        <div class="rating-text">
                            <div class="rating-star">
                                <i class="material-icons rating-icon">star</i>
                                {{ number_format($product->reviews->avg('rating') ?? 0, 1) }}/5
                            </div>
                            ({{ $product->reviews->count() }} Reviews)
                        </div>
                    </td>
                    @endforeach

                    @for($i = $products->count(); $i < 3; $i++) <td class="value" style="text-align:center;">-</td>
                        @endfor
                </tr>
                <tr>
                    <td class="name">Summary</td>
                    @foreach($products as $product)
                    <td class="value short-desc">
                        {!! $product->short_description !!}
                    </td>
                    @endforeach

                    @for($i = $products->count(); $i < 3; $i++) <td class="value" style="text-align:center;">-</td>
                        @endfor
                </tr>
            </tbody>

            @include('frontend.pages.product.compare_expend')

            {{-- Buy Now Button --}}
            <tr>
                <td class="name"></td>
                @foreach($compareProducts as $product)
                <td class="value">
                    <a href="{{ route('product-details', $product->slug) }}" class="btn btn-primary btn-block">
                        Buy Now
                    </a>
                </td>
                @endforeach
                @for ($i = $compareProducts->count(); $i < 3; $i++) <td class="value">
                    </td>
                    @endfor
            </tr>
        </table>
        @endif


    </div>
</section>

@endsection