<x-default-layout>

    @section('title')
        Website Setting
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('webitesetting') }}
    @endsection

    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
        <style>
            .nav-line-tabs .nav-item .nav-link {
                margin: 0 1.3rem !important;
                font-size: 17px !important;
            }

            .dropify-wrapper {
                border: 2px dashed var(--bs-primary) !important;
                background-color: var(--bs-primary-light) !important;
                border-radius: 10px !important;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
            }

            .dropify-message span.file-icon {
                font-size: 40px !important;
                color: var(--bs-primary) !important;
            }

            .dropify-message p {
                font-weight: bold !important;
                color: #333 !important;
            }

            .dropify-clear {
                background-color: #f44336 !important;
                /* Red clear button */
                color: white !important;
                border-radius: 5px !important;
            }

            .dropify-clear:hover {
                background-color: #d32f2f !important;
            }

            .dropify-font-upload:before,
            .dropify-wrapper .dropify-message span.file-icon:before {
                font-size: 60px;
                font-weight: 700;
            }

            .file-icon p {
                font-size: 14px !important;
                color: #45444887 !important;
            }

            /* .dropify-clear {
                                        display: none !important;
                                    } */
        </style>
    @endpush

    <style>
        .nav-line-tabs .nav-item .nav-link {
            margin: 0 1.3rem !important;
            font-size: 17px !important;
        }
    </style>


    <div id="kt_account_settings_profile_details" class="collapse show">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container">
                <!--begin::Card-->
                <div class="card card-flush">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold ">

                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 active"
                                    data-bs-toggle="tab" href="#kt_ecommerce_settings_home">
                                    <i class="ki-duotone ki-home-3 fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Home Page</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5"
                                    data-bs-toggle="tab" href="#kt_ecommerce_settings_products">
                                    <i class="ki-duotone ki-package fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>Products</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5"
                                    data-bs-toggle="tab" href="#kt_ecommerce_settings_customers">
                                    <i class="ki-duotone ki-people fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>Customers</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5"
                                    data-bs-toggle="tab" href="#kt_ecommerce_settings_product_details">
                                    <i class="ki-duotone ki-book fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path2"></span>
                                        <span class="path2"></span>
                                    </i>Product Details</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5"
                                    data-bs-toggle="tab" href="#pages_content">
                                    <i class="ki-duotone ki-book fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path2"></span>
                                        <span class="path2"></span>
                                    </i>Pages Content</a>
                            </li>

                        </ul>
                        <!--end:::Tabs-->
                        <!--begin:::Tab content-->
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="kt_ecommerce_settings_home" role="tabpanel">
                                

                            </div>

                            <div class="tab-pane fade" id="kt_ecommerce_settings_products" role="tabpanel">
                                <livewire:settings.productsetting />
                            </div>

                            <div class="tab-pane fade mt-15" id="kt_ecommerce_settings_customers" role="tabpanel">
                                <livewire:settings.customersetting />
                            </div>

                            <div class="tab-pane fade mt-15" id="kt_ecommerce_settings_product_details" role="tabpanel">
                                <livewire:settings.productdetails />
                            </div>

                            <div class="tab-pane fade mt-15" id="pages_content" role="tabpanel">
                                <form id="pageContentForm">
                                    @csrf
                                    @php
                                        $contentSetting = App\Models\PagesContent::first();
                                    @endphp
                                    <div class="mt-10">
                                        <div class="mb-6">
                                            <label class="form-label">Privacy Policy Content</label>
                                            <div id="privacy_policy" class="min-h-200px mb-2 ql-container ql-snow"
                                                style="width: 100%">
                                                @php
                                                    echo $contentSetting->privacy_policy ?? '';
                                                @endphp
                                            </div>
                                            <input name="privacy_policy" hidden>
                                        </div>
                                        <div>
                                            <label class="form-label">Refund Policy</label>
                                            <div id="refund_policy" style="width: 100%"
                                                class="min-h-200px mb-2 ql-container ql-snow">
                                                @php
                                                    echo $contentSetting->refund_policy ?? '';
                                                @endphp
                                            </div>
                                            <input name="refund_policy" hidden>
                                        </div>
                                        <div>
                                            <label class="form-label">Terms & Condition</label>
                                            <div id="term_condition" style="width: 100%"
                                                class="min-h-200px mb-2 ql-container ql-snow">
                                                @php
                                                    echo $contentSetting->terms ?? '';
                                                @endphp
                                            </div>
                                            <input name="term_condition" hidden>
                                        </div>

                                        <div class="row py-5">
                                            <div class="col-md-9 offset-md-3">
                                                <div class="card-footer d-flex justify-content-end py-0"
                                                    style="border: none;">
                                                    <button type="submit" class="btn btn-primary"
                                                        style="width: 200px !important;">
                                                        <span class="indicator-label">Save Changes</span>
                                                        <span class="indicator-progress">
                                                            Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <!--end:::Tab content-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content container-->
        </div>
    </div>


    @push('scripts')
        <script src="{{ asset('dropify.min.js') }}"></script>
        <script>
            $(document).ready(function() {

                var drEvent = $('.dropify').dropify({
                    messages: {
                        replace: 'Drag and drop or click to replace',
                        remove: 'Remove',
                        error: 'Oops, something went wrong!'
                    }
                });

                $('#homePageContent').on('submit', function(e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('setting.home.content.update') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('.btn-primary .indicator-progress').show();
                            $('.btn-primary .indicator-label').hide();
                        },
                        success: function(response) {
                            $('.btn-primary .indicator-progress').hide();
                            $('.btn-primary .indicator-label').show();

                            $('#new_arrivale_image_error').text('');
                            $('#marquee_text_error').text('');
                            toastr.success(response.message);
                        },
                        error: function(xhr) {
                            $('.btn-primary .indicator-progress').hide();
                            $('.btn-primary .indicator-label').show();
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                if (xhr.responseJSON.errors.new_arrivale_image) {
                                    $('#new_arrivale_image_error').text(xhr.responseJSON.errors
                                        .new_arrivale_image[0]);
                                }
                                if (xhr.responseJSON.errors.marquee_text) {
                                    $('#marquee_text_error').text(xhr.responseJSON.errors
                                        .marquee_text[0]);
                                }
                            } else {
                                toastr.error('Something went wrong. Please try again.');
                            }
                        }
                    });
                });


            });
        </script>

        <script>
            $(document).ready(function() {
                const quillOptions = {
                    modules: {
                        toolbar: [
                            [{
                                header: [1, 2, false]
                            }],
                            ['bold', 'italic', 'underline'],
                            ['image', 'code-block']
                        ]
                    },
                    placeholder: 'Type your text here...',
                    theme: 'snow'
                };

                // Initialize Quill editors
                const privacyEditor = new Quill('#privacy_policy', quillOptions);
                const refundEditor = new Quill('#refund_policy', quillOptions);
                const termsEditor = new Quill('#term_condition', quillOptions);
                

                // Function to update hidden inputs
                function updateHiddenInputs() {
                    $('input[name="privacy_policy"]').val(privacyEditor.root.innerHTML);
                    $('input[name="refund_policy"]').val(refundEditor.root.innerHTML);
                    $('input[name="term_condition"]').val(termsEditor.root.innerHTML);
                   
                }

                // Function to send AJAX request
                function sendAjaxUpdate() {
                    updateHiddenInputs();

                    const formData = $('#pageContentForm').serialize();

                    $.ajax({
                        url: "{{ route('setting.pages.content.update') }}",
                        method: 'POST',
                        data: formData,
                        beforeSend: function() {
                            $('.btn-primary .indicator-progress').show();
                            $('.btn-primary .indicator-label').hide();
                        },
                        success: function(res) {
                            $('.btn-primary .indicator-progress').hide();
                            $('.btn-primary .indicator-label').show();
                            toastr.success('Content updated successfully');
                        },
                        error: function(err) {
                            $('.btn-primary .indicator-progress').hide();
                            $('.btn-primary .indicator-label').show();
                            toastr.error('Update failed');
                            console.error(err);
                        }
                    });
                }


                // Manual form submit (button)
                $('#pageContentForm').on('submit', function(e) {
                    e.preventDefault(); // prevent default
                    sendAjaxUpdate(); // also send via AJAX
                });
            });
        </script>
    @endpush

</x-default-layout>
