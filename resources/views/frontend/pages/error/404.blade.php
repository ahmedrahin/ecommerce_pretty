@extends('frontend.layout.app')

@section('page-title')
    404 Not found
@endsection


@section('body-content')
   
    <section class="s-404 flat-spacing">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 col-sm-10 offset-sm-1">
                    <div class="image">
                        <img class=" ls-is-cached lazyloaded" src="{{ asset('frontend/images/404.svg') }}" data-src="{{ asset('frontend/images/404.svg') }}" alt="">
                    </div>
                </div>
                <div class="col-12">
                    <div class="wrap">
                        <div class="content">
                            <h1 class="title">Page not found</h1>
                            <p class="sub-title h6">This page is missing or you assembled the link incorrectly</p>
                        </div>
                        <div class="group-btn">
                            <a href="{{ route('homepage') }}" class="tf-btn animate-btn">
                                Back to home page
                            </a>
                            <a href="{{ route('shop') }}" class="tf-btn style-line">
                                Shop
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
