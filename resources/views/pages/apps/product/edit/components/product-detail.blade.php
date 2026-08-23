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

        <label class="form-label">Subcategory</label>
        <select name="subcategory_id" id="subcategory_id_item" data-control="select2"
            class="form-select form-select-solid mb-5" data-placeholder="Select a subcategory" data-allow-clear="true">
            <option></option>
        </select>
        <span id="subcategory_id" class="text-danger"></span>

        <label class="form-label">Subsubcategory</label>
        <select name="subsubcategory_id" id="subsubcategory_id_item" data-control="select2"
            class="form-select form-select-solid mb-5" data-placeholder="Select a subsubcategory"
            data-allow-clear="true">
            <option></option>
        </select>
        <span id="subsubcategory_id" class="text-danger"></span>

        <label class="form-label">Brand</label>
        <select name="brand_id" data-control="select2" class="form-select form-select-solid mb-5"
            data-placeholder="Select a brand" data-allow-clear="true">
            <option></option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>{{ $brand->name }}</option>
            @endforeach
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

<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title">
            <h2>Badge</h2>
        </div>
    </div>
    <div class="card-body pt-0 pb-7">
        <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="product_badge" name="badge">
            <option value=""></option>
            <option value="trending" {{ $product->badge == 'trending' ? 'selected' : '' }}>Trending</option>
            <option value="new" {{ $product->badge == 'new' ? 'selected' : '' }}>New Arrival</option>
            <option value="flash" {{ $product->badge == 'falsh' ? 'selected' : '' }}>Flash Sale</option>
            <option value="hot" {{ $product->badge == 'hot' ? 'selected' : '' }}>Hot</option>
        </select>

        <div class="text-muted fs-7">Set the product badge.</div>
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

    var selectedCategoryId = "{{ $product->category_id ?? '' }}";
    var selectedSubcategoryId = "{{ $product->subcategory_id ?? '' }}";
    var selectedSubsubcategoryId = "{{ $product->subsubcategory_id ?? '' }}";
    var selectedBrandId = "{{ $product->brand_id ?? '' }}";

    // Function to update select options via AJAX
    function updateSelectOptions(id, selectElementSelector, url, callback) {
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
                    if (callback) callback();
                }
            });
        } else {
            $(selectElementSelector).empty();
        }
    }

    // ------------------- Initial population for edit -------------------
    if (selectedCategoryId) {
        // Populate Subcategories
        updateSelectOptions(selectedCategoryId, '#subcategory_id_item', '/admin/get-subcategories/', function () {
            $('#subcategory_id_item').val(selectedSubcategoryId).trigger('change');
        });
    }

    if (selectedSubcategoryId) {
        // Populate Subsubcategories
        updateSelectOptions(selectedSubcategoryId, '#subsubcategory_id_item', '/admin/get-subsubcategories/', function () {
            $('#subsubcategory_id_item').val(selectedSubsubcategoryId).trigger('change');
        });
    }

    // ------------------- Event Listeners -------------------
    // Category change
    $('#category_id_item').on('change', function () {
        var categoryId = $(this).val();

        // Update Subcategories
        updateSelectOptions(categoryId, '#subcategory_id_item', '/admin/get-subcategories/', function () {
            $('#subcategory_id_item').val(null).trigger('change'); // reset
        });

        // Update Brands
        updateSelectOptions(categoryId, 'select[name="brand_id"]', '/admin/get-brand/', function () {
            $('select[name="brand_id"]').val(null).trigger('change'); // reset
        });

        // Reset Subsubcategory
        $('#subsubcategory_id_item').empty();
    });

    // Subcategory change
    $('#subcategory_id_item').on('change', function () {
        var subcategoryId = $(this).val();
        updateSelectOptions(subcategoryId, '#subsubcategory_id_item', '/admin/get-subsubcategories/', function () {
            if (selectedSubsubcategoryId) {
                // Keep the saved subsubcategory on edit
                $('#subsubcategory_id_item').val(selectedSubsubcategoryId).trigger('change');
                selectedSubsubcategoryId = null; // clear after first use to avoid overwriting on change
            } else {
                // Otherwise reset
                $('#subsubcategory_id_item').val(null).trigger('change');
            }
        });
    });



        // Initialize Tagify script on the above inputs
        var input = document.querySelector("#kt_tagify_for_product");

        input.addEventListener('change', function(){
            this.setAttribute('name', 'tags');
        })

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