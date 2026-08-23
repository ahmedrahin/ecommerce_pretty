@extends('frontend.layout.app')

@section('page-title')
    My Wishlist
@endsection


@section('page-css')
    <link href="{{ asset('frontend/style/accounts.min.12.css') }}" type="text/css" rel="stylesheet" media="screen" />
    
@endsection

@section('body-content')

    <section class="s-page-title">
        <div class="container">
            <div class="content">
                <h1 class="title-page">Your Wishlist</h1>
                <ul class="breadcrumbs-page">
                    <li><a href="{{ route('homepage') }}" class="h6 link">Home</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li>
                        <h6 class="current-page fw-normal">Wishlist</h6>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <livewire:frontend.wishlist.wishlist-list  />

@endsection
