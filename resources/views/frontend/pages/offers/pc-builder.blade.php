@extends('frontend.layout.app')

@section('page-title')
    PC Builder
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
                    <a href="">PC Builder</a>
                </li>
            </ul>
        </div>
    </section>

    <div>
        <img src="{{ asset('frontend/image/coming-soon.png') }}" style="display: block;width: 300px;margin: 100px auto;">
    </div>
  
@endsection

@section('page-script')


@endsection