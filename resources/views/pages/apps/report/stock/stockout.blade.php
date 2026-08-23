<x-default-layout>

    @section('custom-css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}">
    @endsection

    @section('title')
    Stock Out Report
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('stockout') }}
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
                    <div class="mb-0" style="position: relative;margin-right: 15px;">
                        <input class="form-control form-control-solid" placeholder="Pick date rage"
                            id="kt_daterangepicker_4" data-dropdown-parent="#kt_menu_64b776123225e"
                            autocomplete="off" />
                        <span class="ki-duotone ki-cross fs-1"
                            style="position: absolute;top: 9px;right: 2px;cursor: pointer;" onclick="dateRemove()"><span
                                class="path1"></span><span class="path2"></span></span>
                    </div>
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

            // Initialize daterangepicker
            $("#kt_daterangepicker_4").daterangepicker({
                autoApply: false,
                timePicker: true,
                timePicker24Hour: false,
                timePickerSeconds: false,
                locale: {
                    format: "YYYY-MM-DD",
                    applyLabel: 'Apply',
                    cancelLabel: 'Clear',
                },
                ranges: {
                    "Today": [moment().startOf('day'), moment().endOf('day')],
                    "Yesterday": [moment().subtract(1, "days").startOf('day'), moment().subtract(1, "days").endOf('day')],
                    "Last 7 Days": [moment().subtract(6, "days").startOf('day'), moment().endOf('day')],
                    "Last 30 Days": [moment().subtract(29, "days").startOf('day'), moment().endOf('day')],
                    "This Month": [moment().startOf("month"), moment().endOf("month")],
                    "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
                }
            });

            $('#kt_daterangepicker_4').val('');
            $('.ki-cross').hide();

            // Filter by date range
            $('#kt_daterangepicker_4').on('change keyup', function () {
                if ($(this).val()) {
                    $('.ki-cross').show();
                    const selectedRange = $(this).val();
                    table.column(3).search(selectedRange).draw(); // column index 3 = last_stock_out
                } else {
                    $('.ki-cross').hide();
                    table.column(3).search('').draw(); // clear filter
                }
            });
        });

        // Clear date function
        function dateRemove() {
            $('#kt_daterangepicker_4').val('');
            $('.ki-cross').hide();
            table.column(3).search('').draw();
        }
    </script>


    @endpush

</x-default-layout>