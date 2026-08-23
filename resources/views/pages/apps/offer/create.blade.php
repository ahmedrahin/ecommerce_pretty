<x-default-layout>

    @section('title')
        Add New Offer
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('createoffer') }}
    @endsection
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        div.dataTables_scrollBody {
            border-left: none !important;
        }

        .position-relative.d-flex button {
            width: 25px !important;
            height: 25px !important;
        }

        .ki-minus,
        .ki-plus {
            font-weight: 700;
        }

        input.fs-3 {
            font-size: 15px !important;
        }

        .error-border {
            border: 1px solid #f1416c !important;
        }

        span.text-danger {
            padding-top: 4px;
        }

        div.text-danger {
            padding-top: 0;
        }

        #errors-msgs {
            padding: 15px 0;
            /* width: 65%; */
            display: none;
        }

        #errors-msgs li {
            list-style: none;
        }

        .form-check.form-check-sm .form-check-input {
            height: 1.35rem !important;
            width: 1.35rem !important;
        }
    </style>

    <div id="errors">
        <ul id="errors-msgs">

        </ul>
    </div>

    <!--begin::Form-->
    <form class="form" id="add_form" enctype="multipart/form-data">
        <div class="">
            <div class="card card-flush py-4">
                <div class="card-body pt-5">
                    <div class="d-flex flex-column gap-5 gap-md-7">
                        <div class="fs-3 fw-bold mb-n2">Offer Info</div>

                        <label class="required form-label">Image</label>
                        <div class="card-body p-0">
                                @php
                                    $thumb_image = asset('assets/media/svg/files/blank-image.svg');
                                @endphp

                                <style>
                                    .image-input-placeholder-thumb {
                                        background-image: url('{{ $thumb_image }}');
                                    }

                                    [data-bs-theme="dark"] .image-input-placeholder-thumb {
                                        background-image: url('{{ $thumb_image }}');
                                    }
                                </style>

                                <div class="image-input image-input-empty image-input-outline image-input-placeholder-thumb mb-3"
                                    data-kt-image-input="true">
                                    <div class="image-input-wrapper w-250px h-250px"></div>
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="Upload thumbnails">
                                        <i class="ki-duotone ki-pencil fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="file" name="thumb_image" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="avatar_remove" />
                                    </label>
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        id="cancel_thumb" data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                        title="Cancel thumbnail">
                                        <i class="ki-duotone ki-cross fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <span id="thumb_image" class="text-danger" style="padding-bottom: 0 !important"></span>
                            </div>

                        <div class="d-flex flex-column flex-md-row gap-5">
                            <div class="fv-row flex-row-fluid">
                                <label class="required form-label">Tittle</label>
                                <input class="form-control" name="title" placeholder="Offer Tittle" />
                                <div id="title" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5">
                            <div class="fv-row flex-row-fluid">
                                <label class="required form-label">Sub Title</label>
                                <input class="form-control" name="descrip" placeholder="Sub Title" />
                                <div id="descrip" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5">
                            <div class="fv-row flex-row-fluid">
                                <label class="required form-label">Start Date</label>
                                <input placeholder="Select a date" name="start_at" class="form-control dateFick mb-2" />
                                <div id="start_at" class="text-danger"></div>
                            </div>
                            <div class="flex-row-fluid">
                                <label class="required form-label">Expire Date</label>
                                <input placeholder="Select a date" name="expire_date"
                                    class="form-control dateFick mb-2" />
                                <div id="expire_date" class="text-danger"></div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Details</label>
                            <textarea name="details" id="editor" style="width: 100%;height: 350px;"></textarea>
                        </div>

                        <div class="col-12 mt-0">
                            <div class="card radius-10 w-100">
                                <div class="card-body">
                                    <h5 style="margin-bottom: 20px;">Offer Links</h5>
                                    <div id="product-wrapper">
                                        <div class="product-item row g-3 mb-3">
                                            <div class="col-md-6 mb-2">
                                                <input type="text" name="link_title[]" class="form-control"
                                                    placeholder="Enter a title">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <input type="text" name="link[]" class="form-control"
                                                    placeholder="Link">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-light-success btn-sm" onclick="addProduct()">+
                                        Add
                                        More Link</button>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end" style="margin-top: 15px;">
                            <a href="" id="kt_ecommerce_edit_order_cancel" class="btn btn-light me-5">Cancel</a>

                            <button type="submit" id="add_order" class="btn btn-primary" style="width: 200px;">
                                <span class="indicator-label">Save Offer</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>

            </div>

    </form>
    <!--end::Form-->


    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
        <script>
            ClassicEditor.create(document.querySelector('#editor'))
            .catch(error => console.error(error));
        </script>

        <script>
            $('.dateFick').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });
        </script>

        <script>
            $('#add_form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route('offer.store') }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#add_order .indicator-progress').show();
                        $('#add_order .indicator-label').hide();
                    },
                    success: function(response) {
                        $('#add_order .indicator-progress').hide();
                        $('#add_order .indicator-label').show();

                        // Reset the form and Select2 inputs
                        $('#add_form')[0].reset();
                        $('#add_form select').val('').trigger('change');

                        // Clear any previous error messages
                        $('#errors-msgs').empty();
                        $('#errors-msgs').css('display', 'none');
                        $('div.text-danger').empty();
                        $('span.text-danger').css('padding-bottom', '0px');
                        $('input').removeClass('error-border');

                        $('#product-wrapper').empty();
                        $('#product-wrapper').append(`
                            <div class="product-item row g-3 mb-3">
                                <div class="col-md-6 mb-2">
                                    <input type="text" name="link_title[]" class="form-control"
                                        placeholder="Enter a title">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input type="text" name="link[]" class="form-control"
                                        placeholder="Link">
                                </div>
                            </div>
                        `);
                        $('span.btn-active-color-primary').click();
                        
                        Swal.fire({
                            text: response.message,
                            icon: 'success',
                            buttonsStyling: false,
                            confirmButtonText: 'Ok',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });

                    },
                    error: function(xhr) {
                        $('#add_order .indicator-progress').hide();
                        $('#add_order .indicator-label').show();

                        // Clear previous error messages
                        $('#errors-msgs').empty();
                        $('#errors-msgs').css('display', 'block');

                        // Scroll to error messages
                        $('html, body').animate({
                            scrollTop: $('#errors-msgs').offset().top - 70
                        }, 500);

                        // Check for custom error message
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            $('#errors-msgs').append(`
                                <li>
                                    <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5">
                                        <i class="ki-duotone ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                        <div class="d-flex flex-column pe-0 pe-sm-10">
                                            <h5 class="mb-1">${xhr.responseJSON.error}</h5>
                                            <span>Please fill up the field with valid data!</span>
                                        </div>
                                        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                            <i class="ki-duotone ki-cross fs-1 text-danger">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        </button>
                                    </div>
                                </li>
                            `);
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Display validation errors
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $('#errors-msgs').append(`
                                    <li>
                                        <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5">
                                            <i class="ki-duotone ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0">
                                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                            </i>
                                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                                <h5 class="mb-1">${value}</h5>
                                                <span>Please fill up the field with valid data!</span>
                                            </div>
                                            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                                <i class="ki-duotone ki-cross fs-1 text-danger">
                                                    <span class="path1"></span><span class="path2"></span>
                                                </i>
                                            </button>
                                        </div>
                                    </li>
                                `);

                                $('#' + key).text(value[0]);
                                $('#' + key).css('padding-top', '8px');
                                $('#' + key).css('font-weight', '600');
                                // Add error-border class to invalid input fields
                                $('input[name="' + key + '"]').addClass('error-border');
                                var $select = $('select[name="' + key + '"]');
                                $select.addClass('error-border');
                                // For select2 elements, target the select2-container
                                if ($select.hasClass('select2-hidden-accessible')) {
                                    $select.next('.select2-container').find('.select2-selection')
                                        .addClass('error-border');
                                }
                            });
                        } else {
                            Swal.fire({
                                text: 'An error occurred. Please try again.',
                                icon: 'error',
                                buttonsStyling: false,
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        }
                    }

                });
            });

            // Remove validation classes and messages on input change
            $('input').on('input', function() {
                var input = $(this);
                input.removeClass('error-border');
                input.next('.text-danger').html('').css('padding-bottom', '0px');
            });

            // Remove error border and message on select change
            $('select').on('change', function() {
                var select = $(this);
                select.removeClass('error-border');
                select.next('.text-danger').html('');

                // For Select2 elements, target the select2-container
                if (select.hasClass('select2-hidden-accessible')) {
                    select.next('.select2-container').find('.select2-selection').removeClass('error-border');
                }
            });

            // Remove error border and message on Select2 change
            $('select.select2-hidden-accessible').on('select2:select', function() {
                var select = $(this);
                select.removeClass('error-border');
                select.next('.select2-container').find('.select2-selection').removeClass('error-border');
                select.next('.text-danger').html('');
            });

            // Trigger change event for Select2 on initialization to remove any existing error-border
            $('select.select2-hidden-accessible').each(function() {
                $(this).trigger('change');
            });
        </script>

        <script>
            function addProduct() {
                const wrapper = document.getElementById('product-wrapper');
                const productItem = `
                    <div class="product-item row g-3 mb-3">
                        <div class="col-md-6 mb-2">
                            <input type="text" name="link_title[]" class="form-control"
                                placeholder="Enter a title">
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                            <input type="text" name="link[]" class="form-control"
                                placeholder="Link">
                                <button type="button" onclick="removeProduct(this)" class="btn btn-sm btn-icon btn-light-danger" style="margin-left:12px;">
                                <i class="ki-duotone ki-cross fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </button>
                           </div>
                        </div>
                    </div>
                `;
                wrapper.insertAdjacentHTML('beforeend', productItem);
            }

            function removeProduct(button) {
                button.closest('.product-item').remove();
            }
        </script>
    @endpush
</x-default-layout>
