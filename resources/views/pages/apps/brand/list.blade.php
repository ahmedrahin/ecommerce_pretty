<x-default-layout>

    @section('custom-css')
        <link rel="stylesheet" href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}">
    @endsection
    
    @section('title')Brand List @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('brand') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-brand-table-filter="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="Search brand name"
                        id="mySearchInput" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                @include('pages.apps.brand.buttons')
                <!--end::Toolbar-->
                <!--begin::Modal-->
                <livewire:brand.add-brand-modal></livewire:brand.add-brand-modal>
                <!--end::Modal-->
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

    <!-- DataTables Buttons JS -->
    @push('scripts')
        <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['brand-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_brand').modal('hide');
                    window.LaravelDataTables['brand-table'].ajax.reload();
                });
            });
            
            $(document).ready(function() {
                var table = $('#brand-table').DataTable();

                $('[data-kt-export]').on('click', function(e) {
                    e.preventDefault();

                    var exportType = $(this).data('kt-export');

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