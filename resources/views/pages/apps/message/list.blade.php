<x-default-layout>

    @section('custom-css')
        <link rel="stylesheet" href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}">
    @endsection
    
    @section('title') Contact Messages @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('message') }}
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
                        class="form-control form-control-solid w-250px ps-13" placeholder="Search.."
                        id="mySearchInput" />
                </div>
                <!--end::Search-->
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                    <div>
                        <select name="readFilter" id="readFilter" class="form-select form-select-solid w-200px" data-control="select2" data-allow-clear="true" data-placeholder="Filter by read">
                            <option></option>
                            <option value="1">Seen</option>
                            <option value="0">Unseen</option>
                        </select>        
                    </div>
                    
                     <div class="mx-3">
                        <select name="repliedFilter" id="repliedFilter" class="form-select form-select-solid w-200px" data-control="select2" data-allow-clear="true" data-placeholder="Filter by replied">
                            <option></option>
                            <option value="1">Replied</option>
                            <option value="0">No replied</option>
                        </select>        
                    </div>

                    <div class="btn-group">
                        <a href="{{ route('contact.weekly.message') }}" class="btn {{ request()->routeIs('contact.weekly.message') ? 'btn-success' : 'btn-primary' }}">
                            This Week Messages
                        </a>
                        
                        <a href="{{ route('contact.message') }}" class="btn {{ request()->routeIs('contact.message') ? 'btn-success' : 'btn-primary' }}">
                            All Messages
                        </a>
                    </div>
                </div>
            </div>
            
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
              $(document).ready(function() {
                $('#readFilter, #repliedFilter').select2({
                    minimumResultsForSearch: -1
                });

                var table = $('#message-table').DataTable();

                // Event listener for the search input field
                document.getElementById('mySearchInput').addEventListener('keyup', function() {
                    window.LaravelDataTables['message-table'].search(this.value).draw();
                });

                $('#readFilter').on('change', function () {
                    var selectedStatus = $(this).val();
                    window.LaravelDataTables['message-table']
                        .column(6)
                        .search(selectedStatus)
                        .draw();
                });

                $('#repliedFilter').on('change', function () {
                    var selectedStatus = $(this).val();
                    window.LaravelDataTables['message-table']
                        .column(7)
                        .search(selectedStatus)
                        .draw();
                });

            });
        </script>
    @endpush

</x-default-layout>