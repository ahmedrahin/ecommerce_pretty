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

                <div class="mb-6 fv-row">
                    <div id="main-quantity-wrapper">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="main_quantity" class="form-control" min="1" value="{{ old('quantity') }}" />
                    </div>
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
                                                data-placeholder="Select a value"
                                                data-kt-ecommerce-catalog-add-product="product_option">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4" style="display: none;">
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

        <div class="card card-flush py-4">

            <div class="card-header">
                <div class="card-title">
                    <h2>Key Features</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div>
                    <div id="key_features" style="width: 100%" class="min-h-200px mb-2 ql-container ql-snow"></div>
                    <input name="key_features" hidden>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="tab-pane fade" id="specification" role="tab-panel">
    <div class="col-12 mt-0">
        <div class="card radius-10 w-100">
            <div class="card-body">
                <h5 style="margin-bottom: 20px;">Product Specifications</h5>

                <div id="specification-wrapper">
                    <div class="specification-group mb-4 border p-3 rounded" data-group-index="0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <input type="text" name="spec_group[]" class="form-control w-50"
                                placeholder="Group (e.g. Processor)">
                            <button type="button" onclick="removeGroup(this)"
                                class="btn btn-sm btn-light-danger ms-2">Remove Group</button>
                        </div>

                        <div class="specification-items">
                            <div class="row g-3 mb-2 specification-item">
                                <div class="col-md-5">
                                    <input type="text" name="spec_name[0][]" class="form-control"
                                        placeholder="Specification Name (e.g. Processor Brand)">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="spec_value[0][]" class="form-control"
                                        placeholder="Value (e.g. AMD)">
                                </div>
                                <div class="col-md-2 d-flex align-items-center">
                                    <button type="button" onclick="removeSpecification(this)"
                                        class="btn btn-sm btn-icon btn-light-danger">
                                        <i class="ki-duotone ki-cross fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-light-success mt-2"
                            onclick="addSpecification(this)">+ Add More Row</button>
                    </div>
                </div>

                <button type="button" class="btn btn-light-primary btn-sm mt-3" onclick="addGroup()">+ Add New
                    Group</button>
            </div>
        </div>
    </div>


</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            function updateMainQuantityVisibility() {
                const anyChecked = document.querySelectorAll('.attribute_id_item:checked').length > 0;
                const wrapper = document.getElementById('main-quantity-wrapper');
                const mainQty = document.getElementById('main_quantity');

                if (anyChecked) {
                    wrapper.style.display = 'none';
                    mainQty.removeAttribute('required');
                    mainQty.value = '';
                } else {
                    wrapper.style.display = 'block';
                }
            }

            document.getElementById('product-options-container').addEventListener('change', function(e) {
                if (e.target.classList.contains('attribute_id_item')) {
                    updateMainQuantityVisibility();
                }
            });

            var KTAppEcommerceSaveProduct = function() {

                const initConditionsSelect2 = () => {
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
                            @endforeach
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4" style="display: none;">
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

    <script>
        (function() {
            let groupIndex = Array.from(document.querySelectorAll('.specification-group'))
                .reduce((max, g) => Math.max(max, parseInt(g.dataset.groupIndex || '-1', 10)), -1);

            // Add a new specification group
            window.addGroup = function() {
                const idx = ++groupIndex;
                const wrapper = document.getElementById('specification-wrapper');

                const html = `
                <div class="specification-group mb-4 border p-3 rounded" data-group-index="${idx}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <input type="text" name="spec_group[]" class="form-control w-50" placeholder="Group (e.g. Display)">
                        <button type="button" onclick="removeGroup(this)" class="btn btn-sm btn-light-danger ms-2">Remove Group</button>
                    </div>

                    <div class="specification-items">
                        <div class="row g-3 mb-2 specification-item">
                            <div class="col-md-5">
                                <input type="text" name="spec_name[${idx}][]" class="form-control" placeholder="Specification Name">
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="spec_value[${idx}][]" class="form-control" placeholder="Value">
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <button type="button" onclick="removeSpecification(this)" class="btn btn-sm btn-icon btn-light-danger">
                                    <i class="ki-duotone ki-cross fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-light-success mt-2" onclick="addSpecification(this)">+ Add More Row</button>
                </div>
            `;

                wrapper.insertAdjacentHTML('beforeend', html);
            };

            // Add a new row inside a group
            window.addSpecification = function(button) {
                const group = button.closest('.specification-group');
                const idx = group.dataset.groupIndex; // reliable index from dataset
                const items = group.querySelector('.specification-items');

                const row = `
                <div class="row g-3 mb-2 specification-item">
                    <div class="col-md-5">
                        <input type="text" name="spec_name[${idx}][]" class="form-control" placeholder="Specification Name">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="spec_value[${idx}][]" class="form-control" placeholder="Value">
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" onclick="removeSpecification(this)" class="btn btn-sm btn-icon btn-light-danger">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                    </div>
                </div>
            `;
                items.insertAdjacentHTML('beforeend', row);
            };

            // Remove a row
            window.removeSpecification = function(button) {
                button.closest('.specification-item').remove();
            };

            // Remove a whole group
            window.removeGroup = function(button) {
                button.closest('.specification-group').remove();
            };
        })();
    </script>
@endpush
