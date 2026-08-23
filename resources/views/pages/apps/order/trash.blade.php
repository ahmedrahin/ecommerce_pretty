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

    @section('title') Trash Order List @endsection

    {{-- orders seen --}}
    <livewire:order.order-delete />


    @section('breadcrumbs')
        <div class="w-100 d-flex justify-content-between">
            {{ Breadcrumbs::render('trash_order') }}
        </div>
    @endsection


    <div class="card">
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
            </div>
            <div class="card-toolbar">
                <div>
                    <select name="statusFilter" id="statusFilter" class="form-select form-select-solid w-200px" data-control="select2" data-allow-clear="true" data-placeholder="Filter by status">
                        <option></option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="delivered">Delivered</option>
                        <option value="canceled">Canceled</option>
                        <option value="fake">Fake Order</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body py-4">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
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
                    window.LaravelDataTables['trashorder-table']
                        .column(7)
                        .search(selectedStatus)
                        .draw();
                });

                var table = $('#trashorder-table').DataTable();

                // Event listener for the search input field
                document.getElementById('mySearchInput').addEventListener('keyup', function() {
                    window.LaravelDataTables['trashorder-table'].search(this.value).draw();
                });


                // Livewire success event handler
                document.addEventListener('livewire:load', function() {
                    Livewire.on('success', function() {
                        window.LaravelDataTables['trashorder-table'].ajax.reload();
                    });
                    Livewire.on('info', function() {
                        window.LaravelDataTables['trashorder-table'].ajax.reload();
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
