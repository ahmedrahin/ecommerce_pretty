<div class="tab-pane fade" id="filter" role="tab-panel">
    <div class="row g-4" id="filter-options-wrapper">
        @foreach ($filters as $filter)
            <div class="col-md-4">
                <div class="card card-flush py-4 filter-box">
                    <div class="card-body pt-0 pb-0">
                        <div class="mb-5">
                            <h3 class="mb-4">{{ $filter->option_name }}</h3>
                            @foreach ($filter->values as $value)
                                @php
                                    $inputId = 'filter_' . $filter->id . '_' . $value->id;
                                    // Check if this value is selected for this product
                                    $checked = $product->filterValues->contains(function ($fv) use ($value, $filter) {
                                        return $fv->id == $value->id && $fv->pivot->filter_option_id == $filter->id;
                                    });
                                @endphp

                                <div class="form-check form-check-custom form-check-solid mb-3">
                                    <input class="form-check-input" type="checkbox" id="{{ $inputId }}"
                                        name="filters[{{ $filter->id }}][]" value="{{ $value->id }}"
                                        {{ $checked ? 'checked' : '' }}>

                                    <label class="form-check-label" for="{{ $inputId }}">
                                        {{ $value->option_value }}
                                    </label>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<input type="hidden" id="product_id" value="{{ $product->id }}">


@push('scripts')
    <script>
        $(document).ready(function() {
            let categoryId = $('#category_id_item').val();
            let productId = $('#product_id').val();

            if (categoryId) {
                loadCategoryFilters(categoryId, productId);
            }
        });

        $(document).on('change', '#category_id_item', function() {
            let categoryId = $(this).val();
            let productId = $('#product_id').val();
            if (categoryId) {
                loadCategoryFilters(categoryId, productId);
            }
        });

        function loadCategoryFilters(categoryId, productId) {
            $.ajax({
                url: "{{ route('product-catalogue.category.filters.edit', '') }}/" + categoryId,
                type: "GET",
                data: {
                    product_id: productId
                },
                success: function(response) {
                    $('#filter-options-wrapper').html(response.html);
                }
            });
        }
    </script>
@endpush
