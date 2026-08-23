<x-default-layout>
    @section('title') Edit Product @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('product') }}
    @endsection

    <style>
        .text-danger{ display: inline-block;}
        #remove_thumb, #back_thumb{
            position: absolute;
            left: 100%;
            top: 100%;
            transform: translate(-50%, -50%);
        }
        .select2-container--bootstrap5 .select2-selection__clear{
            top: 37%;
        }
        .error-border {
            border: 1px solid #f1416c !important;
        }
        span.text-danger{
            padding-top: 4px;
        }
        div.text-danger{
            margin-top: -10px;
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
        .dropzone .dz-preview .dz-image img{
            width: 100% !important;
            height: 100%;
            object-fit: cover;
        }
    </style>
    <div id="kt_ecommerce_add_product">
        <div id="errors">
            <ul id="errors-msgs">

            </ul>
        </div>
        <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row" enctype="multipart/form-data">
            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                @include('pages.apps.product.edit.components.thumbnail')
                @include('pages.apps.product.edit.components.product-detail')
            </div>
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">General Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">Advanced</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                            href="#filter">Category Filter</a>
                    </li>
                </ul>
                <div class="tab-content">
                    @include('pages.apps.product.edit.components.general-Information')
                    @include('pages.apps.product.edit.components.advanced-Information')
                    @include('pages.apps.product.edit.components.filter')
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" id="add_product_submit" class="btn btn-primary mt-8" style="width: 200px;">
                        <span class="indicator-label">Save Changes</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>
        </form>
    </div>



    @push('scripts')
    <script>
       $(document).ready(function() {

        $('.d-flex.align-items-center.gap-2.gap-lg-3').html(`<a href="{{ route('product-management.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left fs-2"></i>
                All Product
            </a>`);


            function updateDescriptions() {
                // Short Description
                var shortDescriptionHtml = $('#product_short_description .ql-editor').html();
                var $shortInput = $('input[name="short_description"]');
                $shortInput.val(shortDescriptionHtml);

                // Long Description
                var longDescriptionHtml = $('#product_long_description .ql-editor').html();
                var $longInput = $('input[name="long_description"]');
                $longInput.val(longDescriptionHtml);

                 var key_featuresDescriptionHtml = $('#key_features .ql-editor').html();
                    var $key_featuresInput = $('input[name="key_features"]');
                    $key_featuresInput.val(key_featuresDescriptionHtml);
            }

            $('#kt_ecommerce_add_product_form').on('submit', function(e) {
                e.preventDefault();

                // Update the input fields with the latest description values
                updateDescriptions();

                // Create FormData with the updated input values
                var formData = new FormData(this);

                $.ajax({
                    url: '{{ route("product-management.update", $product->id) }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#add_product_submit .indicator-progress').show();
                        $('#add_product_submit .indicator-label').hide();
                    },
                    success: function(response) {
                        $('#add_product_submit .indicator-progress').hide();
                        $('#add_product_submit .indicator-label').show();
                        var productId = response.product;
                        Livewire.emit('save', productId);

                        // Redirect or perform other actions upon success
                        toastr.success('Product updated successfully')
                        let url = '{{ route("product-management.index") }}';
                        setTimeout(() => {
                            window.location.href = url;
                        }, 300);
                    },
                    error: function(xhr) {
                        $('#add_product_submit .indicator-progress').hide();
                        $('#add_product_submit .indicator-label').show();

                        $('#errors-msgs').empty();
                        $('#errors-msgs').css('display', 'block')
                        let scrolled = false;
                        if (!scrolled) {
                            $('html, body').animate({
                                scrollTop: $('#errors-msgs').offset().top - 70
                            }, 500);
                            scrolled = true;
                        }


                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Clear any previous error messages
                            $('#errors-msgs').empty();

                            // Loop through the errors and display them
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                // Show error message in the alert
                                $('#errors-msgs').append(`
                                    <li>
                                        <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5">
                                            <i class="ki-duotone ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                                <h5 class="mb-1">${value}</h5>
                                                <span>Please fill up the field with valid data!</span>
                                            </div>
                                            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                                <i class="ki-duotone ki-cross fs-1 text-danger">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </div>
                                    </li>
                                `);

                                // Handle normal input fields
                                $('#' + key).text(value[0]);
                                $('#' + key).css('padding-bottom', '15px');
                                $('input[name="' + key + '"]').addClass('error-border');

                                // Handle select fields
                                var $select = $('select[name="' + key + '"]');
                                $select.addClass('error-border');

                                // For select2 elements, target the select2-container
                                if ($select.hasClass('select2-hidden-accessible')) {
                                    $select.next('.select2-container').find('.select2-selection').addClass('error-border');
                                }

                                // Handle repeater list input fields
                                if (key.includes('kt_ecommerce_add_product_options')) {
                                    // Extract the field name and the index
                                    var parts = key.split('.');
                                    var index = parts[1];
                                    var fieldName = parts[2];

                                    // Find the corresponding input/select within the repeater item
                                    $('input[name="kt_ecommerce_add_product_options[' + index + '][' + fieldName + ']"]').addClass('error-border');
                                    var $repeaterSelect = $('select[name="kt_ecommerce_add_product_options[' + index + '][' + fieldName + ']"]');
                                    $repeaterSelect.addClass('error-border');

                                    // For select2 elements in repeater items
                                    if ($repeaterSelect.hasClass('select2-hidden-accessible')) {
                                        $repeaterSelect.next('.select2-container').find('.select2-selection').addClass('error-border');
                                    }
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
            $('#kt_ecommerce_add_product_form input').on('input', function() {
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

        });

    </script>
    <script src="{{asset('assets/js/custom/apps/ecommerce/catalog/save-product.js')}}"></script>
    @endpush

</x-default-layout>
