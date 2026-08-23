<div class="card card-flush py-4">
    <div class="card-header">
        <div class="card-title">
            <h2>Product Details</h2>
        </div>
    </div>
    <div class="card-body pt-0 pb-0">

        <label class="form-label">Category</label>
        <select name="category_id" id="category_id_item" data-control="select2"
            class="form-select form-select-solid mb-5" data-placeholder="Select a category">
            <option></option>
            @foreach($categories ?? [] as $category)
            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }} >{{
                $category->name }}</option>
            @endforeach
        </select>
        <span id="category_id" class="text-danger"></span>

        <label class="form-label">Subcategory (Multiple)</label>
        <select name="subcategory_id[]" id="subcategory_id_item" data-control="select2"
            class="form-select form-select-solid mb-5" data-placeholder="Select subcategories" 
            data-allow-clear="true" multiple="multiple">
            <option></option>
        </select>
        <span id="subcategory_id" class="text-danger"></span>

        {{-- <label class="form-label">Subsubcategory (Multiple)</label>
        <select name="subsubcategory_id[]" id="subsubcategory_id_item" data-control="select2"
            class="form-select form-select-solid mb-5" data-placeholder="Select subsubcategories"
            data-allow-clear="true" multiple="multiple">
            <option></option>
        </select>
        <span id="subsubcategory_id" class="text-danger"></span> --}}

        <label class="form-label">Brand</label>
        <select name="brand_id" data-control="select2" class="form-select form-select-solid mb-5"
            data-placeholder="Select a brand" data-allow-clear="true">
            <option></option>
        </select>
        <span id="brand_id" class="text-danger"></span>

        <label class="form-label d-block">Tags</label>
        <input id="kt_tagify_for_product" class="form-control mb-2" value="{{ json_encode($tagItem) }}" />
        <span id="tags" class="text-danger"></span>

        <div class="mb-5">
            <label class="form-label d-block">Filtering</label>
            <div class="form-check form-check-custom form-check-solid mb-3">
                <input class="form-check-input" type="checkbox" id="is_new" name="is_new" value="1" {{ $product->is_new
                == 1 ? 'checked' : '' }}>
                <label for="is_new" class="form-check-label">set as new product</label>
            </div>

            <div class="form-check form-check-custom form-check-solid mb-2">
                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{
                    $product->is_featured == 1 ? 'checked' : '' }}>
                <label for="is_featured" class="form-check-label">set as featured product</label>
            </div>

            <div class="form-check form-check-custom form-check-solid mb-2">
                <input class="form-check-input" type="checkbox" id="preorder" name="preorder" value="1" {{
                    $product->pre_order == 1 ? 'checked' : '' }}>
                <label for="preorder" class="form-check-label">set as up coming product</label>
            </div>
            <div class="form-check form-check-custom form-check-solid mb-2">
                <input class="form-check-input" type="checkbox" id="stock_out" name="stock_out" value="1" {{
                    $product->stock_out == 1 ? 'checked' : '' }}>
                <label for="stock_out" class="form-check-label">set as stock out</label>
            </div>
        </div>

    </div>
</div>

<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title">
            <h2>Status</h2>
        </div>
        <div class="card-toolbar">
            <div class="rounded-circle bg-success w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
        </div>
    </div>
    <div class="card-body pt-0 pb-7">
        <select class="form-select mb-2" data-control="select2" data-hide-search="true"
            data-placeholder="Select an option" id="kt_ecommerce_add_product_status_select" name="status">
            <option></option>
            <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Published</option>
            <option value="2" {{ $product->status == 2 ? 'selected' : '' }}>Draft</option>
            <option value="3" {{ $product->status == 3 ? 'selected' : '' }}>Scheduled</option>
            <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
        <div class="text-muted fs-7">Set the product status.</div>

        <div class="d-none mt-7">
            <label for="kt_ecommerce_add_product_status_datepicker" class="form-label">Select publishing date and
                time</label>
            <input class="form-control" id="kt_ecommerce_add_product_status_datepicker" placeholder="Pick date & time"
                name="publish_at" value="{{ $product->publish_at }}" />
            <span id="publish_at" class="text-danger"></span>
        </div>


    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const selectElements = ['#product_status', '#discount_type'];

        selectElements.forEach(selector => {
            $(selector).select2({
                minimumResultsForSearch: Infinity
            });
        });

        // Get selected values from the product (for edit mode)
        var selectedCategoryId = "{{ $product->category_id ?? '' }}";
        var selectedSubcategoryIds = @json($product->subcategories->pluck('id') ?? []);
        var selectedSubsubcategoryIds = @json($product->subsubcategories->pluck('id') ?? []);
        var selectedBrandId = "{{ $product->brand_id ?? '' }}";

        // Initialize multiple select2
        $('#subcategory_id_item').select2({
            placeholder: "Select subcategories",
            allowClear: true
        });

        $('#subsubcategory_id_item').select2({
            placeholder: "Select subsubcategories",
            allowClear: true
        });

        // Function to update select options via AJAX
        function updateSelectOptions(id, selectElementSelector, url, selectedValues = [], callback) {
            if (id) {
                $.ajax({
                    url: url + id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        var $select = $(selectElementSelector);
                        $select.empty().append('<option></option>');
                        
                        $.each(data, function (key, value) {
                            $select.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        
                        // Set selected values if any
                        if (selectedValues && selectedValues.length > 0) {
                            $select.val(selectedValues).trigger('change');
                        }
                        
                        if (callback) callback();
                    }
                });
            } else {
                $(selectElementSelector).empty().trigger('change');
            }
        }

        // Function to load subsubcategories based on selected subcategories
        function loadSubsubcategories(selectedSubcategories) {
            if (selectedSubcategories && selectedSubcategories.length > 0) {
                // Make single AJAX request with all IDs
                $.ajax({
                    url: "/admin/get-subsubcategories-for-multiple",
                    type: "GET",
                    data: { ids: selectedSubcategories },
                    dataType: "json",
                    success: function (data) {
                        var $subsubcategorySelect = $('#subsubcategory_id_item');
                        $subsubcategorySelect.empty().append('<option></option>');
                        
                        if (data && data.length > 0) {
                            $.each(data, function (key, value) {
                                $subsubcategorySelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                        
                        // Set selected values for edit mode
                        if (selectedSubsubcategoryIds && selectedSubsubcategoryIds.length > 0) {
                            $subsubcategorySelect.val(selectedSubsubcategoryIds).trigger('change');
                        }
                        
                        $subsubcategorySelect.select2({
                            placeholder: "Select subsubcategories",
                            allowClear: true
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading subsubcategories:", error);
                    }
                });
            } else {
                $('#subsubcategory_id_item').empty().append('<option></option>');
                $('#subsubcategory_id_item').select2({
                    placeholder: "Select subsubcategories",
                    allowClear: true
                });
            }
        }

        // ------------------- Initial population for edit -------------------
        if (selectedCategoryId) {
            // Populate Subcategories
            updateSelectOptions(selectedCategoryId, '#subcategory_id_item', '/admin/get-subcategories/', selectedSubcategoryIds, function () {
                // After subcategories are loaded, load subsubcategories
                if (selectedSubcategoryIds && selectedSubcategoryIds.length > 0) {
                    loadSubsubcategories(selectedSubcategoryIds);
                }
            });

            // Populate Brands
            updateSelectOptions(selectedCategoryId, 'select[name="brand_id"]', '/admin/get-brand/', [], function () {
                $('select[name="brand_id"]').val(selectedBrandId).trigger('change');
            });
        }

        // ------------------- Event Listeners -------------------
        // Category change
        $('#category_id_item').on('change', function () {
            var categoryId = $(this).val();

            // Update Subcategories
            updateSelectOptions(categoryId, '#subcategory_id_item', '/admin/get-subcategories/', [], function () {
                $('#subcategory_id_item').val(null).trigger('change');
            });

            // Update Brands
            updateSelectOptions(categoryId, 'select[name="brand_id"]', '/admin/get-brand/', [], function () {
                $('select[name="brand_id"]').val(null).trigger('change');
            });

            // Reset Subsubcategory
            $('#subsubcategory_id_item').empty().append('<option></option>');
            $('#subsubcategory_id_item').select2({
                placeholder: "Select subsubcategories",
                allowClear: true
            });
        });

        // Subcategory change (multiple selection)
        $('#subcategory_id_item').on('change', function () {
            var selectedSubcategories = $(this).val();
            
            // Load subsubcategories based on selected subcategories
            if (selectedSubcategories && selectedSubcategories.length > 0) {
                loadSubsubcategories(selectedSubcategories);
            } else {
                // Reset subsubcategory if no subcategory selected
                $('#subsubcategory_id_item').empty().append('<option></option>');
                $('#subsubcategory_id_item').select2({
                    placeholder: "Select subsubcategories",
                    allowClear: true
                });
            }
        });

        // Initialize Tagify script
        var input = document.querySelector("#kt_tagify_for_product");
        if (input) {
            input.addEventListener('change', function(){
                this.setAttribute('name', 'tags');
            });

            new Tagify(input, {
                whitelist: @json($tags),
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: "tagify__inline__suggestions",
                    enabled: 0,
                    closeOnSelect: false
                }
            });
        }
    });
</script>
@endpush