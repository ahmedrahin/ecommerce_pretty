@extends('frontend.layout.app')

@section('page-title')
    Checkout
@endsection

@section('page-css')
    <style>
        .shop-cart{
            display: none !important;
        }
        .check-ship .text{
            flex-wrap: inherit;
        }
        .check-ship{
            padding: 11px 12px;
        }
    </style>
@endsection


@section('body-content')
    <section class="s-page-title">
        <div class="container">
            <div class="content">
                <h1 class="title-page">Checkout</h1>
                <ul class="breadcrumbs-page">
                    <li><a href="{{ route('homepage') }}" class="h6 link">Home</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li>
                        <h6 class="current-page fw-normal">Checkout</h6>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <livewire:frontend.order.checkout />
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
@endpush
