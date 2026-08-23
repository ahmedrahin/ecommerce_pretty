<div class="tab-pane fade" id="filter" role="tab-panel">
    <div class="row g-4" id="filter-options-wrapper">
        @foreach($filters as $filter)
            <div class="col-md-4">
                <div class="card card-flush py-4 filter-box">
                    <div class="card-body pt-0 pb-0">
                        <div class="mb-5">
                            <h3 class="mb-4">{{ $filter->option_name }}</h3>
                            @foreach($filter->values as $value)
                                @php
                                    $inputId = 'filter_'.$filter->id.'_'.$value->id;
                                @endphp

                                <div class="form-check form-check-custom form-check-solid mb-3">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="{{ $inputId }}"
                                        name="filters[{{ $filter->id }}][]"
                                        value="{{ $value->id }}">

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


@push('scripts')
<script>
$(document).on('change', '#category_id_item', function () {
    let categoryId = $(this).val();
    if (categoryId) {
        $.ajax({
            url: "{{ route('product-catalogue.category.filters', '') }}/" + categoryId,
            type: "GET",
            success: function (response) {
                $('#filter-options-wrapper').html(response.html);
            }
        });
    }
});
</script>
@endpush
