<x-default-layout>

    @section('custom-css')
        <link rel="stylesheet" href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}">
        <style>
            .thumbnail-img {
                width: 80%;
            }
            .page-title.d-flex{
                width: 100%;
            }
            .breadcrumb-separatorless{
                margin-top: -5px !important;
            }
            .product-datatable .table-product-image{
                width: 44px;
                height: 44px;
            }
            .product-datatable .fs-5{
                font-size: 12px !important
            }
            .product-datatable .table:not(.table-bordered) tr, .table:not(.table-bordered) th, .table:not(.table-bordered) td{
                font-size: 12px !important;
            }
            .product-datatable .fs-6 {
                font-size: 12px !important;
            }
            .dataTables_empty{
                font-size: 16px !important;
            }
            .form-switch.form-check-custom .form-check-input{
                height: 2.05rem;
                width: 3.05rem;
            }
            .menu-sub-dropdown .form-check-custom .form-check-label {
                margin-left: 4px;
            }
            .menu-sub-dropdown .form-check.form-check-sm .form-check-input {
                height: 16px;
                width: 16px;
            }
            .menu-sub-dropdown .form-check-label{
                font-size: 13px;
            }
        </style>
    @endsection

    @section('title') Product List @endsection

    @section('breadcrumbs')
       <div class="w-100 d-flex justify-content-between">
            {{ Breadcrumbs::render('product') }}

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
                            <div class="fs-5 text-dark fw-bold">Filter Products</div>
                        </div>
                        <div class="separator border-gray-200"></div>

                        <div class="px-7 py-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Category:</label>
                                        <div>
                                            <select class="form-select form-select-solid" multiple="multiple" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select category" data-dropdown-parent="#kt_menu_64b776123225e" data-allow-clear="true" id="category-select">
                                                <option></option>
                                                @foreach( $categories as $category )
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Rating:</label>
                                        <div>
                                            <select class="form-select form-select-solid" multiple="multiple" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select rating" data-dropdown-parent="#kt_menu_64b776123225e" data-allow-clear="true" id="rating-select">
                                                <option value="0">No rating</option>
                                                <option value="1"></option>
                                                <option value="2"></option>
                                                <option value="3"></option>
                                                <option value="4"></option>
                                                <option value="5"></option>
                                            </select>
                                        </div>
                                    </div>
        
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Selling:</label>
                                        <div>
                                            <select class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select by sale" data-dropdown-parent="#kt_menu_64b776123225e" data-allow-clear="true" id="selling-select">
                                                <option></option>
                                                <option value="best">Best Selling</option>
                                                <option value="no">No Sale</option>
                                                <option value="1-10">1 to 10</option>
                                                <option value="10-20">10 to 20</option>
                                                <option value="20-50">20 to 50</option>
                                                <option value="50-100">50 to 100</option>
                                            </select>
                                        </div>
                                    </div>
        
                                </div>

                                <div class="col-md-6">
                                    {{-- <div class="mb-5">
                                        <label class="form-label fw-semibold">Product Stock:</label>
                                        <div class="d-flex">

                                            <select class="form-select form-select-solid" multiple="multiple" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select quantity" data-dropdown-parent="#kt_menu_64b776123225e" data-allow-clear="true" id="rating-select">
                                                <option></option>
                                                <option value="outof">Out of stock</option>
                                                <option value="low">Low stock</option>
                                                <option value="low"></option>
                                                <option value="low">Low stock</option>
                                                <option value="low">Low stock</option>
                                            </select>

                                        </div>
                                    </div> --}}

                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Offer Products:</label>
                                        <div>
                                            <select class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select by offer" data-dropdown-parent="#kt_menu_64b776123225e" data-allow-clear="true" id="offer-select">
                                                <option></option>
                                                <option value="no-offer">No offer</option>
                                                <option value="offer">Offer %</option>
                                                <option value="30-offer">30%+ Offer</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Create Date:</label>
                                        <div>
                                            <input id="created_at_filter" placeholder="Select a date" class="form-control mb-2" value="" />
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
  
    <div class="card product-datatable">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" 
                        class="form-control form-control-solid w-250px ps-13" placeholder="Search product"
                        id="mySearchInput" />
                </div>
            </div>

            <div class="card-toolbar">
                @include('pages.apps.product.buttons')
            </div>
        </div>

        <livewire:product.product-action></livewire:product.product-action>

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
        @if(Session::has('success'))
            <script>
                toastr.success("{{ Session::get('success') }}");
            </script>
        @endif
        {{ $dataTable->scripts() }}
        
        <script>
            $(document).ready(function() {
                $('#statusFilter').select2({
                    minimumResultsForSearch: -1 
                });

                var table = $('#product-table').DataTable();
                
                // Event listener for the search input field
                document.getElementById('mySearchInput').addEventListener('keyup', function() {
                    window.LaravelDataTables['product-table'].search(this.value).draw();
                });

                // Livewire success event handler
                document.addEventListener('livewire:load', function() {
                    Livewire.on('success', function() {
                        window.LaravelDataTables['product-table'].ajax.reload();
                    });
                    
                    Livewire.on('info', function() {
                        window.LaravelDataTables['product-table'].ajax.reload();
                    });
                });

                $('#statusFilter').on('change', function () {
                    var selectedStatus = $(this).val();
                    window.LaravelDataTables['product-table']
                        .column(9) 
                        .search(selectedStatus)
                        .draw();
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
        @include('pages.apps.product.columns.filter')

    @endpush

</x-default-layout>