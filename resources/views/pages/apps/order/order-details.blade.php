<x-default-layout>

    @section('title')
        Order Details
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('order') }}
    @endsection
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .order-items-bx {
            max-height: 320px;
            overflow-y: scroll;
            padding-right: 15px;
            /* Create space for the scrollbar */
        }

        /* Hide scrollbar by default for WebKit browsers (Chrome, Safari, Edge) */
        .order-items-bx::-webkit-scrollbar {
            width: 0;
            /* Hide scrollbar by default */
        }

        .order-items-bx::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 10px;
        }

        .order-items-bx::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        /* For Firefox */
        .order-items-bx {
            scrollbar-width: none;
            /* Hide scrollbar by default */
        }

        .order-items-bx:active {
            scrollbar-width: thin;
            /* Show a thin scrollbar when scrolling */
        }

        .delivery-time {
            font-weight: 600;
            color: #ff000087;
            font-size: 13px;
        }

        .download-btn .btn-success {
            margin-right: 3px !important;
        }

        .download-btn i {
            padding: 0;
        }

        .download-btn {
            position: absolute;
            bottom: 15px;
            right: 15px;
        }

        .download-btn a {
            padding: 6px 10px !important;
        }

        .table:not(.table-bordered) tr,
        .table:not(.table-bordered) th,
        .table:not(.table-bordered) td {
            font-size: 13px !important;
        }
    </style>


    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            <!--begin:::Tabs-->
            <ul
                class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                        href="#kt_ecommerce_sales_order_summary">Order Summary</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                        href="#kt_ecommerce_sales_order_history">Order History</a>
                </li>
                <!--end:::Tab item-->
            </ul>
            <!--end:::Tabs-->
            <!--begin::Button-->
            <a href="{{ route('order-management.order.index') }}"
                class="btn btn-icon btn-light btn-active-secondary btn-sm ms-auto me-lg-n7">
                <i class="ki-duotone ki-left fs-2"></i>
            </a>
            <!--end::Button-->
            <!--begin::Button-->
            {{-- <a href="{{route('order-management.order.edit', $order->id)}}"
                class="btn btn-success btn-sm me-lg-n7">Edit Order</a> --}}
            <!--end::Button-->
            <!--begin::Button-->
            <button class="btn btn-primary btn-sm" >Add New Order</button>
            <!--end::Button-->
        </div>

        <div class="tab-content">
            <!--begin::Tab pane-->
            <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10 mb-12">
                    <!--begin::Order details-->
                    <div class="card card-flush py-4 flex-row-fluid order-items-bx">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Order Details ({{ $order->order_id }})</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0 pb-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-calendar fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>Order Date
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-wallet fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                    </i>Payment Method
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                {{ $order->payment_type == 'cod' ? 'Cash on delivery' : 'Online' }}
                                                @php
                                                    $paymentImg =
                                                        $order->payment_type == 'cod'
                                                            ? 'assets/media/payment-methods/cod.png'
                                                            : 'assets/media/payment-methods/visa.svg';
                                                @endphp
                                                <img src="{{ asset($paymentImg) }}" class="w-30px ms-1" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-truck fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>Shipping Method
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                @if ($order->shippingMethod)
                                                    @if ($order->shippingMethod->base_id)
                                                        Inside {{ $order->shippingMethod->District->name }} -
                                                        {{ $order->shipping_cost }} tk
                                                    @else
                                                        {{ $order->shippingMethod->provider_name }} -
                                                        {{ $order->shipping_cost }} tk
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-time fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>Time
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                {{ \Carbon\Carbon::parse($order->order_date)->diffForHumans() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-two-credit-cart fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>Transaction_id
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $order->transaction_id ?? '-' }}</td>
                                        </tr>
                                        @if (!is_null($order->cupon_code))
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ki-duotone ki-discount fs-2 me-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>Coupon Code
                                                    </div>
                                                </td>
                                                <td class="fw-bold text-end">
                                                    {{ $order->cupon_code }}
                                                </td>
                                            </tr>
                                        @endif
                                        {{-- <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-watch fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>Issue Date
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">12-09-24</td>
                                        </tr> --}}


                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Order details-->
                    <!--begin::Customer details-->
                    <div class="card card-flush py-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Customer Details</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0 pb-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-profile-circle fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>Customer
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <!--begin:: Avatar -->
                                                    <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                                                        <a href="#">
                                                            <div class="symbol-label">
                                                                <img src="{{ asset(
                                                                    $order->user && $order->user->avatar && file_exists(public_path($order->user->avatar))
                                                                        ? $order->user->avatar
                                                                        : 'uploads/admin/Default_pfp.svg.webp',
                                                                ) }}"
                                                                    class="w-100" alt="User Avatar" />
                                                            </div>
                                                        </a>
                                                    </div>

                                                    @if ($order->user)
                                                        @php
                                                            if ($order->user->isAdmin == 1) {
                                                                $profileUrl = route(
                                                                    'admin-management.admin-list.show',
                                                                    $order->user_id,
                                                                );
                                                            } elseif ($order->user->isAdmin == 2) {
                                                                $profileUrl = route(
                                                                    'user-management.users.show',
                                                                    $order->user_id,
                                                                );
                                                            } else {
                                                                $profileUrl = null;
                                                            }
                                                        @endphp

                                                        @if ($profileUrl)
                                                            <a href="{{ $profileUrl }}" target="_blank"
                                                                class="text-gray-600 text-hover-primary">
                                                                {{ $order->user->name ?? $order->name }}
                                                            </a>
                                                        @else
                                                            <span class="text-gray-600 text-hover-primary">
                                                                {{ $order->user->name ?? $order->name }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-gray-600 text-hover-primary">
                                                            {{ $order->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-sms fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>Email
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                <a href="mailto:{{ $order->user->email ?? ($order->email ?? '-') }}"
                                                    class="text-gray-600 text-hover-primary">
                                                    {{ $order->user->email ?? ($order->email ?? '-') }}
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-phone fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>Phone
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                <a href="tel:{{ $order->user->phone ?? ($order->phone ?? '') }}"
                                                    class="text-gray-600 text-hover-primary">
                                                    {{ $order->user->phone ?? ($order->phone ?? '-') }}
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>

                    </div>
                    <!--end::Customer details-->
                    <!--begin::Documents-->
                    <div class="card card-flush py-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Documents</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0 pb-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-devices fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>Invoice
                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="View the invoice generated by this order.">
                                                        <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                <a href="{{ route('order-management.invoice', $order->id) }}"
                                                    class="text-gray-600 text-hover-primary">{{ $order->order_id }}</a>
                                            </td>
                                        </tr>

                                        <div class="download-btn">
                                            <a href="{{ route('order-management.invoice', $order->id) }}"
                                                target="_blank" class="btn btn-success btn-sm me-lg-n7"><i
                                                    class="fa fa-eye me-2" aria-hidden="true"></i>Invoice</a>
                                            {{-- <a href="" class="btn btn-danger btn-sm"><i
                                                    class="fa fa-cloud-download" aria-hidden="true"></i></a> --}}
                                        </div>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Documents-->
                </div>

                <!--begin::Orders-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                        <!--begin::Payment address-->
                        <div class="card card-flush py-4 flex-row-fluid position-relative w-50 pb-0">
                            <!--begin::Background-->
                            {{-- <div
                                class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                                <i class="ki-solid ki-two-credit-cart" style="font-size: 14em"></i>
                            </div> --}}
                            <!--end::Background-->
                            <!--begin::Card header-->
                            {{-- <div class="card-header">
                                <div class="card-title">
                                    <h2>Delivery Status</h2>
                                </div>
                            </div> --}}

                            <livewire:order.order-action :order_id="$order->id" />

                        </div>

                        <div class="card card-flush py-4 flex-row-fluid position-relative w-50 pb-0">
                            <!--begin::Background-->
                            <div
                                class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                                <i class="ki-solid ki-delivery" style="font-size: 13em"></i>
                            </div>
                            <!--end::Background-->
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Shipping Address</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0" style="font-size: 16px;">
                                {{ $order->shipping_address }},
                                {{-- {{ $order->zip_code ? 'zip-code:' . $order->zip_code : '' }} --}}
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Shipping address-->
                    </div>
                    <!--begin::Product List-->
                    <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Order Items</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-175px">Product</th>
                                            <th class="min-w-100px text-center">SKU</th>
                                            <th class="min-w-70px text-center">Qty</th>
                                            <th class="min-w-100px text-center">Unit Price</th>
                                            <th class="min-w-100px text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">

                                        @foreach ($order->orderItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('product-management.show', $item->product->id) }}"
                                                            class="symbol symbol-50px">
                                                            <span class="symbol-label"
                                                                style="background-image:url({{ asset($item->product->thumb_image) }});"></span>
                                                        </a>

                                                        <div class="ms-5">
                                                            <a href="{{ route('product-management.show', $item->product->id) }}"
                                                                class="text-gray-800 fs-5 fw-bold">{{ $item->product->name }}</a>
                                                            {{-- show varitaion --}}
                                                            @if ($item->orderItemVariations->count() > 0)
                                                                <div class="fs-7 text-muted">
                                                                    @foreach ($item->orderItemVariations as $itemVariant)
                                                                        {{ ucfirst($itemVariant->attribute_name) . ':' . ucfirst($itemVariant->attribute_value) }}
                                                                        @if (!$loop->last)
                                                                            -
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $item->product->sku_code }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-center">${{ format_price($item->price) }}</td>
                                                <td class="text-end">
                                                    ${{ format_price($item->price * $item->quantity) }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        @php
                                            $subtotal = 0;
                                        @endphp

                                        @foreach ($order->orderItems as $item)
                                            @php
                                                $subtotal += $item->price * $item->quantity;
                                            @endphp
                                        @endforeach

                                        <tr>
                                            <td colspan="4" class="text-end">Subtotal</td>
                                            <td class="text-end">${{ format_price($subtotal) }}</td>
                                        </tr>

                                        @php
                                            $grandTotal = $order->grand_total ?? 0;
                                            $discount = $order->coupon_discount ?? 0;
                                            $discountPercentage = $subtotal > 0 ? ($discount / $subtotal) * 100 : 0;
                                        @endphp

                                        <tr>
                                            <td colspan="4" class="text-end">Discount
                                                ({{ round($discountPercentage) }}%)
                                            </td>
                                            <td class="text-end">
                                                ${{ format_price($discount) }}
                                            </td>
                                        </tr>


                                        <tr>
                                            <td colspan="4" class="text-end">Shipping Rate</td>
                                            <td class="text-end">${{ format_price($order->shipping_cost) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="fs-3 text-dark text-end">Grand Total</td>
                                            <td class="text-dark fs-3 fw-bolder text-end">
                                                ${{ format_price($order->grand_total) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="fs-3 text-success text-end">Paid</td>
                                            <td class="text-dark fs-3 fw-bolder text-end">
                                                ${{ format_price($order->paid_amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Product List-->
                </div>
                <!--end::Orders-->
            </div>
            <!--end::Tab pane-->
        </div>

        <div class="tab-content">
            <div class="tab-pane fade" id="kt_ecommerce_sales_order_history" role="tab-panel">
                <!--begin::Orders-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Order history-->
                    <div class="card card-flush py-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Order History</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-100px">Date</th>
                                            <th class="min-w-175px">Note</th>
                                            <th class="min-w-70px">Order Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        @foreach ($order->histories as $history)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($history->created_at)->format('d M, Y  (H:i a)') }}
                                                </td>
                                                <td>{{ $history->note }}</td>
                                                <td>
                                                    @php
                                                        $statusBadges = [
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'delivered' => 'primary',
                                                            'completed' => 'success',
                                                            'canceled' => 'danger',
                                                            'fake' => 'dark',
                                                        ];
                                                    @endphp
                                                    <div
                                                        class="badge badge-light-{{ $statusBadges[$history->status] ?? 'secondary' }}">
                                                        {{ ucfirst($history->status) }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>

                    {{-- <div class="card card-flush py-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Order Data</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">IP Address</td>
                                            <td class="fw-bold text-end">172.68.221.26</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Forwarded IP</td>
                                            <td class="fw-bold text-end">89.201.163.49</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">User Agent</td>
                                            <td class="fw-bold text-end">Mozilla/5.0 (Windows NT 10.0; Win64; x64)
                                                AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110
                                                Safari/537.36</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Accept Language</td>
                                            <td class="fw-bold text-end">en-GB,en-US;q=0.9,en;q=0.8</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div> --}}
                </div>
            </div>
        </div>

    </div>


    @push('scripts')
        <script>
            // $(document).ready(function(){
            //     $('.d-flex.align-items-center.gap-2.gap-lg-3').html(`
    //         <span class="delivery-time"><span class="text-muted">Delivery Time - </span>01:11:50 days</span>
    //     `);
            // })
        </script>
    @endpush
</x-default-layout>
