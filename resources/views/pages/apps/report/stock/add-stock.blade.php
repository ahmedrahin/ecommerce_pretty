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
                        <div class="d-flex flex-column gap-5 gap-md-7">
                            <div class="fs-3 fw-bold mb-n2">Stock Product</div>

                            <div class="fv-row" id="add_stock">
                                <label class="fw-semibold fs-6 mb-2 required">Select Products</label>
                                <select id="products" name="product_id" class="form-select form-select-solid">
                                </select>
                                <div id="product_id_error" class="text-danger"></div>
                            </div>

                            <div class="fv-row flex-row-fluid" id="variant_wrapper" style="display:none;">
                                <label class="fw-semibold fs-6 mb-2">Variations</label>
                                <div id="variant_list" class="d-flex flex-column gap-3 mt-2"></div>
                                <div id="variants_error" class="text-danger"></div>
                            </div>

                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="fw-semibold fs-6 mb-2 required">Date</label>
                                    <input name="date" id="stockDate" placeholder="Select a date"
                                        class="form-control form-control-solid mb-0" />
                                    <div id="date_error" class="text-danger"></div>
                                </div>
                                <div class="fv-row flex-row-fluid" id="single_quantity_wrapper">
                                    <label class="fw-semibold fs-6 mb-2 required">Quantity</label>
                                    <input type="number" name="quantity" placeholder="Quantity"
                                        class="form-control form-control-solid mb-0" />
                                    <div id="quantity_error" class="text-danger"></div>
                                </div>
                                <div class="fv-row flex-row-fluid">
                                    <label class="fw-semibold fs-6 mb-2">Wholesale Price</label>
                                    <input type="number" name="wholesale_price" placeholder="Wholesale Product Price"
                                        class="form-control form-control-solid mb-0" />
                                    <div id="wholesale_price_error" class="text-danger"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="margin-top: 15px;">
                                <a href="" id="kt_ecommerce_edit_order_cancel" class="btn btn-light me-5">Cancel</a>
                                <button type="submit" id="add_order" class="btn btn-primary" style="width: 200px;">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Initialize flatpickr
                $('#stockDate').flatpickr({
                    enableTime: true,
                    dateFormat: "Y-m-d",
                });

                // Initialize Select2
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

                // Clear all errors function
                function clearAllErrors() {
                    $('#errors-msgs').empty().hide();
                    $('.text-danger').empty();
                    $('input, select').removeClass('error-border');
                    $('.select2-container .select2-selection').removeClass('error-border');
                    $('.variant-item').removeClass('border-danger');
                    $('.variant-qty-input').removeClass('error-border');
                    $('.variant-error-msg').remove();
                }

                // Show field error function
                function showFieldError(fieldId, message) {
                    $(`#${fieldId}_error`).text(message).css({
                        'padding-top': '8px',
                        'font-weight': '600',
                        'color': '#dc3545'
                    });
                }

                // Show variant error function
                function showVariantError(variantItem, message) {
                    // Remove existing error for this variant
                    variantItem.find('.variant-error-msg').remove();

                    // Add error class to border
                    variantItem.addClass('border-danger');

                    // Add error message
                    variantItem.append(`
                        <div class="variant-error-msg text-danger mt-2" style="font-size: 12px;">
                            ${message}
                        </div>
                    `);

                    // Add error class to quantity input
                    variantItem.find('.variant-qty-input').addClass('error-border');
                }

                // Clear variant errors function
                function clearVariantErrors() {
                    $('.variant-item').removeClass('border-danger');
                    $('.variant-qty-input').removeClass('error-border');
                    $('.variant-error-msg').remove();
                }

                // Product change — variant load
                $('#products').on('change', function() {
                    const productId = $(this).val();
                    $('#variant_wrapper').hide();
                    $('#variant_list').html('');
                    clearAllErrors();
                    clearVariantErrors();

                    // Show single quantity wrapper initially
                    $('#single_quantity_wrapper').show();
                    $('input[name="quantity"]').val('').removeAttr('disabled');

                    if (!productId) return;

                    $.get(`/admin/products/${productId}/variants`, function(data) {
                        if (data && data.length > 0) {
                            // Hide single quantity wrapper for variant products
                            $('#single_quantity_wrapper').hide();
                            $('input[name="quantity"]').val('').attr('disabled', 'disabled');

                            // Generate variant items
                            data.forEach((v, index) => {
                                $('#variant_list').append(`
                            <div class="variant-item border rounded p-3" data-variant-id="${v.id}" data-variant-index="${index}">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input type="checkbox"
                                        class="form-check-input variant-checkbox"
                                        id="variant_${v.id}"
                                        value="${v.id}"
                                        data-variant-id="${v.id}"
                                        data-label="${v.variation_label}"
                                        data-stock="${v.quantity}" />
                                    <label class="form-check-label fw-semibold" for="variant_${v.id}">
                                        ${v.variation_label}
                                        <span class="badge badge-light-primary ms-2">Stock: ${v.quantity}</span>
                                    </label>
                                </div>
                                <div class="variant-qty-wrapper ps-7" style="display:none;">
                                    <input type="number"
                                        class="form-control form-control-sm variant-qty-input mt-2"
                                        placeholder="Enter quantity" min="1" />
                                </div>
                            </div>
                        `);
                            });

                            $('#variant_wrapper').show();
                        } else {
                            // No variants - enable single quantity
                            $('#single_quantity_wrapper').show();
                            $('input[name="quantity"]').removeAttr('disabled');
                        }
                    }).fail(function() {
                        $('#single_quantity_wrapper').show();
                        $('input[name="quantity"]').removeAttr('disabled');
                    });
                });

                // Handle variant checkbox change
                $(document).on('change', '.variant-checkbox', function() {
                    const variantItem = $(this).closest('.variant-item');
                    const qtyWrapper = variantItem.find('.variant-qty-wrapper');
                    const qtyInput = variantItem.find('.variant-qty-input');

                    if ($(this).is(':checked')) {
                        qtyWrapper.show();
                        // Remove error if user is trying to fix
                        variantItem.removeClass('border-danger');
                        variantItem.find('.variant-error-msg').remove();
                        qtyInput.removeClass('error-border');
                    } else {
                        qtyWrapper.hide();
                        qtyInput.val('');
                        // Clear error when unchecked
                        variantItem.removeClass('border-danger');
                        variantItem.find('.variant-error-msg').remove();
                        qtyInput.removeClass('error-border');
                    }
                });

                // Real-time validation for variant quantity inputs
                $(document).on('input', '.variant-qty-input', function() {
                    const variantItem = $(this).closest('.variant-item');
                    const quantity = $(this).val();

                    if (quantity && parseInt(quantity) > 0) {
                        // Remove error if quantity is valid
                        variantItem.removeClass('border-danger');
                        variantItem.find('.variant-error-msg').remove();
                        $(this).removeClass('error-border');
                    } else {
                        // Add error if quantity is invalid but checkbox is checked
                        const checkbox = variantItem.find('.variant-checkbox');
                        if (checkbox.is(':checked') && (!quantity || parseInt(quantity) <= 0)) {
                            variantItem.addClass('border-danger');
                            $(this).addClass('error-border');
                            if (!variantItem.find('.variant-error-msg').length) {
                                variantItem.append(`
                            <div class="variant-error-msg text-danger mt-2" style="font-size: 12px;">
                                <i class="fas fa-exclamation-circle"></i> Quantity is required and must be greater than 0
                            </div>
                        `);
                            }
                        }
                    }
                });

                // Form submit handler
                $('#add_order_form').on('submit', function(e) {
                    e.preventDefault();

                    // Clear previous errors
                    clearAllErrors();
                    clearVariantErrors();

                    var formData = new FormData();

                    // Get basic fields
                    const productId = $('#products').val();
                    const date = $('#stockDate').val();
                    const wholesalePrice = $('input[name="wholesale_price"]').val();

                    // Validate basic fields
                    let hasError = false;

                    if (!productId) {
                        showFieldError('product_id', 'Please select a product');
                        $('#products').next('.select2-container').find('.select2-selection').addClass('error-border');
                        hasError = true;
                    }

                    if (!date) {
                        showFieldError('date', 'Please select a date');
                        $('input[name="date"]').addClass('error-border');
                        hasError = true;
                    }

                    // Check if product has variants
                    const hasVariants = $('#variant_wrapper').is(':visible') && $('#variant_list').children().length > 0;

                    if (hasVariants) {
                        // Validate variants
                        let variantIndex = 0;
                        let hasValidVariant = false;
                        let variantErrors = [];

                        // Clear any existing variant selection error
                        $('#variants_error').empty();

                        $('.variant-checkbox:checked').each(function() {
                            const $checkbox = $(this);
                            const $variantItem = $checkbox.closest('.variant-item');
                            const $qtyInput = $variantItem.find('.variant-qty-input');
                            const variantId = $checkbox.val();
                            const quantity = $qtyInput.val();

                            if (!quantity || parseInt(quantity) < 1) {
                                // Show error for this variant
                                hasError = true;
                                showVariantError($variantItem, 'Quantity is required and must be greater than 0');
                                variantErrors.push(`Variant ${$checkbox.next('label').text()} requires quantity`);
                            } else {
                                // Add valid variant data
                                formData.append(`variants[${variantIndex}][id]`, variantId);
                                formData.append(`variants[${variantIndex}][quantity]`, quantity);
                                variantIndex++;
                                hasValidVariant = true;
                            }
                        });

                        // Check if at least one variant is selected
                        if ($('.variant-checkbox:checked').length === 0) {
                            hasError = true;
                            $('#variants_error').text('Please select at least one variation').css({
                                'padding-top': '8px',
                                'font-weight': '600',
                                'color': '#dc3545'
                            });
                        } else if (!hasValidVariant) {
                            hasError = true;
                            if (variantErrors.length > 0) {
                                $('#variants_error').text('Please enter valid quantity for selected variations').css({
                                    'padding-top': '8px',
                                    'font-weight': '600',
                                    'color': '#dc3545'
                                });
                            }
                        }
                    } else {
                        // Validate single product quantity
                        const quantity = $('input[name="quantity"]').val();
                        if (!quantity || parseInt(quantity) < 1) {
                            hasError = true;
                            showFieldError('quantity', 'Quantity is required and must be greater than 0');
                            $('input[name="quantity"]').addClass('error-border');
                        } else {
                            formData.append('quantity', quantity);
                        }
                    }

                    // Add wholesale price if exists
                    if (wholesalePrice && parseFloat(wholesalePrice) >= 0) {
                        formData.append('wholesale_price', wholesalePrice);
                    }

                    // Add basic fields to formData
                    formData.append('product_id', productId);
                    formData.append('date', date);

                    // If there are errors, show them and stop submission
                    if (hasError) {
                        // Scroll to errors
                        $('html, body').animate({
                            scrollTop: $('#errors').offset().top - 100
                        }, 500);

                        // Show error summary
                        $('#errors-msgs').show();
                        Swal.fire({
                            text: 'Please fix the errors before submitting',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    // Submit form
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

                            // Reset form
                            $('#add_order_form')[0].reset();
                            $('#products').val('').trigger('change');
                            $('#variant_wrapper').hide();
                            $('#variant_list').html('');
                            $('#single_quantity_wrapper').show();
                            $('input[name="quantity"]').removeAttr('disabled');
                            clearAllErrors();
                            clearVariantErrors();

                            Swal.fire({
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(xhr) {
                            $('#add_order .indicator-progress').hide();
                            $('#add_order .indicator-label').show();

                            // Clear previous errors
                            clearAllErrors();
                            clearVariantErrors();

                            // Scroll to error messages
                            $('html, body').animate({
                                scrollTop: $('#errors').offset().top - 70
                            }, 500);

                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                // Display validation errors from server
                                $('#errors-msgs').show();

                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    // Add to summary
                                    $('#errors-msgs').append(`
                                <li>
                                    <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5">
                                        <i class="ki-duotone ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                        <div class="d-flex flex-column pe-0 pe-sm-10">
                                            <h5 class="mb-1">${value[0]}</h5>
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

                                    // Handle field-specific errors
                                    if (key === 'product_id') {
                                        showFieldError('product_id', value[0]);
                                        $('#products').next('.select2-container').find(
                                            '.select2-selection').addClass('error-border');
                                    } else if (key === 'date') {
                                        showFieldError('date', value[0]);
                                        $('input[name="date"]').addClass('error-border');
                                    } else if (key === 'quantity') {
                                        showFieldError('quantity', value[0]);
                                        $('input[name="quantity"]').addClass('error-border');
                                    } else if (key === 'wholesale_price') {
                                        showFieldError('wholesale_price', value[0]);
                                        $('input[name="wholesale_price"]').addClass('error-border');
                                    } else if (key.includes('variants')) {
                                        $('#variants_error').text('Please check variant quantities')
                                            .css({
                                                'padding-top': '8px',
                                                'font-weight': '600',
                                                'color': '#dc3545'
                                            });
                                    }
                                });
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                Swal.fire({
                                    text: xhr.responseJSON.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    text: 'An error occurred. Please try again.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                });

                // Remove validation classes on input
                $(document).on('input', 'input', function() {
                    $(this).removeClass('error-border');
                    $(this).closest('.fv-row').find('.text-danger').empty();
                });

                $(document).on('change', 'select', function() {
                    $(this).removeClass('error-border');
                    $(this).next('.select2-container').find('.select2-selection').removeClass('error-border');
                    $(this).closest('.fv-row').find('.text-danger').empty();
                });
        </script>
    @endpush
</x-default-layout>