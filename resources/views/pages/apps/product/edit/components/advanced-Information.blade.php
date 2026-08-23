<div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="card card-flush py-4">
            <div class="card-header">
                <div class="card-title">
                    <h2>Product Inventory</h2>
                </div>
            </div>
            <div class="card-body pt-0 pb-2">
                <div class=" fv-row">
                    <label class=" form-label">SKU</label>
                    <input type="text" name="sku_code" class="form-control mb-2" placeholder="SKU Number"
                        value="{{ $product->sku_code }}" />
                    <span id="sku_code" class="text-danger"></span>
                </div>
                <input type="hidden" name="quantity" value="{{ $product->quantity }}">
                <div class=" fv-row">
                    <label class="form-label">Expire Date</label>
                    @if (is_null($product->expire_date) || $product->expire_date > now())
                        <input class="form-control" id="kt_ecommerce_add_product_expire_datepicker"
                            placeholder="Pick date & time" name="expire_date" value="{{ $product->expire_date }}" />
                    @else
                        <input class="form-control" id="kt_ecommerce_add_product_expire_datepicker"
                            placeholder="Pick date & time" name="expire_date" />
                    @endif
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

            <!-- Hidden field for deleted variations -->
            <input type="hidden" name="deleted_variations" id="deleted_variations" value="">

            @if ($productStocks->count() > 0)
                <div class="card-body pt-0">
                    <div id="product-options-container">
                        @foreach ($productStocks as $index => $productStock)
                            <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $productStock->id }}" />

                            <div class="product_options mb-6" data-variation-id="{{ $productStock->id }}" style="{{ $loop->last ? '' : 'padding-bottom: 20px; border-bottom: 1px solid #eee;' }}">
                                <div class="row mb-4">
                                    @foreach ($attributes ?? [] as $attribute)
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">{{ $attribute->attr_name }}</label>
                                            <div class="d-flex align-items-center gap-1">
                                                <div>
                                                    <div class="form-check">
                                                        <input type="checkbox"
                                                            class="form-check-input attribute_id_item"
                                                            name="attributes[{{ $index }}][{{ $loop->index }}][attribute]"
                                                            value="{{ $attribute->id }}"
                                                            @if ($productStock->attributeOptions->contains('attribute_id', $attribute->id)) checked @endif />
                                                    </div>
                                                </div>
                                                <div class="attribute_value" style="width: 85%">
                                                    <select class="form-select value_id_item"
                                                        name="attributes[{{ $index }}][{{ $loop->index }}][attribute_value]"
                                                        data-placeholder="Select a value"
                                                        data-kt-ecommerce-catalog-add-product="product_option"
                                                        data-selected-value="{{ optional($productStock->attributeOptions->where('attribute_id', $attribute->id)->first())->attribute_value_id }}">
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
                                            name="variations[{{ $index }}][price]"
                                            value="{{ $productStock->price }}" placeholder="Enter price" />
                                    </div>

                                    <div class="col-md-4">
                                            <label class="form-label fw-semibold">Quantity</label>
                                            <input type="number" min="0" value="{{ $productStock->quantity }}"
                                                class="form-control bg-light" 
                                                name="variations[{{ $index }}][quantity]"
                                                readonly />
                                        </div>

                                    <div class="col-md-4">
                                        <input type="file" name="variations[{{ $index }}][image]"
                                            class="form-control" accept="image/*">
                                        @if ($productStock->image)
                                            <img src="{{ asset($productStock->image) }}" alt="Variation Image"
                                                width="50" class="mt-2 rounded">
                                            <input type="hidden"
                                                name="variations[{{ $index }}][existing_image]"
                                                value="{{ $productStock->image }}">
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-4">
                                    <input type="number" class="form-control mw-100 w-200px"
                                        name="variations[{{ $index }}][option_quantity]" placeholder="Quantity"
                                        value="{{ $productStock->quantity }}" hidden />
                                    <button type="button" data-repeater-delete=""
                                        class="btn btn-sm btn-icon btn-light-danger">
                                        <i class="ki-duotone ki-cross fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-light-primary" id="addAttr">
                                <i class="ki-duotone ki-plus fs-2"></i>Add another variation
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="card-body pt-0">
                    <div id="product-options-container">
                        <div class="product_options mb-6">
                            <input type="hidden" name="variations[0][id]" value="" />
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
                                    <input type="number" min="0" class="form-control"
                                        name="variations[0][quantity]" placeholder="Enter quantity" />
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Image</label>
                                    <input type="file" name="variations[0][image]" class="form-control"
                                        accept="image/*">
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-4">
                                <input type="number" class="form-control mw-100 w-200px"
                                    name="variations[0][option_quantity]" placeholder="Quantity"
                                    value="{{ $product->quantity }}" hidden />
                                <button type="button" data-repeater-delete=""
                                    class="btn btn-sm btn-icon btn-light-danger">
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
            @endif
        </div>

        <div class="card card-flush py-4">

            <div class="card-header">
                <div class="card-title">
                    <h2>Key Features</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div>
                    <div id="key_features" style="width: 100%" class="min-h-200px mb-2 ql-container ql-snow">
                        @php echo $product->key_features; @endphp
                    </div>
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
                    @foreach ($product->specifications->groupBy('group') as $groupName => $items)
                        <div class="specification-group mb-4 border p-3 rounded"
                            data-group-index="{{ $loop->index }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <input type="text" name="spec_group[]" class="form-control w-50"
                                    value="{{ $groupName }}" placeholder="Group (e.g. Display)">
                                <button type="button" onclick="removeGroup(this)"
                                    class="btn btn-sm btn-light-danger ms-2">Remove Group</button>
                            </div>

                            <div class="specification-items">
                                @foreach ($items as $item)
                                    <div class="row g-3 mb-2 specification-item">
                                        {{-- keep track of spec ID for update --}}
                                        <input type="hidden" name="spec_id[{{ $loop->parent->index }}][]"
                                            value="{{ $item->id }}">

                                        <div class="col-md-5">
                                            <input type="text" name="spec_name[{{ $loop->parent->index }}][]"
                                                class="form-control" value="{{ $item->name }}"
                                                placeholder="Specification Name">
                                        </div>

                                        <div class="col-md-5">
                                            <input type="text" name="spec_value[{{ $loop->parent->index }}][]"
                                                class="form-control" value="{{ $item->value }}"
                                                placeholder="Value">
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
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-sm btn-light-success mt-2"
                                onclick="addSpecification(this)">+ Add More Row</button>
                        </div>
                    @endforeach
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

                return {
                    init: function() {
                        initConditionsSelect2();
                    }
                };
            }();

            KTUtil.onDOMContentLoaded(function() {
                KTAppEcommerceSaveProduct.init();
            });

            let counter = {{ $productStocks->count() > 0 ? $productStocks->count() - 1 : 1 }};
            let qtyCounter = {{ $productStocks->count() > 0 ? $productStocks->count() - 1 : 1 }};

            // Function to attach events to product options
            function attachEvents($container) {
                $container.find(".attribute_id_item").each(function() {
                    let $checkbox = $(this);
                    let attributeId = $checkbox.val();
                    let $selectBox = $checkbox.closest('.d-flex').find('.attribute_value select');
                    let selectedValue = $selectBox.attr('data-selected-value');

                    if ($checkbox.is(':checked')) {
                        if (attributeId !== "" && attributeId !== "0") {
                            $.ajax({
                                url: '/admin/get-attribute-value/' + attributeId,
                                type: "GET",
                                dataType: "json",
                                success: function(data) {
                                    $selectBox.empty();
                                    if (data.length === 0) {
                                        $checkbox.prop('checked', false);
                                        toastr.warning('No value exists for the selected attribute.');
                                    } else {
                                        $.each(data, function(key, value) {
                                            let selected = (value.id == selectedValue) ? 'selected' : '';
                                            $selectBox.append('<option value="' + value.id + '" ' + selected + '>' + value.attr_value + '</option>');
                                        });
                                    }
                                },
                                error: function() {
                                    toastr.error('An error occurred while fetching attribute values.');
                                }
                            });
                        }
                    }
                });

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
                                    if (data.length === 0) {
                                        $checkbox.prop('checked', false);
                                        toastr.warning('No value exists for the selected attribute.');
                                    } else {
                                        $.each(data, function(key, value) {
                                            $selectBox.append('<option value="' + value.id + '">' + value.attr_value + '</option>');
                                        });
                                    }
                                },
                                error: function() {
                                    toastr.error('An error occurred while fetching attribute values.');
                                }
                            });
                        }
                    } else {
                        $selectBox.empty().append('<option></option>');
                    }
                });

                // DELETE FUNCTION - FIXED VERSION
                $container.find("[data-repeater-delete]").on("click", function() {
                    let $thisProductOptions = $(this).closest('.product_options');

                    // Try multiple ways to find the variation ID
                    let deletedId = $thisProductOptions.find('input[name*="[id]"]').val();

                    // If still not found, try by data attribute
                    if (!deletedId) {
                        deletedId = $thisProductOptions.data('variation-id');
                    }

                    console.log("Delete button clicked, Variation ID:", deletedId);

                    if (deletedId && deletedId !== '') {
                        let $deletedInput = $('#deleted_variations');
                        let currentVal = $deletedInput.val();
                        let newVal = currentVal ? currentVal + ',' + deletedId : deletedId;
                        $deletedInput.val(newVal);
                        console.log("Added to deleted_variations:", $deletedInput.val());
                    }

                    // Remove from UI
                    $thisProductOptions.fadeOut(300, function() {
                        $(this).remove();
                    });
                });
            }

            // Generate new product option HTML
            function generateNewOptionHtml(counter, qtyCounter) {
                return `
                    <div class="product_options mb-6" style="padding-top: 20px; border-top: 1px solid #eee;">
                        <input type="hidden" name="variations[${counter}][id]" value="" />
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

                            <div class="col-md-4">
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
                    </div>
                    `;
            }

            // Initially attach events to existing product options
            attachEvents($("#product-options-container"));

            // Add a new product option on button click
            $("#addAttr").on("click", function() {
                counter++;
                qtyCounter++;
                let $newProductOptions = $(generateNewOptionHtml(counter, qtyCounter));
                $newProductOptions.hide().insertBefore($(this).closest('.form-group')).slideDown('slow');

                attachEvents($newProductOptions);
                KTAppEcommerceSaveProduct.init();
            });

        });
    </script>

    {{-- specification --}}
    <script>
        (function() {
            const groups = Array.from(document.querySelectorAll('.specification-group'));

            if (groups.length && !groups[0].dataset.groupIndex) {
                // if server didn't set group-index, set sequential indexes
                groups.forEach((g, i) => g.dataset.groupIndex = i);
            }

            let groupIndex = groups.reduce((max, g) => Math.max(max, parseInt(g.dataset.groupIndex || -1, 10)), -1);

            // Add new group (with one default row)
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
                                <input type="hidden" name="spec_id[${idx}][]" value="">
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

            // Add row inside a group
            window.addSpecification = function(button) {
                const group = button.closest('.specification-group');
                const idx = group.dataset.groupIndex;
                const items = group.querySelector('.specification-items');

                const row = `
                    <div class="row g-3 mb-2 specification-item">
                        <input type="hidden" name="spec_id[${idx}][]" value="">
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
                const row = button.closest('.specification-item');
                if (row) row.remove();
            };

            // Remove a whole group
            window.removeGroup = function(button) {
                const group = button.closest('.specification-group');
                if (group) group.remove();
            };

            // If no groups present (create page or user removed all), ensure one default group exists
            if (document.querySelectorAll('.specification-group').length === 0) {
                addGroup();
            }
        })();
    </script>
@endpush
