<x-default-layout>

    @section('title')
        Dashboard
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('dashboard') }}
    @endsection

    @push('styles')
        <style>
            .card .card-header {
                min-height: 58px;
            }

            .bg-gradient-blue {
                background: #7239ea !important;
                opacity: 1;
            }

            .bt-gradient-green {
                background: #50cd89 !important;
                opacity: 1;
            }

            .bt-gradient-red {
                background: #f1416c;
                opacity: 1;
            }

            .bt-gradient-black {
                background: #071437 !important;
                opacity: 1;
            }

            .text-custom {
                color: #ffffffc4 !important;
            }


            .apexcharts-canvas,
            .apexcharts-canvas svg {
                width: 100% !important;
            }

            .delivery-percent .col-xl-3 {
                margin: 0;
            }

            .delivery-percent .g-xl-8,
            .gx-xl-8 {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
            }

            .delivery-percent .card .card-body {
                padding: 10px 20px 0px;
            }

            .other-info .card.card-xl-stretch {
                max-height: 350px;
                height: 200px;
                overflow-y: scroll;
            }
            .other-info .card .card-header {
                padding-right: 10px !important;
            }
            .no-found {
                position: absolute;
                top: 57%;
                left: 54%;
                transform: translate(-50%, -50%);
                font-size: 15px;
                font-weight: 600;
                color: #ff0000ba;
            }
            .view-all {
                color: black;
                font-weight: 600;
                font-weight: 600;
                font-size: 16px;
                padding: 0px 0;
                display: block;
                text-align: center;
                border-top: 1px solid #f3f2f2;
                padding-top: 10px;
            }
            .apexcharts-tooltip,.apexcharts-tooltip-title, .apexcharts-xaxistooltip {
                display: none !important;
            }
            .flex-stack{
                padding-bottom: 0px !important;
            }
        </style>
    @endpush

    {{-- today report --}}
    <div class="card mb-14">
        <!-- Card Header -->
        <div class="card-header border-0 pt-6 text-white" style="background: #3c3c3c0f;">
            <h4 class="mb-0 text-center" style="font-weight: 700;">Today's Report</h4>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <div class="row">
                <!-- Sales Card -->
                <div class="col-md-3">
                    <div class="card bg-gradient-blue">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-4 text-white">Today's Sales</h6>
                                    <h2 class="mb-0 text-white">{{ formatCurrencyShort($todaySales) }}</h2>
                                </div>
                                <i class="bi bi-currency-dollar text-white" style="font-size: 30px;"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Orders Card -->
                <div class="col-md-3">
                    <div class="card bt-gradient-green">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-4 text-white">Today's Orders</h6>
                                    <h2 class="mb-0 text-white">{{ $todayOrders }}</h2>
                                </div>
                                <i class="bi bi-cart-plus text-white" style="font-size: 30px;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bt-gradient-red">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-4 text-white">Ordered Product Quantity</h6>
                                    <h2 class="mb-0 text-white">{{ $orderedProductQuantity }}</h2>
                                </div>
                                <i class="bi bi-person-standing-dress text-white" style="font-size: 30px;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bt-gradient-black">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-4 text-white">Total Visitors</h6>
                                    <h2 class="mb-0 text-white">{{ $totalVisitors }}</h2>
                                </div>
                                <i class="bi bi-person-check text-white" style="font-size: 30px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($todayPendingOrders > 0)
                <a class="alert alert-warning d-flex align-items-center p-5 mt-8 mb-0" href="{{ route('order-management.order.today') }}">
                    <i class="ki-duotone ki-shield-tick fs-2hx text-warning me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-warning">Pending Orders Alert</h4>
                        <span>You have <strong>{{ $todayPendingOrders }}</strong> pending order's today. Please review them.</span>
                    </div>
                </a>
            @endif

            <div class="row">
                <div class="row g-5 g-xl-8 other-info mt-0">
                    <div class="col-xl-4">
                        <!--begin::List Widget 1-->
                        <div class="card card-xl-stretch mb-xl-8">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-dark">Stock Report</span>
                                    <span class="text-muted mt-1 fw-semibold fs-7">Total stock {{ $todayStocks->sum('quantity') }} quantities</span>
                                </h3>
                                <div class="card-toolbar">
                                    <!--begin::Menu-->
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-duotone ki-category fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-5">
                                @forelse ($todayStocks as $stock)
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="symbol symbol-50px me-5">
                                            @if ($stock->stock === 'out_of_stock')
                                                <span class="symbol-label bg-light-danger">
                                                    <i class="ki-duotone ki-abstract-26 fs-2x text-danger">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                            @elseif($stock->stock === 'stock_in')
                                                <span class="symbol-label bg-light-success">
                                                    <i class="ki-duotone ki-abstract-26 fs-2x text-success">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('product-management.show',$stock->product_id) }}" class="text-dark text-hover-primary fs-6 fw-bold">{{ $stock->product_name }}</a>
                                            <span class="text-muted fw-bold">
                                                {{ $stock->stock == 'stock_in' ? $stock->quantity . ' pcs stock' : 'stock out' }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="no-found">No data found</div>
                                @endforelse
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List Widget 1-->
                    </div>
                    <div class="col-xl-4">
                        <!--begin::List Widget 2-->
                        <div class="card card-xl-stretch mb-xl-8">
                            <!--begin::Header-->
                            <div class="card-header border-0">
                                <h3 class="card-title fw-bold text-dark">New Users</h3>
                                <div class="card-toolbar">
                                    <!--begin::Menu-->
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-duotone ki-category fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                @forelse  ($todayUsers as $v)
                                    <div class="d-flex align-items-center mb-7">
                                        <a href="{{route('user-management.users.show', $v->id)}}" class="symbol symbol-50px me-5">
                                            @if($v->avatar)
                                                <img alt="Logo" src="{{ asset($v->avatar) }}" />
                                            @else
                                            <div
                                                class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $v->name) }}">
                                                {{ substr($v->name, 0, 1) }}
                                            </div>
                                            @endif
                                        </a>
                                        <div class="flex-grow-1">
                                            <a href="{{route('user-management.users.show', $v->id)}}" class="text-dark fw-bold text-hover-primary fs-6">{{ $v->name }}</a>
                                            <span class="text-muted d-block fw-bold">{{ $v->email }}</span>
                                        </div>
                                        <!--end::Text-->
                                    </div>
                                @empty
                                    <div class="no-found">No user register</div>
                                @endforelse

                                @if($todayOrderData->count() > 20)
                                    <a href="{{ route('order-management.order.today') }}" class="view-all">View all</a>
                                @endif

                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List Widget 2-->
                    </div>

                    <div class="col-xl-4">
                        <!--begin::List Widget 3-->
                        <div class="card card-xl-stretch mb-5 mb-xl-8">
                            <div class="card-header border-0 pt-5 mb-4">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-dark">New Orders</span>
                                </h3>
                                <div class="card-toolbar">
                                    <!--begin::Menu-->
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-duotone ki-category fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body pt-2">
                                @php
                                $statusBullets = [
                                    'pending'    => 'warning',
                                    'processing' => 'info',
                                    'delivered'  => 'primary',
                                    'completed'  => 'success',
                                    'canceled'   => 'danger',
                                    'fake'       => 'dark',
                                ];
                                @endphp

                                @forelse ($todayOrderData as $todayOrder)
                                    @php
                                        $bulletColor = $statusBullets[$todayOrder->delivery_status] ?? 'secondary';
                                    @endphp

                                    <div class="d-flex align-items-center mb-8">
                                        <span class="bullet bullet-vertical h-40px bg-{{ $bulletColor }}"></span>

                                        <div class="flex-grow-1" style="margin-left: 15px;">
                                            <a href="{{ route('order-management.order.show', $todayOrder->id) }}"
                                            class="text-gray-800 text-hover-primary fw-bold fs-6">
                                                {{ $todayOrder->order_id }}
                                            </a>
                                            <span class="text-muted fw-semibold d-block">
                                                @if ($todayOrder->created_at->diffInSeconds(now()) < 59)
                                                    now
                                                @else
                                                    {{ $todayOrder->created_at->diffForHumans() }}
                                                @endif
                                            </span>
                                        </div>

                                        <!--end::Description-->
                                        <span class="badge badge-light-{{ $bulletColor }} fs-8 fw-bold">
                                            ${{ number_format($todayOrder->grand_total, 0) }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="no-found">No order found</div>
                                @endforelse

                                @if($todayOrderData->count() > 20)
                                    <a href="{{ route('order-management.order.today') }}" class="view-all">View all</a>
                                @endif

                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end:List Widget 3-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- over all report --}}
    <div class="card mb-14">
        <!-- Card Header -->
        <div class="card-header border-0 pt-6 text-white" style="background: #3c3c3c0f;">
            <h4 class="mb-0 text-center" style="font-weight: 700;">Over All</h4>
            <div class="mb-0" style="position: relative;display:flex;margin-bottom: 10px !important;">
                <input class="form-control form-control-solid" placeholder="Pick date rage" id="kt_daterangepicker_4" autocomplete="off" />
                @if($startDate && $endDate)
                    <a href="{{ route('dashboard') }}" class="btn btn-danger" style="width: 160px;margin-left:10px;">Clear Filter</a>
                @endif
            </div>
        </div>

        <div class="card-body" id="overall-section">
            <div class="row g-5 g-xl-8">
                <div class="col-xl-3">
                    <!--begin::Statistics Widget 4-->
                    <div class="card card-xl-stretch mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body p-0">
                            <div class="d-flex flex-stack card-p flex-grow-1">
                                <span class="symbol symbol-50px me-2">
                                    <span class="symbol-label bg-light-info">
                                        <i class="ki-duotone ki-profile-user fs-2x text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column text-end">
                                    <span class="text-dark fw-bold fs-2">{{ $user }}</span>
                                    <span class="text-muted fw-semibold mt-1">Total Users</span>
                                </div>
                            </div>
                            <div class="statistics-widget-4-chart card-rounded-bottom" data-kt-chart-color="info"
                                style="height: 150px"></div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 4-->
                </div>
                <div class="col-xl-3">
                    <!--begin::Statistics Widget 4-->
                    <div class="card card-xl-stretch mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body p-0">
                            <div class="d-flex flex-stack card-p flex-grow-1">
                                <span class="symbol symbol-50px me-2">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-basket fs-2x text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column text-end">
                                    <span class="text-dark fw-bold fs-2">{{ $order }}</span>
                                    <span class="text-muted fw-semibold mt-1">Total Orders</span>
                                </div>
                            </div>
                            <div class="statistics-widget-4-chart card-rounded-bottom" data-kt-chart-color="success"
                                style="height: 150px"></div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 4-->
                </div>
                <div class="col-xl-3">
                    <!--begin::Statistics Widget 4-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body p-0">
                            <div class="d-flex flex-stack card-p flex-grow-1">
                                <span class="symbol symbol-50px me-2">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-dollar fs-2x text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column text-end">
                                    <span class="text-dark fw-bold fs-2">+{{ formatCurrencyShort($totalSales) }}</span>
                                    <span class="text-muted fw-semibold mt-1">Total Sales</span>
                                </div>
                            </div>
                            <div class="statistics-widget-4-chart card-rounded-bottom" data-kt-chart-color="primary"
                                style="height: 150px"></div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 4-->
                </div>

                <div class="col-xl-3">
                    <!--begin::Statistics Widget 4-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body p-0">
                            <div class="d-flex flex-stack card-p flex-grow-1">
                                <span class="symbol symbol-50px me-2">
                                    <span class="symbol-label bg-light-warning">
                                        <i class="ki-duotone ki-people fs-2x text-warning">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column text-end">
                                    <span class="text-dark fw-bold fs-2">+{{ $visitor }}</span>
                                    <span class="text-muted fw-semibold mt-1">Total Visitors</span>
                                </div>
                            </div>
                            <div class="statistics-widget-4-chart card-rounded-bottom" data-kt-chart-color="warning"
                                style="height: 150px"></div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 4-->
                </div>
            </div>

            <div class="row delivery-percent">
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

                <div class="row g-5 g-xl-8 mt-0">
                    <h4 class="mb-3" style="font-weight: 700;">Order Progress</h4>
                        @php
                            $statusColors = [
                                'pending'    => 'warning',
                                'processing' => 'info',
                                'delivered'  => 'primary',
                                'completed'  => 'success',
                                'canceled'   => 'danger',
                                'fake'       => 'dark',
                            ];
                        @endphp

                        <div class="row g-5 g-xl-8" style="margin-top: 5px;">
                            @foreach ($statusColors as $status => $color)
                                @php
                                    $stat    = $deliveryStats[$status] ?? null;
                                    $orders  = $stat->total_orders ?? 0;
                                    $amount  = $stat->total_amount ?? 0;
                                    $percent = $deliveryTotalOrders > 0 ? round(($orders / $deliveryTotalOrders) * 100, 2) : 0;
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
                                                    Total {{ $orders }} orders / ${{ number_format($amount, 0) }}
                                                </span>
                                            </div>
                                            <div class="progress h-7px bg-{{ $color }} bg-opacity-50 mt-7">
                                                <div class="progress-bar bg-{{ $color }}"
                                                    role="progressbar"
                                                    style="width: {{ $percent }}%"
                                                    aria-valuenow="{{ $percent }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="mb-5 mb-xl-10" >
                    <div class="card card-flush h-xl-100" >
                        <!--begin::Card header-->
                        <div class="card-header pt-7" >
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">Top 10 Best Selling Products</span>
                            </h3>
                            <div class="card-toolbar" >
                                <!--begin::Filters-->
                                <div class="d-flex flex-stack flex-wrap gap-4" >
                                    <div class="position-relative my-1">
                                        <i class="ki-duotone ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="text" class="form-control w-150px fs-7 ps-12" placeholder="Search">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-2">
                            <div id="kt_table_widget_4_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="table-responsive"><table class="table align-middle table-row-dashed fs-6 gy-3 dataTable no-footer">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-200px">Product</th>
                                        <th class="text-center min-w-100px">Price</th>
                                        <th class="text-center min-w-100px">Total Selling</th>
                                        <th class="text-center min-w-125px">Total Revenue</th>
                                        <th class="text-center min-w-100px">Stock</th>
                                        <th class="text-end min-w-150px">Publish Date</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">
                                    @forelse ($bestSellingProducts as $product)
                                        <tr>
                                            <!-- Product -->
                                            <td>
                                                <a href="{{ route('product-management.show', $product->id) }}"
                                                class="text-gray-800 text-hover-primary">
                                                    {{ $product->name }}
                                                </a>
                                            </td>

                                            <td class="text-center">
                                                ${{ $product->price }}
                                            </td>

                                            <td class="text-center">
                                                {{ $product->total_qty }}
                                            </td>

                                            <td class="text-center">
                                                {{ formatCurrencyShort($product->total_amount) }}
                                            </td>

                                            <td class="text-center">
                                                {!! $product->quantity == 0
                                                    ? '<span class="badge badge-light-danger">Out of stock</span>'
                                                    : '<span class="badge badge-light-success">In Stock</span>' !!}
                                            </td>

                                            <td class="text-end">
                                                {{ $product->created_at ? \Carbon\Carbon::parse($product->created_at)->format('d M, Y') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No sales data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div><div class="row"><div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"></div><div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"></div></div></div>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
            </div>

            <div class="row mt-0">
                <div class="mb-5 mb-xl-10" >
                    <div class="card card-flush h-xl-100" >
                        <!--begin::Card header-->
                        <div class="card-header pt-7" >
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">Top 5 Highest Order Amount</span>
                            </h3>
                            <div class="card-toolbar" >
                                <!--begin::Filters-->
                                <div class="d-flex flex-stack flex-wrap gap-4" >
                                    <div class="position-relative my-1">
                                        <i class="ki-duotone ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="text" class="form-control w-150px fs-7 ps-12" placeholder="Search">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-2">
                            <div id="kt_table_widget_4_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="table-responsive"><table class="table align-middle table-row-dashed fs-6 gy-3 dataTable no-footer">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-200px">Order Id</th>
                                        <th class="text-center min-w-100px">Grand Total</th>
                                        <th class="text-center min-w-125px">Total Qty</th>
                                        <th class="text-end min-w-150px">Order Date</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">
                                    @forelse ($topOrders as $topOrder)
                                        <tr>
                                            <!-- Product -->
                                            <td>
                                                <a href="{{ route('order-management.order.show', $topOrder->id) }}"
                                                class="text-gray-800 text-hover-primary">
                                                    {{ $topOrder->order_id }}
                                                </a>
                                            </td>

                                            <td class="text-center">
                                                {{ formatCurrencyShort($topOrder->grand_total) }}
                                            </td>

                                            <td class="text-center">
                                                {{ $topOrder->orderItems->sum('quantity') }}
                                            </td>

                                            <td class="text-end">
                                                {{ $topOrder->order_date ? \Carbon\Carbon::parse($topOrder->order_date)->format('d M, Y') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div><div class="row"><div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"></div><div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"></div></div></div>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            let start = "{{ $startDate ?? '' }}";
            let end   = "{{ $endDate ?? '' }}";

            $('#kt_daterangepicker_4').daterangepicker({
                autoApply: false,
                timePicker: true,
                locale: {
                    format: "YYYY-MM-DD",
                    applyLabel: 'Apply',
                    cancelLabel: 'Clear',
                },
                @if($startDate && $endDate)
                startDate: start,
                endDate: end,
                @endif
                ranges: {
                    "Today": [moment().startOf('day'), moment().endOf('day')],
                    "Yesterday": [moment().subtract(1, "days").startOf('day'), moment().subtract(1, "days").endOf('day')],
                    "Last 7 Days": [moment().subtract(6, "days").startOf('day'), moment().endOf('day')],
                    "Last 30 Days": [moment().subtract(29, "days").startOf('day'), moment().endOf('day')],
                    "This Month": [moment().startOf("month"), moment().endOf("month")],
                    "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
                }
            });


            $('#kt_daterangepicker_4').on('apply.daterangepicker', function(ev, picker) {
                let start = picker.startDate.format('YYYY-MM-DD');
                let end   = picker.endDate.format('YYYY-MM-DD');
                window.location.href = "?start_date=" + start + "&end_date=" + end;
            });

        </script>

        <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
    @endpush

</x-default-layout>
