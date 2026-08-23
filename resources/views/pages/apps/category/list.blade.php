<x-default-layout>

    @section('custom-css')
        <link rel="stylesheet" href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}">

        <style>
            .subcategorize h4 {
                margin-bottom: 30px;
                border-top: 1px solid #f1f1f2;
                padding-top: 20px;
            }
            .subcategorize .no-found{
                color: #ff0000a6;
                text-align: center;
                font-weight: 600;
                font-style: italic;
            }
            .delsubCat {
                background: #ff0000c7;
                border: none;
                width: 23px;
                height: 23px;
                border-radius: 50%;
                line-height: 6px;
            }
            .delsubCat i {
                color: white;
                font-size: 10px;
            }
            .subcategorize li {
                list-style: none;
                border-bottom: 1px solid #f1f1f2;
                padding: 0 20px;
                padding-bottom: 10px;
                margin-bottom: 10px;
                color: black;
            }
            .subcategorize ul {
                padding: 0 50px;
            }
            .modal-image {
                width: 130px;
                height: 130px;
                border-radius: 8px;
                display: block;
                margin-bottom: 20px;
            }
        </style>
    @endsection

    @section('title')Category List @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('category') }}
    @endsection

   

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-category-table-filter="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="Search category name"
                        id="mySearchInput" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                @include('pages.apps.category.buttons')
                <!--end::Toolbar-->
                <!--begin::Modal-->
                <livewire:category.add-category-modal></livewire:category.add-category-modal>
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
                window.LaravelDataTables['category-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_category').modal('hide');
                    window.LaravelDataTables['category-table'].ajax.reload();
                });
            });
            
            $(document).ready(function() {
                var table = $('#category-table').DataTable();

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
