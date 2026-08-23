@extends('frontend.layout.app')

@section('page-title')
    My Dashboard
@endsection


@section('body-content')

    <section class="s-page-title">
        <div class="container">
            <div class="content">
                <h1 class="title-page">@yield('page-title')</h1>
                <ul class="breadcrumbs-page">
                    <li><a href="{{ route('homepage') }}" class="h6 link">Home</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li>
                        <h6 class="current-page fw-normal">@yield('page-title')</h6>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <div class="btn-sidebar-mb d-lg-none left">
        <button data-bs-toggle="offcanvas" data-bs-target="#mbSidebar">
            <i class="icon icon-sidebar"></i>
        </button>
    </div>
    <div class="offcanvas offcanvas-start canvas-sidebar" id="mbSidebar">
        <div class="canvas-wrapper">
            <div class="canvas-header">
                <span class="title h4 fw-bold">My Acount</span>
                <span class="icon-close link icon-close-popup" data-bs-dismiss="offcanvas"></span>
            </div>
            <div class="canvas-body sidebar-mobile-append sidebar-account"></div>
        </div>
    </div>

    <section class="flat-spacing">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 d-none d-xl-block">
                    @include('frontend.pages.user.user-menu')
                </div>

                <div class="col-xl-9">
                    <div class="my-account-content">
                        <div class="acount-order_stats">
                            <div dir="ltr" class="swiper tf-swiper" data-preview="3" data-tablet="3" data-mobile-sm="2" data-mobile="1"
                                data-space-lg="48" data-space-md="16" data-space="12" data-pagination="1" data-pagination-sm="2"
                                data-pagination-md="3" data-pagination-lg="3">
                                <div class="swiper-wrapper">
                                    <!-- item 1 -->
                                    <div class="swiper-slide">
                                        <div class="order-box">
                                            <div class="order_icon">
                                                <i class="icon icon-package-thin"></i>
                                            </div>
                                            <div class="order_info">
                                                <p class="info_label h6">Wait for confirmation</p>
                                                <h2 class="info_count type-semibold">{{$user->orders->where('delivery_status', 'pending')->count()}}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="swiper-slide">
                                        <div class="order-box">
                                            <div class="order_icon">
                                                <i class="icon icon-box-arrow-up"></i>
                                            </div>
                                            <div class="order_info">
                                                <p class="info_label h6">Total order</p>
                                                <h2 class="info_count type-semibold">{{$user->orders->count()}}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="order-box">
                                            <div class="order_icon">
                                                <i class="icon icon-box-arrow-up"></i>
                                            </div>
                                            <div class="order_info">
                                                <p class="info_label h6">Total Buying</p>
                                                <h2 class="info_count type-semibold">${{format_price($user->orders->sum('grand_total'))}}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sw-dot-default tf-sw-pagination"></div>
                            </div>
                        </div>
                    </div>
                    <div class="my-account-content">
                        <h2 class="account-title type-semibold">Recent Orders</h2>
                        <div class="overflow-auto">
                            <table class="table-my_order">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th class="text-center">Total Item</th>
                                        <th class="text-center">Grand Total</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders->sortByDesc('id') as $order)
                                        <tr class="tb-order-item">
                                            <td class="tb-order_code">#{{ $order->order_id }}</td>
                                            <td class="text-center">{{ $order->orderItems()->sum('quantity') }}</td>
                                            <td class="text-center">${{ format_price($order->grand_total) }}</td>
                                            <td class="text-center">
                                                @php
                                                    $status = $order->delivery_status;
                                                    $statusClasses = [
                                                        'pending'    => 'stt-pending',
                                                        'processing' => 'stt-delivery', 
                                                        'delivered'  => 'stt-delivery',
                                                        'completed'  => 'stt-complete',
                                                        'canceled'   => 'stt-cancel',
                                                        'fake'       => 'stt-fake',
                                                    ];
                                                    
                                                    $statusLabels = [
                                                        'pending'    => 'Pending',
                                                        'processing' => 'Processing',
                                                        'delivered'  => 'Delivered', 
                                                        'completed'  => 'Completed',
                                                        'canceled'   => 'Cancelled',
                                                        'fake'       => 'Fake Order',
                                                    ];
                                                @endphp
                                                <div class="tb-order_status {{ $statusClasses[$status] ?? 'stt-pending' }}">
                                                    {{ $statusLabels[$status] ?? ucfirst($status) }}
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('order.invoice', ['user_id' => auth()->user()->id, 'order_id' => $order->order_id]) }}" class="link fw-semibold">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
