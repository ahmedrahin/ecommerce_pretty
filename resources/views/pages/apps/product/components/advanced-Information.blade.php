<div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="card card-flush py-4">
            <div class="card-header">
                <div class="card-title">
                    <h2>Product Inventory</h2>
                </div>
            </div>
            <div class="card-body pt-0 pb-2">
                <div class="fv-row">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku_code" class="form-control mb-2" placeholder="SKU Number"
                        value="" />
                    <span id="sku_code" class="text-danger"></span>
                </div>
                <div class=" fv-row">
                    <label class="required form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control mb-2" placeholder="Product Quantity"
                        value="" />
                    <span id="quantity" class="text-danger"></span>

                </div>
                <div class=" fv-row">
                    <label class="form-label">Expire Date</label>
                    <input class="form-control" id="kt_ecommerce_add_product_expire_datepicker"
                        placeholder="Pick date & time" name="expire_date" value="{{ old('expire_date') }}" />
                    <span id="expire_date" class="text-danger"></span>

                </div>
            </div>
        </div>
        <div class="card card-flush py-4">
            <div class="card-header">
                <div class="card-title">
                    <h2>Product Variations</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div id="product-options-container">
                    <div class="product_options mb-6">
                        <div class="row mb-4">
                            @foreach ($attributes ?? [] as $attribute)
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">{{ $attribute->attr_name }}</label>
                                    <div class="d-flex align-items-center gap-1">
                                        <div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input attribute_id_item"
                                                    name="attributes[0][{{ $loop->index }}][attribute]"
                                                    value="{{ $attribute->id }}" />
                                            </div>
                                        </div>
                                        <div class="attribute_value" style="width: 85%">
                                            <select class="form-select value_id_item"
                                                name="attributes[0][{{ $loop->index }}][attribute_value]"
                                                data-placeholder="Select a variation"
                                                data-kt-ecommerce-catalog-add-product="product_option">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Printful Variant Id</label>
                                    <input type="number" min="0" class="form-control"
                                        name="variations[0][printful_variant_id]" placeholder="Enter Printful Variant Id" />
                                </div>
                            @endforeach
                        </div>

                        <div class="row mb-3" style="display: none;">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Price</label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    name="variations[0][price]" placeholder="Enter price" />
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Quantity</label>
                                <input type="number" min="0" class="form-control" name="variations[0][quantity]"
                                    placeholder="Enter quantity" />
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Image</label>
                                <input type="file" name="variations[0][image]" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-4 mt-2">
                            <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger">
                                <i class="ki-duotone ki-cross fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <button type="button" class="btn btn-sm btn-light-primary" id="addAttr">
                            <i class="ki-duotone ki-plus fs-2"></i>Add another variation
                        </button>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {
            var KTAppEcommerceSaveProduct = function() {

                // Init condition select2
                const initConditionsSelect2 = () => {
                    // Initialize all repeating condition types
                    const allConditionTypes = document.querySelectorAll(
                        '[data-kt-ecommerce-catalog-add-product="product_option"]');
                    allConditionTypes.forEach(type => {
                        if ($(type).hasClass("select2-hidden-accessible")) {
                            return;
                        } else {
                            $(type).select2({
                                minimumResultsForSearch: -1
                            });
                        }
                    });
                }

                // Public methods
                return {
                    init: function() {
                        initConditionsSelect2();
                    }
                };
            }();

            // Initialize select2 on document ready
            KTUtil.onDOMContentLoaded(function() {
                KTAppEcommerceSaveProduct.init();
            });

            let counter = 1;
            let qtyCounter = 1;

            // Function to attach events to product options
            function attachEvents($container) {
                $container.find(".attribute_id_item").on("change", function() {
                    let $checkbox = $(this);
                    let attributeId = $checkbox.val();
                    let $selectBox = $checkbox.closest('.d-flex').find('.attribute_value select');

                    if ($checkbox.is(':checked')) {
                        if (attributeId !== "" && attributeId !== "0") {
                            $.ajax({
                                url: '/admin/get-attribute-value/' + attributeId,
                                type: "GET",
                                dataType: "json",
                                success: function(data) {
                                    $selectBox.empty();

                                    // Check if data is empty (no attribute values)
                                    if (data.length === 0) {
                                        // Uncheck the checkbox and show a toastr notification
                                        $checkbox.prop('checked', false);
                                        toastr.warning(
                                            'No value exists for the selected attribute.');
                                    } else {
                                        // Populate the select box with attribute values
                                        $.each(data, function(key, value) {
                                            $selectBox.append('<option value="' + value
                                                .id + '">' + value.attr_value +
                                                '</option>');
                                        });
                                    }
                                },
                                error: function() {
                                    toastr.error(
                                        'An error occurred while fetching attribute values.'
                                    );
                                }
                            });
                        }
                    } else {
                        $selectBox.empty().append('<option></option>');
                    }
                });

                // Delete product option
                $container.find("[data-repeater-delete]").on("click", function() {
                    let $thisProductOptions = $(this).closest('.product_options');

                    $thisProductOptions.fadeOut(300, function() {
                        $(this).slideUp(300, function() {
                            $(this).remove();
                        });
                    });
                });
            }

            // Generate new product option HTML
            function generateNewOptionHtml(counter, qtyCounter) {
                return `
                    <div class="product_options mb-6" style="padding-top: 20px; border-top: 1px solid #eee;">
                        <div class="row mb-4">
                            @foreach ($attributes ?? [] as $attribute)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ $attribute->attr_name }}</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input attribute_id_item"
                                                    name="attributes[${counter}][{{ $loop->index }}][attribute]"
                                                    value="{{ $attribute->id }}" />
                                            </div>
                                        </div>
                                        <div class="attribute_value" style="width: 85%">
                                            <select class="form-select value_id_item"
                                                    name="attributes[${counter}][{{ $loop->index }}][attribute_value]"
                                                    data-placeholder="Select a variation"
                                                    data-kt-ecommerce-catalog-add-product="product_option">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Printful Variant Id</label>
                                        <input type="number" min="0" class="form-control"
                                            name="variations[${counter}][printful_variant_id]" placeholder="Enter Printful Variant Id" />
                                    </div>   
                            @endforeach
                        </div>

                        <div class="row mb-3" style="display:none;">
                            <div class="col-md-4" >
                                <label class="form-label fw-semibold">Price</label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    name="variations[${counter}][price]"
                                    placeholder="Enter price" />
                            </div>

                            <div class="col-md-4" >
                                <label class="form-label fw-semibold">Quantity</label>
                                <input type="number" min="0" class="form-control"
                                    name="variations[${counter}][quantity]"
                                    placeholder="Enter quantity" />
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Image</label>
                                <input type="file" class="form-control"
                                    name="variations[${counter}][image]" accept="image/*" />
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-4 mt-2">
                            <button type="button" data-repeater-delete=""
                                class="btn btn-sm btn-icon btn-light-danger">
                                <i class="ki-duotone ki-cross fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </button>
                        </div>
                    </div>`;
            }


            // Initially attach events to existing product options
            attachEvents($("#product-options-container"));

            // Add a new product option on button click
            $("#addAttr").on("click", function() {
                counter++;
                qtyCounter++;
                let $newProductOptions = $(generateNewOptionHtml(counter, qtyCounter));
                $newProductOptions.hide().insertBefore($(this).closest('.form-group')).slideDown('slow');

                // Re-attach events to the new option and reinitialize select2
                attachEvents($newProductOptions);
                KTAppEcommerceSaveProduct.init(); // Re-initialize select2 for new select elements
            });
        });
    </script>

@endpush
