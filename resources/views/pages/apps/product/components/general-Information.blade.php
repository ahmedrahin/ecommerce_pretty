<div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="card card-flush py-4">
            <div class="card-header">
                <div class="card-title">
                    <h2>General Information</h2>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="mb-0 fv-row">
                    <label class="required form-label">Product Name</label>
                    <input type="text" name="name" 
                    class="form-control"
                     placeholder="Product name" value="" />
                    <span class="text-danger error-msg " id="name"></span>
                </div>
                
                <div class="mb-6">
                    <label class="form-label">Short Description</label>
                    <div id="product_short_description" class="min-h-200px mb-2 ql-container ql-snow" style="width: 100%"></div>
                    <input name="short_description" hidden>
                </div>
                <div>
                    <label class="form-label">Product Description</label>
                    <div id="product_long_description" style="width: 100%" class="min-h-200px mb-2 ql-container ql-snow"></div>
                    <input name="long_description" hidden>
                </div>
            </div>
        </div>
        <div class="card card-flush py-4">
            <div class="card-header">
                <div class="card-title">
                    <h2>Product Pricing</h2>
                </div>
            </div>
            <div class="card-body product-pricing pt-0 pb-5">
                <div class="fv-row">
                    <label class="required form-label">Base Price</label>
                    <input type="text" name="base_price" class="form-control mb-2" placeholder="Product price" value="" />
                    <span class="text-danger error-msg" id="base_price"></span>
                </div>
                <div class="fv-row mb-5">
                    <label class="fs-6 fw-semibold mb-2">
                        Discount Type
                    </label>
                    <select 
                    class="form-select form-select-solid" 
                    id="discount_type" 
                    name="discount_option"
                    data-control="select2"
                    data-placeholder="Select a discount">
                        <option value=""></option>
                        <option selected value="1">No Discount</option>
                        <option value="2">Parcentage %</option>
                        <option value="3">Flat Discount</option>
                    </select>
                </div>
                <div class="fv-row" id="product_discount_no">
                    <label class="required form-label">
                        Discount Amount
                    </label>
                    <input type="number" name="discount_percentage_or_flat_amount" class="form-control" value="" placeholder="Discount Amount" />
                    <span id="discount_percentage_or_flat_amount" class="text-danger"></span>
                </div>

                {{-- <div class="fv-row">
                    <label class="required form-label">Wholesale Price</label>
                    <input type="text" name="wholesale_price" class="form-control" placeholder="Product wholesale price" value="" />
                    <span class="text-danger error-msg" id="wholesale_price"></span>
                </div> --}}
            </div>
        </div>
        
        {{-- gellary images --}}
        <livewire:product.gellary-images/>

    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Options for initializing Quill editors
        const quillOptions = {
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            placeholder: 'Type your text here...',
            theme: 'snow'
        };

        // Initialize Quill editors for short and long descriptions
        const quillShortDesc = new Quill('#product_short_description', quillOptions);
        const quillLongDesc = new Quill('#product_long_description', quillOptions);
        const quillLongFeatureDesc = new Quill('#key_features', quillOptions);

        // Handle discount type change
        $('#discount_type').change(function() {
            const discountValue = $(this).val();
            const discountInput = $('input[name="discount_percentage_or_flat_amount"]');

            // Reset the discount input
            discountInput.val('');

            // Show or hide the discount input based on selected option
            if (discountValue == "1") {
                $('#product_discount_no').hide();
            } else {
                $('#product_discount_no').show();
                if (discountValue == "2") {
                    discountInput.attr('placeholder', 'Percentage Amount');
                } else if (discountValue == "3") {
                    discountInput.attr('placeholder', 'Flat Amount');
                }
            }
        }).change();
    });
</script>
@endpush