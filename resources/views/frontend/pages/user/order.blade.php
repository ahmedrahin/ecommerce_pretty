@extends('frontend.layout.app')

@section('page-title')
    My Orders
@endsection


@section('body-content')

    <section class="s-page-title">
        <div class="container">
            <div class="content">
                <h1 class="title-page">@yield('page-title')</h1>
                <ul class="breadcrumbs-page">
                    <li><a href="{{ route('homepage') }}" class="h6 link">Home</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li><a href="{{ route('user.dashboard') }}" class="h6 link">My Dashboard</a></li>
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
                        <h2 class="account-title type-semibold">My Order({{ $orders->count() }})</h2>
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

                        <div class="wd-full wg-pagination" style="justify-content: center;margin-top: 10px;">
                            @if($orders->hasPages())
                                <div class="wd-full wg-pagination" style="justify-content: center;margin-top: 30px;">
                                    {{-- Previous Page Link --}}
                                    @if($orders->onFirstPage())
                                        <span class="pagination-item h6 direct disabled"><i class="icon icon-caret-left"></i></span>
                                    @else
                                        <a href="{{ $orders->previousPageUrl() }}" class="pagination-item h6 direct">
                                            <i class="icon icon-caret-left"></i>
                                        </a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                        @if($page == $orders->currentPage())
                                            <span class="pagination-item h6 active">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="pagination-item h6">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if($orders->hasMorePages())
                                        <a href="{{ $orders->nextPageUrl() }}" class="pagination-item h6 direct">
                                            <i class="icon icon-caret-right"></i>
                                        </a>
                                    @else
                                        <span class="pagination-item h6 direct disabled"><i class="icon icon-caret-right"></i></span>
                                    @endif
                                </div>
                            @endif
                        </div>          
                
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
