<x-default-layout>

    @section('custom-css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}">
    @endsection

    @section('title')
        Low Stock Report
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('lowstock') }}
    @endsection


    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" class="form-control form-control-solid w-250px ps-13"
                        placeholder="Search product by name" id="mySearchInput" />
                </div>
                <!--end::Search-->
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-category-table-toolbar="base">
                    
                    <button type="button" class="btn btn-light-primary me-2" data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-end">
                        <i class="ki-duotone ki-exit-down fs-2"><span class="path1"></span><span
                                class="path2"></span></i>
                        Export Report
                    </button>

                    <div id="kt_datatable_example_export_menu"
                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                        data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-kt-export="copy">
                                Copy to clipboard
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-kt-export="excel">
                                Export as Excel
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-kt-export="csv">
                                Export as CSV
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-kt-export="pdf">
                                Export as PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card-body py-4">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    <!-- DataTables Buttons JS -->
    @push('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    {{ $dataTable->scripts() }}

    <script>
        let table; 

        $(document).ready(function () {
            // Initialize datatable
            table = $('#out-of-stock-table').DataTable();

            // Global search input
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['out-of-stock-table'].search(this.value).draw();
            });

            // Export buttons
            $('[data-kt-export]').on('click', function (e) {
                e.preventDefault();

                const exportType = $(this).data('kt-export');
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
            });

        });

    </script>


    @endpush

</x-default-layout>