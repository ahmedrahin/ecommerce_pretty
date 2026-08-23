<div class="card card-flush py-4">
    <div class="card-header">
        <div class="card-title">
            <h2>Product Details</h2>
        </div>
    </div>
    <div class="card-body pt-0 pb-0">
       
        <label class="form-label">Category</label>
        <select name="category_id" id="category_id_item" data-control="select2" class="form-select form-select-solid mb-5" data-placeholder="Select a category">
            <option></option>
            @foreach($categories ?? [] as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <div id="category_id" class="text-danger"></div>
        

        <label class="form-label d-block">Subcategory</label>
        <select name="subcategory_id[]" id="subcategory_id_item" data-control="select2" class="form-select form-select-solid mb-3" data-placeholder="Select subcategories" multiple="multiple">
            <option></option>
        </select>
        <span id="subcategory_id" class="text-danger"></span>

        {{-- <label class="form-label">Subsubcategory (Multiple)</label>
        <select name="subsubcategory_id[]" id="subsubcategory_id_item" data-control="select2" class="form-select form-select-solid mb-5" data-placeholder="Select subsubcategories" multiple="multiple">
            <option></option>
        </select>
        <span id="subsubcategory_id" class="text-danger"></span> --}}

        <label class="form-label d-block">Brand</label>
        <select name="brand_id" data-control="select2" class="form-select form-select-solid mb-5" data-placeholder="Select a brand" data-allow-clear="true">
            <option></option>
        </select>
        <span id="brand_id" class="text-danger"></span>

        <label class="form-label d-block">Tags</label>
        <input id="kt_tagify_for_product" name="tags" class="form-control mb-2" value="" />
        <span id="tags" class="text-danger"></span>

        <div class="mb-5">
            <label class="form-label d-block">Filtering</label>
            <div class="form-check form-check-custom form-check-solid mb-3">
                <input class="form-check-input" type="checkbox" id="is_new" name="is_new" value="1">
                <label for="is_new" class="form-check-label">set as new product</label>
            </div>
            <div class="form-check form-check-custom form-check-solid mb-2">
                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                <label for="is_featured" class="form-check-label">set as featured product</label>
            </div>
             <div class="form-check form-check-custom form-check-solid mb-2">
                <input class="form-check-input" type="checkbox" id="preorder" name="preorder" value="1">
                <label for="preorder" class="form-check-label">set as up coming product</label>
            </div>
             <div class="form-check form-check-custom form-check-solid mb-2">
                <input class="form-check-input" type="checkbox" id="stock_out" name="stock_out" value="1">
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
        <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="kt_ecommerce_add_product_status_select" name="status">
            <option></option>
            <option value="1" selected>Published</option>
            <option value="2">Draft</option>
            <option value="3">Scheduled</option>
            <option value="0">Inactive</option>
        </select>

        <div class="text-muted fs-7">Set the product status.</div>

        <div class="d-none mt-7">
            <label for="kt_ecommerce_add_product_status_datepicker" class="form-label">Select publishing date and time</label>
            <input class="form-control" id="kt_ecommerce_add_product_status_datepicker" placeholder="Pick date & time" name="publish_at" value="{{ old('publish_at') }}" />
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

            // Initialize multiple select2 for subcategory and subsubcategory
            $('#subcategory_id_item').select2({
                placeholder: "Select subcategories",
                allowClear: true
            });

            $('#subsubcategory_id_item').select2({
                placeholder: "Select subsubcategories",
                allowClear: true
            });

            // Event listener for when the category selection changes
            $('#category_id_item').on('change', function() {
                var categoryId = $(this).val();
                updateSelectOptions(categoryId, '#subcategory_id_item', '/admin/get-subcategories/', true, function() {
                    $('#subcategory_id_item').trigger('change');
                });

                // Update brands
                updateSelectOptions(categoryId, 'select[name="brand_id"]', '/admin/get-brand/', false);
            });

            // Event listener for when the subcategory selection changes
            $('#subcategory_id_item').on('change', function() {
                var selectedSubcategories = $(this).val();
                if (selectedSubcategories && selectedSubcategories.length > 0) {
                    // Build query string for multiple IDs
                    var queryString = selectedSubcategories.map(id => 'ids[]=' + id).join('&');
                    loadMultipleSubsubcategories(queryString);
                } else {
                    $('#subsubcategory_id_item').empty().trigger('change');
                    $('#subsubcategory_id_item').select2({
                        placeholder: "Select subsubcategories",
                        allowClear: true
                    });
                }
            });

            function loadMultipleSubsubcategories(queryString) {
                $.ajax({
                    url: "/admin/get-subsubcategories-for-multiple?" + queryString,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        var $select = $('#subsubcategory_id_item');
                        $select.empty();

                        $.each(data, function(key, value) {
                            $select.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        $select.select2({
                            placeholder: "Select subsubcategories",
                            allowClear: true
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading subsubcategories:", error);
                    }
                });
            }

            function updateSelectOptions(id, selectElementId, url, isMultiple = false, callback) {
                if (id) {
                    $.ajax({
                        url: url + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var $select = $(selectElementId);
                            $select.empty();

                            $.each(data, function(key, value) {
                                $select.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                            // Initialize or re-init select2
                            if (isMultiple) {
                                $select.select2({
                                    placeholder: $select.data('placeholder') || "Select options",
                                    allowClear: true
                                });
                            } else {
                                $select.select2({
                                    placeholder: $select.data('placeholder'),
                                    allowClear: true
                                });
                            }

                            if (callback) callback();
                        }
                    });
                } else {
                    $(selectElementId).empty().trigger('change');
                    if (isMultiple) {
                        $(selectElementId).select2({
                            placeholder: "Select options",
                            allowClear: true
                        });
                    }
                }
            }

            var input = document.querySelector("#kt_tagify_for_product");

            // Initialize Tagify script on the above inputs
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
        });
    </script>
@endpush