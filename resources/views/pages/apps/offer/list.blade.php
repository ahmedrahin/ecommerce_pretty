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

    @section('title') Offer List @endsection

    {{-- orders seen --}}
    <livewire:order.seen-order />


    @section('breadcrumbs')
    <div class="w-100 d-flex justify-content-between">
        {{ Breadcrumbs::render('offer') }}
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
                    <input type="text" class="form-control form-control-solid w-250px ps-13" placeholder="Search offer"
                        id="mySearchInput" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('offer.create') }}" class="btn btn-primary">
                    {!! getIcon('plus', 'fs-2', '', 'i') !!}
                    Add New
                </a>
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


    <livewire:coupon.offer-action></livewire:coupon.offer-action>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
        <script>
            $(document).ready(function() {
                   
                    var table = $('#offer-table').DataTable();

                    // Event listener for the search input field
                    document.getElementById('mySearchInput').addEventListener('keyup', function() {
                        window.LaravelDataTables['offer-table'].search(this.value).draw();
                    });


                    // Livewire success event handler
                    document.addEventListener('livewire:load', function() {
                        Livewire.on('info', function() {
                            window.LaravelDataTables['offer-table'].ajax.reload();
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
