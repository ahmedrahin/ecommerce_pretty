<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<head>
    <meta charset="utf-8">
    <title>Order Invoice || {{ config('app.name') }}</title>
    <meta name="author" content="themesflat.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    

    <link rel="stylesheet" href="{{ asset('frontend/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/icon/icomoon/style.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('frontend/css/toastify.css')}}"/>

    <style>
        .s-invoice .heading {
            margin-bottom: 16px;
        }
        @media screen and (max-width: 800px) {
            .invoice_head_right{
                display: flex;
                align-items: center !important;
                margin-top: 10px;
            }
            .invoice_head_right, .tf-btn{
                padding: 10px 18px !important;
                font-size: 14px;
            }
            .wg-invoice{
                gap: 30px;
            }
        }
    </style>

</head>
<body class="wrapper-invoice p-0">

    @php
        $payment = match ($order->payment_type) {
            'cod' => 'Cash On Delivery',
            'stripe' => 'Pay with Stripe',
            default => 'Unknown',
        };
    @endphp

    <div class="s-invoice">
        <div class="container">
            <a href="{{ route('homepage') }}" class="heading h1 text-black fw-medium text-center d-block">
                <img src="{{ asset(config('app.logo')) }}" alt="" style="width:100px;">
            </a>
            <div class="wg-invoice">
                <div class="invoice-head">
                    <h2 class="invoice_number">Order Number: #{{ $order->order_id }}</h2>
                    <div class="invoice_head_right">
                        <a href="{{ route('order.invoice.pdf', $order->order_id) }}" class="invoice_download-btn tf-btn style-line">
                            Download
                            <i class="icon icon-download"></i>
                        </a>
                        <a href="{{ route('shop') }}" class="tf-btn" style="margin-left: 10px;">
                            Continue Shopping
                        </a>
                    </div>
                </div>
                <div class="invoice-info">
                    <div class="invoice-info_item invoice-info_date">
                        <h5 class="invoice_label fw-semibold">Order date:</h5>
                        <p class="invoice_value h6 ">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</p>
                    </div>
                    <div class="invoice-info_item invoice-info_date">
                        <h5 class="invoice_label fw-semibold">Payment Method:</h5>
                        <p class="invoice_value h6 ">{{ $payment }}</p>
                    </div>
                    <div class="invoice-info_item invoice-info_date">
                        <h5 class="invoice_label fw-semibold">Total Amount:</h5>
                        <p class="invoice_value h6 ">${{ $order->grand_total }}</p>
                    </div>
                    <div class="invoice-info_item" style="min-width: 300px;">
                        <h5 class="invoice_label fw-semibold">Shipping Address:</h5>
                        <p class="invoice_value h6">
                            <span class="fw-medium text-black">
                                {{ $order->shipping_name ?? 'Customer' }}
                            </span>
                            {{ $order->shipping_address }}, {{ $order->district->name ?? $order->city }}
                        </p>
                    </div>
                </div>
                <div class="overflow-auto">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                            <tr>
                                <td class="h6 invoice__desc">
                                    {{ $item->product->name }}
                                    @if ($item->orderItemVariations->count())
                                        <div class="item-variants">
                                            @foreach ($item->orderItemVariations as $variant)
                                                {{ ucfirst($variant->attribute_name) }}: {{ ucfirst($variant->attribute_value) }}
                                                @if (!$loop->last) - @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="h6 invoice__price">${{ $item->price }}</td>
                                <td class="h6 invoice__vat text-center">{{ $item->quantity }}</td>
                                <td class="h6 invoice__total text-end">${{ $item->price * $item->quantity }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="h6 invoice__desc fw-semibold">Subtotal</td>
                                <td class="h6 invoice__total text-end">${{ $order->orderItems->sum(fn($i) => $i->price * $i->quantity) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="h6 invoice__desc fw-semibold">Shipping</td>
                                <td class="h6 invoice__total text-end">${{ $order->shipping_cost }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="h6 invoice__desc fw-semibold">Discount</td>
                                <td class="h6 invoice__total text-end">-${{ $order->coupon_discount }}</td>
                            </tr>
                            <tr>
                                <td class="h6 invoice__desc fw-semibold">Total</td>
                                <td></td>
                                <td></td>
                                <td class="h6 invoice__amount fw-semibold text-primary text-end">${{ $order->grand_total }}</td>
                            </tr>
                            <tr>
                                <td class="h6 invoice__desc fw-semibold">Total Paid</td>
                                <td></td>
                                <td></td>
                                <td class="h6 invoice__amount fw-semibold text-success text-end">${{ $order->paid_amount }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <ul class="invoice-social">
                    <li>
                        <a href="{{ url('/') }}" class="invoice_link link h6">
                            <i class="icon fas fa-globe"></i>
                            {{ request()->getHttpHost() }}
                        </a>
                    </li>
                    <li>
                        <a href="tel:+88001234567" class="invoice_link link h6">
                            <i class="icon fas fa-phone"></i>
                            {{ config('app.phone') }}
                        </a>
                    </li>
                    <li>
                        <a href="mailto:support@ochaka.com" class="invoice_link link h6">
                            <i class="icon fas fa-envelope"></i>
                            {{ config('app.email') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('frontend/js/toastify.js') }}"></script>
    @if (session('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
            }).showToast();
        </script>
    @endif
</body>
</html>
