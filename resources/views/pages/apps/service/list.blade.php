<x-default-layout>

    @section('custom-css')
    <link rel="stylesheet" href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}">
    <style>
        .table:not(.table-bordered) tr,
        .table:not(.table-bordered) th,
        .table:not(.table-bordered) td {
            font-size: 13px !important;
        }

        .page-title.d-flex {
            width: 100%;
        }
    </style>
    @endsection

    @section('title') {{ $getMonth }} Servicing List @endsection

    {{-- orders seen --}}
    <livewire:order.seen-order />


    @section('breadcrumbs')
    <div class="w-100 d-flex justify-content-between">
        {{ Breadcrumbs::render('serviceList') }}
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
                    <input type="text" class="form-control form-control-solid w-250px ps-13" placeholder="Search servicing"
                        id="mySearchInput" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('service.create') }}" class="btn btn-primary">
                    {!! getIcon('plus', 'fs-2', '', 'i') !!}
                    Add New
                </a>
                @include('pages.apps.service.buttons')
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->


        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

     <div class="row" style="margin-top: 30px;">

        <div class="col-md-5">
            <div class="card bgi-no-repeat bgi-position-y-top bgi-position-x-end statistics-widget-1 card-xl-stretch mb-xl-8">
                <div class="card-header" style="min-height: 60px;">
                    <a href="#" class="card-title text-muted text-hover-primary fs-4" style="font-weight: 400;"> Show Others Month's ({{ request('year', date('Y')) }}) Servicing</a>
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

                                <a href="{{ route('service.index', ['month' => strtolower($monthName), 'year' => $selectedYear]) }}"
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
                                    <a href="{{ route('service.index', ['year' => $availableYear, 'month' => (Carbon\Carbon::now()->format('M'))]) }}"
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

    <livewire:order.servicing-action></livewire:order.servicing-action>

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
                        window.LaravelDataTables['service-table']
                            .column(9)
                            .search(selectedStatus)
                            .draw();
                    });

                    var table = $('#service-table').DataTable();

                    // Event listener for the search input field
                    document.getElementById('mySearchInput').addEventListener('keyup', function() {
                        window.LaravelDataTables['service-table'].search(this.value).draw();
                    });


                    // Livewire success event handler
                    document.addEventListener('livewire:load', function() {
                        Livewire.on('success', function() {
                            window.LaravelDataTables['service-table'].ajax.reload();
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
    @endpush
</x-default-layout>
