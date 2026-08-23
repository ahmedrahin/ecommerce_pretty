<x-default-layout>

    @section('title')
        Order Invoice
    @endsection

    @section('content')
        <div class="card" id="invoiceArea">
            <!-- begin::Body-->
            <div class="card-body py-20">
                <!-- begin::Wrapper-->
                <div class="mw-lg-950px mx-auto w-100">
                    <!-- begin::Header-->
                    <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                        <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">INVOICE</h4>
                        <!--end::Logo-->
                        <div class="text-sm-end">
                            <!--begin::Logo-->
                            <a href="#" class="d-block mw-150px ms-sm-auto">
                                <img alt="Logo" src="{{ asset(config('app.logo')) }}" style="width: 100px" />
                            </a>
                            <!--end::Logo-->
                            <!--begin::Text-->
                            <div class="text-sm-end fw-semibold fs-4 text-muted mt-7">
                                <div>{{ config('app.name') }},
                                    <br>{{ config('app.phone') }}
                                    <br> {{ config('app.address') }}
                                </div>
                                <div>{{ config('app.state') }}</div>
                            </div>
                            <!--end::Text-->
                        </div>
                    </div>

                    <div class="pb-0">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column gap-7 gap-md-10">
                            <!--begin::Message-->
                            <div class="fw-bold fs-2">Dear {{ $order->name }}
                                <span class="fs-6">({{ $order->phone }})</span>,
                                <br />
                                <span class="text-muted fs-5">Here is your order invoice. We thank you for your
                                    purchase.</span>
                            </div>
                            <!--begin::Message-->
                            <!--begin::Separator-->
                            <div class="separator"></div>
                            <!--begin::Separator-->
                            <!--begin::Order details-->
                            <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                <div class="flex-root d-flex flex-column">
                                    <span class="text-muted">Order ID</span>
                                    <span class="fs-5">{{ $order->order_id }}</span>
                                </div>
                                <div class="flex-root d-flex flex-column">
                                    <span class="text-muted">Order Date</span>
                                    <span
                                        class="fs-5">{{ \Carbon\Carbon::parse($order->order_date)->format('d F, Y') }}</span>
                                </div>

                                <div class="flex-root d-flex flex-column">
                                    <span class="text-muted">Shipping method</span>
                                    <span class="fs-5">
                                        @if ($order->shippingMethod)
                                            @if ($order->shippingMethod->base_id)
                                                Inside {{ $order->shippingMethod->District->name }}
                                            @else
                                                {{ $order->shippingMethod->provider_name }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="flex-root d-flex flex-column">
                                    <span class="text-muted">Payment method</span>
                                    <span class="fs-5">
                                        {{ $order->payment_type == 'cod' ? 'Cash on delivery' : 'Online Payment' }}
                                    </span>
                                </div>
                            </div>
                            <!--end::Order details-->
                            <!--begin::Billing & shipping-->
                            <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                <div class="flex-root d-flex flex-column">
                                    <span class="text-muted">Shipping Address</span>
                                    <span class="fs-6">
                                        {{ $order->shipping_address }},
                                        {{ $order->zip_code ? 'zip-code:' . $order->zip_code : '' }}
                                        <br />{{ $order->district->name ?? '' }}.
                                    </span>
                                </div>
                                <div class="flex-root d-flex flex-column">
                                    <span class="text-muted">Billing Address</span>
                                    <span class="fs-6">

                                    </span>
                                </div>
                            </div>
                            <!--end::Billing & shipping-->
                            <!--begin:Order summary-->
                            <div class="d-flex justify-content-between flex-column">
                                <!--begin::Table-->
                                <div class="table-responsive border-bottom mb-9">
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
                                                        {{ format_price($item->price * $item->quantity) }}
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
                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end:Order summary-->
                        </div>
                        <div class="warranty">
                            {!! App\Models\PagesContent::value('warranty_text') !!}
                        </div>
                    </div>
                    <!--end::Body-->
                    <!-- begin::Footer-->
                    <div class="d-flex flex-stack flex-wrap mt-lg-20 pt-0" style="margin-top: 0 !important">
                        <div class="my-1 me-5 no-print">
                            <button type="button" class="btn btn-success my-1 me-3" onclick="printInvoice()">Print
                                Invoice</button>
                            <a href="{{ route('order.invoice.pdf', $order->order_id) }}" target="_blank"
                                class="btn btn-light-success my-1">Download Pdf</a>
                        </div>

                    </div>
                    <!-- end::Footer-->
                </div>
                <!-- end::Wrapper-->
            </div>
            <!-- end::Body-->
        </div>
    @endsection

    @push('scripts')
        <script>
            function printInvoice() {
                window.print();
            }
        </script>
    @endpush
</x-default-layout>
