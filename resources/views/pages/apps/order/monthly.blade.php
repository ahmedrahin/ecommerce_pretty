<x-default-layout>

    @section('custom-css')
        <link rel="stylesheet" href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}">
        <style>
            .table:not(.table-bordered) tr, .table:not(.table-bordered) th, .table:not(.table-bordered) td{font-size: 13px !important;}
            .page-title.d-flex{
                width: 100%;
            }
             .col-xl-3 {
                margin: 0;
            }

            .g-xl-8,
            .gx-xl-8 {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
            }
            .delivery-percent .card .card-body {
                padding: 10px 20px 0px;
            }
        </style>
    @endsection

    @section('title') Monthly Order List @endsection

    {{-- orders seen --}}
    <livewire:order.order-delete />


    @section('breadcrumbs')
        <div class="w-100 d-flex justify-content-between">
            {{ Breadcrumbs::render('monthlyorder') }}

            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <div id="active-filters">

                </div>

                <button  class="btn btn-sm btn-danger reset-filter-btn me-1" id="resetFilter" onclick="triggerReset()" style="display: none;">
                    Reset Filters
                </button>

                <div class="m-0">
                    <!--begin::Menu toggle-->
                    <a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                    <i class="ki-duotone ki-filter fs-6 text-muted me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>Filter</a>

                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-500px" data-kt-menu="true" id="kt_menu_64b776123225e">
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bold">Filter Orders</div>
                        </div>
                        <div class="separator border-gray-200"></div>

                        <div class="px-7 py-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">User Type:</label>
                                        <div class="mb-0">
                                            <div class="d-flex mt-3">
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" name="type-status" value="author" id="author">
                                                    <label class="form-check-label" for="author">Author</label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" name="type-status" value="customer" id="customer">
                                                    <label class="form-check-label" for="customer">Customer</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="mb-5">
                                        <label class="form-label fw-semibold">:</label>
                                        <div>
                                            <select class="form-select form-select-solid" multiple="multiple" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select category" data-dropdown-parent="#kt_menu_64b776123225e" data-allow-clear="true" id="category-select">
                                                <option></option>

                                            </select>
                                        </div>
                                    </div> --}}

                                </div>

                                <div class="col-md-6">
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Viewed Order:</label>
                                        <div class="mb-0">
                                            <div class="d-flex mt-3">
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" name="viewed-status" value="1" id="seen">
                                                    <label class="form-check-label" for="seen">Seen</label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" name="viewed-status" value="0" id="unseen">
                                                    <label class="form-check-label" for="unseen">Unseen</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
                                <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true" id="apply">Apply</button>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
    </div>
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text"
                        class="form-control form-control-solid w-250px ps-13" placeholder="Search order"
                        id="mySearchInput" />
                </div>
                <!--end::Search-->
            </div>

            <div class="card-toolbar">
                @include('pages.apps.order.buttons')
            </div>
        </div>

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div class="col-md-3">
            <a href="#" class="card bg-body hoverable card-xl-stretch mb-xl-8">
                <!--begin::Body-->
                <div class="card-body">
                    <i class="ki-duotone ki-chart-simple text-primary fs-2x ms-n1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    <div class="text-gray-900 fw-bold fs-2 mb-2 mt-5">{{ formatCurrencyShort($order->sum('grand_total')) }}</div>
                    <div class="fw-semibold text-gray-400">Total amount of {{ $month }} - {{ $year }}</div>
                </div>
                <!--end::Body-->
            </a>
        </div>

        <div class="col-md-5">
            <div class="card bgi-no-repeat bgi-position-y-top bgi-position-x-end statistics-widget-1 card-xl-stretch mb-xl-8">
                <div class="card-header" style="min-height: 60px;">
                    <a href="#" class="card-title text-muted text-hover-primary fs-4" style="font-weight: 400;"> Show Others Month's ({{ request('year', date('Y')) }}) Order Sheet</a>
                </div>
                <div class="card-body monthlyExpense">
                    <div class="row">
                        <div class="col-12">
                            @php
                                $currentMonth = date('n');
                                $selectedYear = request('year', date('Y'));
                                $currentYear = date('Y');
                            @endphp

                            @for ($i = 1; $i <= 12; $i++)
                                @php
                                    $monthName = \Carbon\Carbon::create($selectedYear, $i, 1)->format('M');
                                    $disabled = ($selectedYear == $currentYear && $i > $currentMonth) ? 'disabled' : '';
                                    $buttonClass = (strtolower(request('month')) == strtolower($monthName)) ? 'btn-success' : 'btn-primary';
                                    $isCurrentMonth = (strtolower(date('M')) == strtolower($monthName) && request('month') == null) ? 'btn-success' : '';
                                @endphp

                                <a href="{{ route('order-management.order.monthly', ['month' => strtolower($monthName), 'year' => $selectedYear]) }}"
                                class="btn {{ $buttonClass }} {{ $isCurrentMonth }} {{ $disabled }}">
                                    {{ $monthName }}
                                </a>
                            @endfor
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bgi-no-repeat bgi-position-y-top bgi-position-x-end statistics-widget-1 card-xl-stretch mb-xl-8">
                <div class="card-header" style="min-height: 60px;">
                    <a href="#" class="card-title text-muted text-hover-primary fs-4" style="font-weight: 400;"> Select Year</a>
                </div>
                <div class="card-body monthlyExpense">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($allYear as $availableYear)
                                    <a href="{{ route('order-management.order.monthly', ['year' => $availableYear, 'month' => (Carbon\Carbon::now()->format('M'))]) }}"
                                    class="btn mb-2 {{ request('year') == $availableYear ? 'btn-success text-white' : 'btn-primary' }}"
                                    style="width: 45%; text-align: center;">
                                        {{ $availableYear }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </div>

        </div>
    </div>

     <div class="row g-5 g-xl-8 mt-0 delivery-percent" style="margin-top: 0px !important;">
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
                        $percent = $totalOrders > 0 ? round(($orders / $totalOrders) * 100, 2) : 0;
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
                                        Total {{ $orders }} orders / {{ number_format($amount, 0) }}৳
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

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
        <script>
             $(document).ready(function() {
                $('#statusFilter').select2({
                    minimumResultsForSearch: -1
                });

                $('#statusFilter').on('change', function () {
                    var selectedStatus = $(this).val();
                    window.LaravelDataTables['order-table']
                        .column(7)
                        .search(selectedStatus)
                        .draw();
                });

                var table = $('#order-table').DataTable();

                // Event listener for the search input field
                document.getElementById('mySearchInput').addEventListener('keyup', function() {
                    window.LaravelDataTables['order-table'].search(this.value).draw();
                });


                // Livewire success event handler
                document.addEventListener('livewire:load', function() {
                    Livewire.on('success', function() {
                        window.LaravelDataTables['order-table'].ajax.reload();
                    });
                });

                // Event listener for export buttons
                $('[data-kt-export]').on('click', function(e) {
                    e.preventDefault();
                    handleExport($(this).data('kt-export'));
                });

                // Handle DataTable export actions
                function handleExport(exportType) {
                    switch (exportType) {
                        case 'copy':
                            table.button('.buttons-copy').trigger();
                            break;
                        case 'excel':
                            table.button('.buttons-excel').trigger();
                            break;
                        case 'csv':
                            table.button('.buttons-csv').trigger();
                            break;
                        case 'pdf':
                            table.button('.buttons-pdf').trigger();
                            break;
                        default:
                            console.error('Unknown export type:', exportType);
                    }
                }

            });
        </script>

        {{-- product filter --}}
        @include('pages.apps.order.columns.filter')
    @endpush
</x-default-layout>
