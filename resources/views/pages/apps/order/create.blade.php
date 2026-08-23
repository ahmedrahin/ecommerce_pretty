<x-default-layout>

    @section('title') Add New Order @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('addOrder') }}

    @endsection
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        div.dataTables_scrollBody{
            border-left: none !important;
        }
        .position-relative.d-flex button {
            width: 25px !important;
            height: 25px !important;
        }
        .ki-minus , .ki-plus {
            font-weight: 700;
        }
        input.fs-3 {
            font-size: 15px !important;
        }
        .error-border {
            border: 1px solid #f1416c !important;
        }
        span.text-danger{
            padding-top: 4px;
        }
        div.text-danger{
            padding-top: 0;
        }
        #errors-msgs{
            padding: 15px 0;
            /* width: 65%; */
            display: none;
        }
        #errors-msgs li {
            list-style: none;
        }
        .form-check.form-check-sm .form-check-input{
            height: 1.35rem !important;
            width: 1.35rem !important;
        }
    </style>

        <div id="errors">
            <ul id="errors-msgs">

            </ul>
        </div>

        <!--begin::Form-->
        <form  class="form d-flex flex-column flex-lg-row" id="add_order_form">
            <!--begin::Aside column-->
            <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
                <!--begin::Order details-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Order Details</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column gap-7">
                            <!--begin::Input group-->
                            {{-- <div class="fv-row">
                                <label class="form-label">Order ID</label>
                                <div class="fw-bold fs-3">#13621</div>
                            </div> --}}

                            <div class="fv-row" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                                <label class="required form-label">Payment Method</label>
                                <!--begin::Col-->
                                <div class="col-md-4 col-lg-12">
                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary active d-flex text-start py-4 px-6 align-items-center" data-kt-button="true">
                                        <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input" type="radio" name="payment_type" value="cod" checked="checked" />
                                        </span>

                                        <span class="ms-3">
                                            <span class="fs-5 fw-bold mb-1 d-block" style="margin-bottom: 0 !important;">
                                                Cash on Delivery
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <!--end::Col-->
                                <div class="col-md-4 col-lg-12 mt-4">
                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start py-4 px-6 align-items-center" data-kt-button="true">
                                        <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input" type="radio" name="payment_type" value="bksh"  />
                                        </span>
                                        <span class="ms-3">
                                            <span class="fs-5 fw-bold mb-1 d-block" style="margin-bottom: 0 !important;">
                                                Bkash
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                 <div class="col-md-4 col-lg-12 mt-4">
                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start py-4 px-6 align-items-center" data-kt-button="true">
                                        <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input" type="radio" name="payment_type" value="bank"  />
                                        </span>
                                        <span class="ms-3">
                                            <span class="fs-5 fw-bold mb-1 d-block" style="margin-bottom: 0 !important;">
                                                Credit/Master Card
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div id="payment_type" class="text-danger"></div>
                            </div>

                            <div class="fv-row">
                                <label class="form-label">Transaction Id</label>
                                <input  name="transaction_id" placeholder="e.g BLC41VBEHKq" class="form-control mb-0" />
                            </div>

                            <div class="fv-row">
                                <label class="form-label">Payment Details</label>
                                <textarea name="payment_from" class="form-control" placeholder="e.g bank name or details"></textarea>
                            </div>

                            <div class="fv-row">
                                <label class="form-label">Shipping Method</label>
                                <select class="form-select mb-0" data-control="select2" data-hide-search="true" data-placeholder="Select an option" name="shipping_method" id="kt_ecommerce_edit_order_shipping">
                                    <option></option>
                                    @foreach($ShippingMethods as $ShippingMethod)
                                        <option value="{{ $ShippingMethod->id }}" data-cost="{{ $ShippingMethod->base_id ? $ShippingMethod->base_charge : $ShippingMethod->provider_charge }}">
                                            @if($ShippingMethod->base_id)
                                                Inside {{ $ShippingMethod->District->name }} - {{ $ShippingMethod->base_charge }}tk
                                            @else
                                                {{ $ShippingMethod->provider_name }} - {{ $ShippingMethod->provider_charge }}tk
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div id="shipping_method" class="text-danger"></div>
                                <input type="hidden" name="shipping_cost" id="shipping_cost">
                            </div>

                            <div class="fv-row">
                                <label class="required form-label">Order Date</label>
                                <input id="kt_ecommerce_edit_order_date" name="order_date" placeholder="Select a date" class="form-control mb-0" value="" />
                                <div id="order_date" class="text-danger"></div>
                            </div>

                            <div class="fv-row">
                                <label class="form-label">Order Discount</label>
                                <input  name="discount_amount" placeholder="" class="form-control mb-0" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
                <!--begin::Order details-->
                <livewire:order.select-product></livewire:order.select-product>
                <!--end::Order details-->

                <!--begin::Order details-->
                <div class="card card-flush py-4">

                    <div class="card-body pt-5">
                        <!--begin::Billing address-->
                        <div class="d-flex flex-column gap-5 gap-md-7">
                            <div class="fs-3 fw-bold mb-n2">Contact Info</div>

                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="required form-label">Customer Name</label>
                                    <input class="form-control" name="name" placeholder="Customer Name" />
                                    <div id="name" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="flex-row-fluid">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" name="email" placeholder="Customer email" />
                                    <div id="email" class="text-danger"></div>
                                </div>
                                <div class="fv-row flex-row-fluid">
                                    <label class="required form-label">Phone no.</label>
                                    <input class="form-control" name="phone" placeholder="Phone no." />
                                    <div id="phone" class="text-danger"></div>
                                </div>
                            </div>

                            <div class="fs-3 fw-bold mb-n2">Shipping Info</div>
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="flex-row-fluid">
                                    <!--begin::Label-->
                                    <label class="required form-label">Shipping Address</label>
                                    <input class="form-control" name="shipping_address" placeholder="Shipping Address" value="" />
                                    <div id="shipping_address" class="text-danger"></div>
                                </div>
                                <div class="flex-row-fluid">
                                    <label class="required form-label">City</label>
                                    <select class="form-select" data-placeholder="Select a city" data-control="select2" id="kt_ecommerce_order_district" name="district_id">
                                        <option></option>
                                        @foreach( $districts as $district )
                                            <option value="{{$district->id}}">{{$district->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="district_id" class="text-danger"></div>
                                </div>

                            </div>

                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="flex-row-fluid">
                                    <label class="form-label">Note (optional)</label>
                                    <textarea class="form-control" id="kt_docs_maxlength_textarea" maxlength="100" placeholder="Write a note..." rows="6" name="note"></textarea>
                                </div>
                            </div>


                        </div>
                        <!--end::Billing address-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Order details-->
                <div class="d-flex justify-content-end">
                    <!--begin::Button-->
                    <a href="" id="kt_ecommerce_edit_order_cancel" class="btn btn-light me-5">Cancel</a>
                    <!--end::Button-->

                    <button type="submit" id="add_order" class="btn btn-primary" style="width: 200px;">
                        <span class="indicator-label">Publish</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>

        </form>
        <!--end::Form-->


    @push('scripts')
        <script>

            function handleCheckboxChange(checkbox, productName) {
                if (checkbox.checked) {
                    toastr.success('The product "' + productName + '" has been added to your cart.');
                } else {
                    toastr.info('The product "' + productName + '" has been removed from your cart.');
                }
            }

            $('#kt_docs_maxlength_textarea').maxlength({
                warningClass: "badge badge-primary",
                limitReachedClass: "badge badge-success"
            });

            // Init flatpickr
            $('#kt_ecommerce_edit_order_date').flatpickr({
                altInput: true,
                altFormat: "d F, Y",
                dateFormat: "Y-m-d",
            });
        </script>

        <script>
            $('#kt_ecommerce_edit_order_shipping').on('change', function() {
                var shippingCost = $(this).find(':selected').data('cost');
                $('#shipping_cost').val(shippingCost);
            });

            $('#add_order_form').on('submit', function(e) {
                e.preventDefault();
                let shippingCost = $('#kt_ecommerce_edit_order_shipping option:selected').data('cost');
                $('#shipping_cost').val(shippingCost);

                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route("order-management.order.store") }}',
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

                        // Show success message using SweetAlert
                        Swal.fire({
                            text: response.message,
                            icon: 'success',
                            buttonsStyling: false,
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });

                        // cart-items
                        $('#cart-items').html('<span class="w-100 text-muted">Select one or more products from the list below by ticking the checkbox.</span>');
                        $('#kt_ecommerce_edit_order_total_qty').html('0');
                        $('#kt_ecommerce_edit_order_total_price').html('0.00৳');
                        $('input[type="checkbox"]:checked').prop('checked', false);
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
                                    $select.next('.select2-container').find('.select2-selection').addClass('error-border');
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
