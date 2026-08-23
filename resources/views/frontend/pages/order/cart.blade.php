@extends('frontend.layout.app')

@section('page-title')
Cart
@endsection

@section('page-css')

@endsection


@section('body-content')

    <section class="s-page-title">
        <div class="container">
            <div class="content">
                <h1 class="title-page">Shopping Cart</h1>
                <ul class="breadcrumbs-page">
                    <li><a href="index.html" class="h6 link">Home</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li>
                        <h6 class="current-page fw-normal">Shopping Cart</h6>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <livewire:frontend.cart.cart-list />

@endsection