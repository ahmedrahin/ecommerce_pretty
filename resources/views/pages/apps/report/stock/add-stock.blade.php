<x-default-layout>

    @section('title')
        Add new stock
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('addstock') }}
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

    <div class="row mt-10">
        <div class="col-md-8 offset-md-2">
            <div id="errors">
                <ul id="errors-msgs">
                </ul>
            </div>

            <form class="form" id="add_order_form">
                <div class="card card-flush py-4">
                    <div class="card-body pt-5">
                        <!--begin::Billing address-->
                        <div class="d-flex flex-column gap-5 gap-md-7">
                            <div class="fs-3 fw-bold mb-n2">Stock Product</div>

                            <div class="fv-row" id="add_stock">
                                <label class="fw-semibold fs-6 mb-2 required">Select Products</label>
                                <select id="products" name="product_id" class="form-select form-select-solid ">
                                </select>
                                <div id="product_id" class="text-danger"></div>
                            </div>

                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="fw-semibold fs-6 mb-2 required">Date</label>
                                    <input name="date" id="stockDate" placeholder="Select a date"
                                        class="form-control form-control-solid mb-0" />
                                    <div id="date" class="text-danger"></div>
                                </div>
                                <div class="fv-row flex-row-fluid">
                                    <label class="fw-semibold fs-6 mb-2 required">Quantity</label>
                                    <input type="number"  name="quantity" placeholder="Quantity"
                                        class="form-control form-control-solid mb-0" />
                                    <div id="quantity" class="text-danger"></div>
                                </div>
                                <div class="fv-row flex-row-fluid">
                                    <label class="fw-semibold fs-6 mb-2 required">Wholesale Price</label>
                                     <input type="number" name="wholesale_price"
                                        placeholder="Wholesale Product Price"
                                        class="form-control form-control-solid mb-0" />
                                    <div id="wholesale_price" class="text-danger"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="margin-top: 15px;">
                                <!--begin::Button-->
                                <a href="" id="kt_ecommerce_edit_order_cancel"
                                    class="btn btn-light me-5">Cancel</a>
                                <!--end::Button-->

                                <button type="submit" id="add_order" class="btn btn-primary" style="width: 200px;">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $('#stockDate').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d",
            });

            const selectElement = $('#products');
            selectElement.select2({
                placeholder: "Select a product",
                allowClear: true,
                dropdownParent: $("#add_stock"),
                ajax: {
                    url: "{{ route('products.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.name || item.text
                            }))
                        };
                    },
                    cache: true
                },
                width: '100%'
            });

        </script>

        <script>
            $('#add_order_form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route('report.store.stock') }}',
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

                        Swal.fire({
                            text: response.message,
                            icon: 'success',
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
    @endpush
</x-default-layout>
