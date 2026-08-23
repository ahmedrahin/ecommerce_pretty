@extends('frontend.layout.app')

@section('page-title')
Order Information
@endsection


@section('page-css')
    <style>
        .account-order_detail .detail-content_info{
            gap: 0 !important;
        }
    </style>
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
                <div class="my-account-content flat-animate-tab">
                    <div class="account-order_detail">
                        <div class="order-detail_content" style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 30px; align-items: start;">
                            <div class="detail-content_info">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-warning',
                                        'processing' => 'bg-info',
                                        'delivered' => 'bg-success',
                                        'completed' => 'bg-success',
                                        'canceled' => 'bg-danger',
                                        'fake' => 'bg-secondary',
                                    ];
                                    
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'delivered' => 'Delivered',
                                        'completed' => 'Completed',
                                        'canceled' => 'Cancelled',
                                        'fake' => 'Fake Order',
                                    ];

                                    $payment = match ($order->payment_type) {
                                        'cod' => 'Cash On Delivery',
                                        'sslcommerz' => 'Online Payment',
                                        default => 'Unknown',
                                    };
                                @endphp
                                
                                <div class="detail-info_status {{ $statusClasses[$order->delivery_status] ?? 'bg-primary' }} h6" style="padding: 8px 16px; border-radius: 20px; color: white; font-weight: 500; display: inline-block; width: fit-content; background: #3a86ff;">
                                    {{ $statusLabels[$order->delivery_status] ?? ucfirst($order->delivery_status) }}
                                </div>

                                <br>

                                <div class="detail-info_item" style="display: flex; flex-direction: column; gap: 5px; margin-bottom: 15px;">
                                    <p class="info-item_label" style="font-size: 14px; color: #666; font-weight: 500; margin: 0;">Order No:</p>
                                    <p class="info-item_value" style="font-size: 16px; color: #333; font-weight: 400; margin: 0;">#{{ $order->order_id }}</p>
                                </div>
                                
                                <div class="detail-info_item" style="display: flex; flex-direction: column; gap: 5px; margin-bottom: 15px;">
                                    <p class="info-item_label" style="font-size: 14px; color: #666; font-weight: 500; margin: 0;">Order date</p>
                                    <p class="info-item_value" style="font-size: 16px; color: #333; font-weight: 400; margin: 0;">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, h:i a') }}</p>
                                </div>
                                
                                <div class="detail-info_item" style="display: flex; flex-direction: column; gap: 5px; margin-bottom: 15px;">
                                    <p class="info-item_label" style="font-size: 14px; color: #666; font-weight: 500; margin: 0;">Payment Method</p>
                                    <p class="info-item_value" style="font-size: 16px; color: #333; font-weight: 400; margin: 0;">{{ $payment }}</p>
                                </div>
                                
                                <div class="detail-info_item" style="display: flex; flex-direction: column; gap: 5px; margin-bottom: 15px;">
                                    <p class="info-item_label" style="font-size: 14px; color: #666; font-weight: 500; margin: 0;">Address</p>
                                    <p class="info-item_value" style="font-size: 16px; color: #333; font-weight: 400; margin: 0; line-height: 1.4;">
                                        {{ $order->name }}<br>
                                        {{ $order->shipping_address }}<br>
                                        {{ $order->district->name ?? '' }}
                                    </p>
                                </div>
                                
                                <div class="detail-info_item" style="display: flex; flex-direction: column; gap: 5px; margin-bottom: 15px;">
                                    <p class="info-item_label" style="font-size: 14px; color: #666; font-weight: 500; margin: 0;">Mobile</p>
                                    <p class="info-item_value" style="font-size: 16px; color: #333; font-weight: 400; margin: 0;">{{ $order->phone }}</p>
                                </div>
                            </div>
                            
                            <span class="br-line" style="width: 1px; background: #e0e0e0; height: 100%; min-height: 200px;"></span>
                            
                            <div class="order-summary-side">
                                <div class="summary-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                                    <strong class="summary-label" style="font-size: 14px; color: #666; margin: 0;">Sub-Total</strong>
                                    <strong class="summary-value" style="font-size: 14px; font-weight: 500; color: #333; margin: 0;">${{ format_price($order->orderItems->sum(fn($i) => $i->price * $i->quantity)) }}</strong>
                                </div>
                                
                                <div class="summary-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                                    <strong class="summary-label" style="font-size: 14px; color: #666; margin: 0;">Delivery Charge</strong>
                                    <strong class="summary-value" style="font-size: 14px; font-weight: 500; color: #333; margin: 0;">${{ format_price($order->shipping_cost) }}</strong>
                                </div>
                                
                                @if($order->coupon_discount)
                                    <div class="summary-item discount" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                                        <strong class="summary-label" style="font-size: 14px; color: #666; margin: 0;">Coupon Discount</strong>
                                        <strong class="summary-value" style="font-size: 14px; font-weight: 500; color: #28a745; margin: 0;">-${{ format_price($order->coupon_discount) }}</strong>
                                    </div>
                                @endif
                                
                                <div class="summary-item total" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0 8px 0; margin-top: 5px;">
                                    <strong class="summary-label" style="font-size: 16px; color: #333; font-weight: 600; margin: 0;">Total</strong>
                                    <strong class="summary-value" style="font-size: 16px; font-weight: 600; color: #333; margin: 0;">${{ format_price($order->grand_total) }}</strong>
                                </div>
                                
                                
                                <div style="margin-top: 30px;">
                                    <a target="_blank" href="{{ route('order.invoice.pdf', $order->order_id) }}" class="tf-btn style-line">
                                        Download Invoice
                                        <i class="icon icon-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="account-order_tab">
                        <ul class="tab-order_detail" role="tablist">
                            <li class="nav-tab-item" role="presentation">
                                <a href="#order-history" data-bs-toggle="tab" class="tf-btn-line tf-btn-tab active"
                                    aria-selected="false" role="tab" tabindex="-1">
                                    <span class="h4">
                                        Order history
                                    </span>
                                </a>
                            </li>
                            <li class="nav-tab-item" role="presentation">
                                <a href="#item-detail" data-bs-toggle="tab" class="tf-btn-line tf-btn-tab"
                                    aria-selected="false" role="tab" tabindex="-1">
                                    <span class="h4">
                                        Item details
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content overflow-hidden">
                            <div class="tab-pane active show" id="order-history" role="tabpanel">
                                <div class="order-timeline">
                                    @php
                                        $statusIcons = [
                                            'pending' => 'bi-clock',
                                            'processing' => 'icon-setting',
                                            'delivered' => 'icon-truck',
                                            'completed' => 'icon-check-1',
                                            'canceled' => 'icon-close',
                                            'fake' => 'bi-exclamation-triangle',
                                        ];
                                        
                                        $statusTitles = [
                                            'pending' => 'Order Pending',
                                            'processing' => 'Processing Order',
                                            'delivered' => 'Product Delivered',
                                            'completed' => 'Order Completed',
                                            'canceled' => 'Order Cancelled',
                                            'fake' => 'Fake Order Detected',
                                        ];
                                    @endphp

                                    @foreach ($order->histories as $index => $history)
                                        @php
                                            $status = strtolower($history->status);
                                            $icon = $statusIcons[$status] ?? 'icon-check-1';
                                            $title = $statusTitles[$status] ?? ucfirst($history->status);
                                        @endphp
                                        
                                        <div class="timeline-step completed">
                                            <div class="timeline_icon">
                                                <span class="icon" style="{{ $status == 'fake' || $status == 'canceled' ? 'background:#C8102E;border-color:#C8102E;' : '' }}">
                                                    <i class="{{ $icon }}"></i>
                                                </span>
                                            </div>
                                            <div class="timeline_content">
                                                <h5 class="step-title fw-semibold">{{ $title }}</h5>
                                                <h6 class="step-date fw-normal">{{ \Carbon\Carbon::parse($history->created_at)->format('d M, Y H:i a') }}</h6>
                                                
                                                @if(!empty($history->note))
                                                    <p class="step-detail h6">{{ $history->note }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane" id="item-detail" role="tabpanel">
                                <table class="table table-bordered table-hover table-order-products">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product Details</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($order->orderItems as $item)
                                            <tr>
                                                {{-- Product Image --}}
                                                <td width="100">
                                                    <img src="{{ asset($item->product->thumb_image) }}" 
                                                        alt="{{ $item->product->name }}" 
                                                        style="width:70px; height:auto;">
                                                </td>

                                                {{-- Product Details --}}
                                                <td>
                                                    <div class="info_detail">
                                                        <a href="{{ route('product-details', $item->product->slug) }}" 
                                                        class="h5 d-block text-dark">
                                                            {{ $item->product->name }}
                                                        </a>

                                                        <p class="m-0">
                                                            Price:
                                                            <span class="fw-semibold">
                                                                ${{ $item->price }}
                                                            </span>
                                                        </p>

                                                        @if ($item->orderItemVariations->count())
                                                            <div class="item-variants">
                                                                @foreach ($item->orderItemVariations as $variant)
                                                                    {{ ucfirst($variant->attribute_name) }}: {{ ucfirst($variant->attribute_value) }}
                                                                    @if (!$loop->last) - @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>

                                                {{-- Quantity --}}
                                                <td class="text-center">
                                                    {{ $item->quantity }}
                                                </td>

                                                {{-- Total --}}
                                                <td class="text-right fw-bold">
                                                    ${{ format_price($item->price * $item->quantity) }}
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

        </div>
    </div>
</section>

@endsection