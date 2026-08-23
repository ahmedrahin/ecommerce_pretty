<div class="row g-5 g-xl-8">
    <div class="col-xl-4">
        <!--begin::Mixed Widget 14-->
        <div class="card card-xxl-stretch mb-xl-8 theme-dark-bg-body" style="background-color: #F7D9E3">
            <!--begin::Body-->
            <div class="card-body d-flex flex-column">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column mb-7">
                    <span class="text-dark text-hover-primary fw-bold fs-3">Summary</a>
                </div>
                <div class="row g-0">
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-9 me-2">
                            <div class="symbol symbol-40px me-3">
                                <div class="symbol-label bg-light">
                                    <i class="ki-duotone ki-abstract-42 fs-1 text-dark">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                            <div>
                                <div class="fs-5 text-dark fw-bold lh-1">{{ $product->orderItems()->sum('quantity') }}
                                </div>
                                <div class="fs-7 text-gray-600 fw-bold">Total Orders</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="d-flex align-items-center mb-9 ms-2">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-40px me-3">
                                <div class="symbol-label bg-light">
                                    <i class="ki-duotone ki-abstract-45 fs-1 text-dark">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Title-->
                            <div>
                                <div class="fs-5 text-dark fw-bold lh-1">{{ formatCurrencyShort($product->getRevenueAttribute() ?? 0)
                                    }}</div>
                                <div class="fs-7 text-gray-600 fw-bold">Revenue</div>
                            </div>
                            <!--end::Title-->
                        </div>
                    </div>

                    <div style="font-weight: 600;">
                        @if($lastOrder)
                            Last Order: <a href="{{ route('order-management.order.show', $lastOrder->id) }}" target="_blank">{{ $lastOrder->order_id }}</a> - {{ $lastOrder->created_at->format('d M Y') }}
                        @endif
                    </div>
                </div>
                <!--end::Row-->
            </div>
        </div>
        <!--end::Mixed Widget 14-->
    </div>
</div>


@php
$statusColors = [
'pending' => 'warning',
'processing' => 'info',
'delivered' => 'primary',
'completed' => 'success',
'canceled' => 'danger',
'fake' => 'dark',
];
@endphp

<div class="row g-5 g-xl-8  delivery-percent" style="margin-top: 5px;">
    @foreach($statusColors as $status => $color)
    @php
        $stat = $deliveryStats[$status] ?? null;
        $qty = $stat->total_qty ?? 0;
        $amount = $stat->total_amount ?? 0;
        $percent = $totalQty > 0 ? round(($qty / $totalQty) * 100, 2) : 0;
    @endphp

    <div class="col-xl-3">
        <div class="card bg-light-{{ $color }} card-xl-stretch mb-xl-8 mb-0">
            <div class="card-body my-3">
                <span class="card-title fw-bold text-{{ $color }} fs-5 mb-3 d-block">
                    {{ ucfirst($status) }} Orders
                </span>
                <div class="py-1">
                    <span class="text-dark fs-1 fw-bold me-2">{{ round($percent) }}%</span>
                    <span class="fw-semibold text-muted fs-7">
                        Total {{ $qty }} items / {{ number_format($amount, 0) }}৳
                    </span>
                </div>
                <div class="progress h-7px bg-{{ $color }} bg-opacity-50 mt-7">
                    <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $percent }}%"
                        aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
