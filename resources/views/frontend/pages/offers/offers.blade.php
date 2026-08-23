@extends('frontend.layout.app')

@section('page-title')
    Our Latest Offers
@endsection

@section('page-css')
    <link href="{{ asset('frontend/style/offer.min.16.css') }}" type="text/css" rel="stylesheet" media="screen" />
@endsection


@section('body-content')

    <section class="after-header">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}"><i class="material-icons" title="Home">home</i></a></li>
                <li>
                    <a href="">Offers</a>
                </li>
            </ul>
        </div>
    </section>

    <section class="offer-page bg-bt-gray p-tb-15">
        <div class="container">
            <div class="row">
                @foreach ($offers as $offer)
                    <div class="col-lg-4 col-md-6 col-xs-12 offer m-b-30">
                        <div class="offer-content ws-box">
                            <a href="{{ route('offer.details',$offer->id) }}">
                                <img src="{{ asset($offer->image) }}" width="400" height="400" >
                            </a>
                            <div class="details">
                                <p class="offer-info">
                                   <span>
                                        <i class="material-icons">date_range</i>
                                        <time itemprop="validFrom" datetime="{{ $offer->start_at }}">
                                            {{ \Carbon\Carbon::parse($offer->start_at)->format('d M Y') }}
                                        </time>
                                        &nbsp;-&nbsp;
                                        <time itemprop="validThrough" datetime="{{ $offer->expire_date }}">
                                            {{ \Carbon\Carbon::parse($offer->expire_date)->format('d M Y') }}
                                        </time>
                                    </span>
                                </p>
                                <a href="{{ route('offer.details',$offer->id) }}">
                                    <h4 class="title">{{ $offer->title }}</h4>
                                </a>
                                <p class="short-desc">{{ $offer->descrip }}</p>
                                <a href="{{ route('offer.details',$offer->id) }}" class="btn view-details" target="_blank">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@section('page-script')


@endsection