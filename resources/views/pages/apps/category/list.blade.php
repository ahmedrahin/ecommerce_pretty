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

                <!--begin::Sort Modal-->
                <div class="modal fade" id="kt_modal_sort_categories" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="fw-bold">Sort Categories (Drag & Drop)</h2>
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                                    {!! getIcon('cross','fs-1') !!}
                                </div>
                            </div>
                            <div class="modal-body px-5 my-3">
                                <p class="text-muted fs-7 mb-4">Drag and reorder categories. Top items will appear first on the Home page grid.</p>
                                @php
                                    $sortCategories = \App\Models\Category::orderBy('sort_order', 'asc')->orderByDesc('id')->get();
                                @endphp
                                <ul id="sortable_category_list" class="list-group">
                                    @foreach($sortCategories as $cat)
                                        <li class="list-group-item d-flex align-items-center justify-content-between py-3 px-4 mb-2 border rounded cursor-move bg-light-primary border-primary border-dashed"
                                            draggable="true" data-id="{{ $cat->id }}" style="cursor: grab;">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-abstract-14 fs-3 me-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                                <span class="fw-bold text-gray-800">{{ $cat->name }}</span>
                                            </div>
                                            <span class="badge badge-light-primary">{{ $cat->status == 1 ? 'Active' : 'Inactive' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="saveCategoryOrderBtn" class="btn btn-primary" style="width: 200px;">Save Order</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Sort Modal-->
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

                // HTML5 Drag and Drop logic for sorting list
                const sortableList = document.getElementById('sortable_category_list');
                let draggedItem = null;

                if (sortableList) {
                    sortableList.querySelectorAll('li').forEach(item => {
                        item.addEventListener('dragstart', function (e) {
                            draggedItem = item;
                            setTimeout(() => item.style.opacity = '0.4', 0);
                        });

                        item.addEventListener('dragend', function () {
                            setTimeout(() => {
                                item.style.opacity = '1';
                                draggedItem = null;
                            }, 0);
                        });

                        item.addEventListener('dragover', function (e) {
                            e.preventDefault();
                        });

                        item.addEventListener('dragenter', function (e) {
                            e.preventDefault();
                            if (this !== draggedItem) {
                                this.style.borderStyle = 'solid';
                            }
                        });

                        item.addEventListener('dragleave', function () {
                            this.style.borderStyle = 'dashed';
                        });

                        item.addEventListener('drop', function (e) {
                            e.preventDefault();
                            this.style.borderStyle = 'dashed';
                            if (this !== draggedItem) {
                                let allItems = Array.from(sortableList.children);
                                let draggedIdx = allItems.indexOf(draggedItem);
                                let targetIdx = allItems.indexOf(this);

                                if (draggedIdx < targetIdx) {
                                    sortableList.insertBefore(draggedItem, this.nextSibling);
                                } else {
                                    sortableList.insertBefore(draggedItem, this);
                                }
                            }
                        });
                    });
                }

                // Save order via AJAX
                $('#saveCategoryOrderBtn').on('click', function () {
                    let order = [];
                    $('#sortable_category_list li').each(function () {
                        order.push($(this).data('id'));
                    });

                    let $btn = $(this);
                    $btn.prop('disabled', true).text('Saving...');

                    $.ajax({
                        url: "{{ route('product-catalogue.category.update-sort-order') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            order: order
                        },
                        success: function (res) {
                            $btn.prop('disabled', false).text('Save Order');
                            $('#kt_modal_sort_categories').modal('hide');
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    text: "Category order updated successfully!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" }
                                });
                            } else {
                                alert("Category order updated successfully!");
                            }
                            if (window.LaravelDataTables && window.LaravelDataTables['category-table']) {
                                window.LaravelDataTables['category-table'].ajax.reload();
                            }
                        },
                        error: function (xhr) {
                            $btn.prop('disabled', false).text('Save Order');
                            alert("Failed to save order. Please try again.");
                        }
                    });
                });
            });
        </script>
    @endpush

</x-default-layout>
