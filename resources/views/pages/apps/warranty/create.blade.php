<x-default-layout>

    @section('title')
        Create Warranty
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('warranty') }}
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

        .product-info-items {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #DBDFE9;
        }
    </style>

    <div id="errors">
        <ul id="errors-msgs">

        </ul>
    </div>

    <!--begin::Form-->
    <form class="form" id="add_order_form">

        <div class="">
            <!--begin::Order details-->
            <div class="card card-flush py-4">

                <div class="card-body pt-5">
                    <!--begin::Billing address-->
                    <div class="d-flex flex-column gap-5 gap-md-7">
                        <div class="fs-3 fw-bold mb-n2">Contact Info</div>

                        {{-- <div class="fv-row flex-row-fluid">
                            <label class="form-label">Select User</label>
                            <select name="user_id" id="user_id_item" data-control="select2"
                                class="form-select form-select-solid mb-0" data-placeholder="Select a user">
                                <option></option>
                            </select>
                        </div> --}}

                        <div class="d-flex flex-column flex-md-row gap-5">
                            <div class="fv-row flex-row-fluid">
                                <label class="required form-label">Customer Name</label>
                                <input class="form-control" name="client_name" id="user_name" placeholder="Customer Name" />
                                <div id="client_name" class="text-danger"></div>
                            </div>
                            <div class="flex-row-fluid">
                                <label class="form-label">Email</label>
                                <input class="form-control" name="email" id="user_email"
                                    placeholder="Customer email" />
                                <div id="email" class="text-danger"></div>
                            </div>
                            <div class="fv-row flex-row-fluid">
                                <label class="required form-label">Phone no.</label>
                                <input class="form-control" name="mobile" id="user_mobile" placeholder="Phone no." />
                                <div id="mobile" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5">
                            <div class="fv-row flex-row-fluid">
                                <label class="required form-label">Sale Date</label>
                                <input placeholder="Select a date" name="sale_date"
                                    class="form-control dateFick mb-2" />
                                <div id="sale_date" class="text-danger"></div>
                            </div>
                            <div class="flex-row-fluid">
                                <label class="form-label">Warranty Provider</label>
                                <input class="form-control" name="provider" placeholder="Provider" />
                            </div>
                            <div class="flex-row-fluid">
                                <label class="form-label">Warranty Cost</label>
                                <input class="form-control" name="cost" placeholder="Warranty Cost" />
                            </div>
                            {{-- <div class="fv-row flex-row-fluid">
                                <label class="required form-label">Order ID</label>
                                <input class="form-control" name="order_id" placeholder="#A12GRLA" />
                                <div id="order_id" class="text-danger"></div>
                            </div> --}}
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5">
                            <div class="fv-row flex-row-fluid">
                                <label class=" form-label">Received By</label>
                                <input class="form-control" name="recive_by" placeholder="Received By" />
                            </div>
                            <div class="flex-row-fluid">
                                <label class=" form-label">Received Date</label>
                                <input placeholder="Select a date" name="date_of" class="form-control dateFick mb-2" />
                                <div id="date_of" class="text-danger"></div>
                            </div>
                        </div>


                        <div class="col-12 mt-0">
                            <div class="card radius-10 w-100">
                                <div class="card-body">
                                    <h5 style="margin-bottom: 20px;">Product Information</h5>
                                    <div id="product-wrapper">
                                        <div class="product-item row g-3 mb-3">
                                            <div class="col-md-6 mb-0">
                                                <input type="text" name="product_name[]" class="form-control"
                                                    placeholder="Product Name">
                                            </div>
                                            <div class="col-md-6 mb-0">
                                                <input type="text" name="serial_no[]" class="form-control"
                                                    placeholder="Serial No">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="remarks[]" class="form-control"
                                                    placeholder="Remarks">
                                            </div>
                                            <div class="col-md-6 ">
                                                <input type="text" name="model[]" class="form-control"
                                                    placeholder="Model">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="problem[]" class="form-control"
                                                    placeholder="Product Problem">
                                            </div>
                                            <div class="col-md-6 ">
                                                <input type="text" name="change[]" class="form-control"
                                                    placeholder="Product Change">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-light-success btn-sm mt-2"
                                        onclick="addProduct()">+ Add More Product</button>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end" style="margin-top: 15px;">
                            <!--begin::Button-->
                            <a href="" id="kt_ecommerce_edit_order_cancel"
                                class="btn btn-light me-5">Cancel</a>
                            <!--end::Button-->

                            <button type="submit" id="add_order" class="btn btn-primary" style="width: 200px;">
                                <span class="indicator-label">Save Warranty</span>
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
        <script>
            $('.dateFick').flatpickr({
                altInput: true,
                altFormat: "d F, Y",
                dateFormat: "Y-m-d"
            });
        </script>

        <script>
            $('#add_order_form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route('warranty.store') }}',
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
                        $('#add_order_form')[0].reset();
                        $('#add_order_form select').val('').trigger('change');

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
                                        <input type="text" name="product_name[]" class="form-control" placeholder="Product Name">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" name="serial_no[]" class="form-control" placeholder="Serial No">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="remarks[]" class="form-control" placeholder="Remarks">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="model[]" class="form-control" placeholder="Model">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="problem[]" class="form-control"
                                            placeholder="Product Problem">
                                    </div>
                                    <div class="col-md-6 ">
                                        <input type="text" name="change[]" class="form-control"
                                            placeholder="Product Change">
                                    </div>
                                </div>
                            `);

                        Swal.fire({
                            text: response.message,
                            icon: 'success',
                            buttonsStyling: false,
                            confirmButtonText: 'View',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open(response.redirect,
                                    '_blank');
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
                        <div class="product-item product-info-items row g-3 mb-3">
                            <div class="col-md-6 mb-0">
                                <input type="text" name="product_name[]" class="form-control" placeholder="Product Name">
                            </div>
                            <div class="col-md-6 mb-0">
                                <input type="text" name="serial_no[]" class="form-control" placeholder="Serial No">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="remarks[]" class="form-control" placeholder="Remarks">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="model[]" class="form-control" placeholder="Model">
                            </div>
                            <div class="col-md-6">
                                    <input type="text" name="problem[]" class="form-control"
                                        placeholder="Product Problem">
                                </div>
                                <div class="col-md-6 ">
                                    <div class="d-flex align-items-center">
                                    <input type="text" name="change[]" class="form-control"
                                        placeholder="Product Change">
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

        <script>
            $(document).ready(function() {
                $('#user_id_item').select2({
                    ajax: {
                        url: "{{ route('users.search') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    placeholder: "Select a user",
                    allowClear: true,
                    minimumInputLength: 1,
                    templateResult: function(user) {
                        if (!user.id) return user.text;
                        let phone = user.phone ? ' | ' + user.phone : '';
                        let email = user.email ? ' (' + user.email + ')' : '';
                        return $('<span>' + user.text + email + phone + '</span>');
                    },
                    templateSelection: function(user) {
                        return user.text || user.id;
                    }
                });

                $('#user_id_item').on('select2:select', function(e) {
                    let data = e.params.data;
                    $('#user_name').val(data.text).prop('readonly', true);
                    $('#user_email').val(data.email ?? '').prop('readonly', true);
                });

                $('#user_id_item').on('select2:clear', function(e) {
                    $('#user_name').val('').prop('readonly', false);
                    $('#user_email').val('').prop('readonly', false);
                    $('#user_mobile').val('').prop('readonly', false);
                });
            });
        </script>
    @endpush
</x-default-layout>
