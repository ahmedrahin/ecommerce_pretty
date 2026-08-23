@extends('frontend.layout.app')

@section('page-title')
    Our Latest Offers
@endsection

@section('page-css')
    <link href="{{ asset('frontend/style/offer.min.16.css') }}" type="text/css" rel="stylesheet" media="screen" />
    <style>
        .share-on  {
            display: block !important;
        }
        .share-on .share {
            display: block;
            padding-bottom: 12px;
        }
        .share-on a i {
            font-size: 20px;
        }
        .description ul li{
            padding: 3px 0;
        }
    </style>
@endsection


@section('body-content')

    <section class="after-header">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}"><i class="material-icons" title="Home">home</i></a></li>
                <li><a href="{{ route('offers') }}">Offers</a></li>
                <li><a href="">{{ $offer->title }}</a></li>
            </ul>
        </div>
    </section>

    <section class="bg-bt-gray info-page" style="padding: 35px 0;">
        <div class="container p-tb-15">
            <div class="ws-box content m-auto offer-details" style="max-width: 650px">
                <div class="head">
                    <div class="title"><a href="{{ route('offers') }}"><span class="material-icons">arrow_back</span></a>
                        <h3>Offer Details</h3>
                    </div>
                    <div class="countdown" data-date="{{ \Carbon\Carbon::parse($offer->expire_date)->format('F d, Y H:i:s') }}">
                        <span class="label">Offer Ends In</span>
                        <div class="count-items">
                            <span class="group days"><span class="digit"></span><span class="digit"></span><span class="tag">Days</span></span>
                            <span class="group hours"><span class="digit"></span><span class="digit"></span><span class="tag">Hours</span></span>
                            <span class="group minutes"><span class="digit"></span><span class="digit"></span><span class="tag">Minutes</span></span>
                            <span class="group seconds"><span class="digit"></span><span class="digit"></span><span class="tag">Seconds</span></span>
                        </div>
                    </div>
                </div>
                <div class="body">
                    <img width="600" height="600" src="{{ asset($offer->image) }}" alt="{{ asset($offer->image) }}">
                    <h1> {{ $offer->title }} </h1>
                    <p class="offer-info">
                        <span><i class="material-icons">date_range</i> {{ \Carbon\Carbon::parse($offer->start_at)->format('d M Y') }} - {{ \Carbon\Carbon::parse($offer->expire_date)->format('d M Y') }}</span>
                    </p>
                    <div class="description">
                        {!! $offer->details !!}
                    </div>

                    @if( $offer->links->count() > 0 )
                        <h4 class="m-tb-15">Click below links to check out our best prices:</h4>
                        <ul>
                            @foreach ($offer->links as $link)
                                <li><a href="{{ $link->link }}">{{ $link->title }}</a></li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="share-on">
                        <span class="share">Share:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" style="padding-right: 7px;"
                            target="_blank">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode(url()->current()) }}" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@section('page-script')


@endsection