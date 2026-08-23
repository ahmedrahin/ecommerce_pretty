
<div class="canvas-sidebar sidebar-filter canvas-filter left">
    <div class="canvas-wrapper">
        <div class="canvas-header d-xl-none">
            <span class="title h3 fw-medium">Filter</span>
            <span class="icon-close link icon-close-popup fs-24 close-filter"></span>
        </div>
        <div class="canvas-body">
            <div class="widget-facet" style="padding-bottom:30px;">
                <div class="facet-title" data-bs-target="#category" role="button" data-bs-toggle="collapse"
                    aria-expanded="true" aria-controls="category">
                    <span class="h4 fw-semibold">Category</span>
                    <span class="icon icon-caret-down fs-20"></span>
                </div>
                <div id="category" class="collapse show">
                    @php
                        $categories = App\Models\Category::with(['subcategories.subsubcategories'])->where('status', 1)->get();
                        $currentSlug = request()->segment(count(request()->segments()));
                    @endphp
                    <ul id="category" class="collapse-body filter-group-check group-category">

                        @foreach ($categories as $category)
                            @php
                                $isActiveCategory = $currentSlug === $category->slug;
                                $isSubActive = $category->subcategories->contains('slug', $currentSlug);
                                $isSubSubActive = $category->subcategories
                                ->flatMap->subsubcategories
                                ->contains('slug', $currentSlug);
                                $hasSub = $category->subcategories->count() > 0;
                            @endphp

                           <li class="list-item {{ $isActiveCategory ? 'active' : '' }}  {{ $isSubActive || $isSubSubActive ? 'subActive' : '' }} ">

                                <a href="{{ route('category.products', $category->slug) }}" class="link h6">
                                    {{ $category->name }}
                                    @if ($hasSub)
                                        <span class="arrow"><i class="bi bi-caret-right-fill"></i></span>
                                    @endif
                                </a>

                                @if ($hasSub)
                                    <ul class="sub-category">
                                        @foreach ($category->subcategories->where('status',1) as $sub)
                                            @php
                                                $isActiveSub = $currentSlug === $sub->slug;
                                                $isActiveSubSub = $sub->subsubcategories->contains('slug', $currentSlug);
                                                $subClass = '';
                                                if ($isActiveSubSub) {
                                                    $subClass = 'subsubActive';
                                                }
                                            @endphp

                                            <li class="{{ $isActiveSub ? 'active' : '' }} {{ $subClass }}">
                                                <a href="{{ route('category.products', [$category->slug, $sub->slug]) }}">
                                                    {{ $sub->name }}

                                                    @if ($sub->subsubcategories->count() > 0)
                                                        <span class="arrow"><i class="bi bi-caret-right-fill"></i></span>
                                                    @endif
                                                </a>

                                                @if ($sub->subsubcategories->count() > 0)
                                                    <ul class="sub-sub-category">
                                                        @foreach ($sub->subsubcategories->where('status',1) as $subsub)
                                                            @php
                                                                $isActiveSubSub = $currentSlug === $subsub->slug;
                                                            @endphp

                                                            <li class="{{ $isActiveSubSub ? 'active' : '' }}">
                                                                <a href="{{ route('category.products', [$category->slug, $sub->slug, $subsub->slug]) }}">
                                                                    {{ $subsub->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>

            <div class="widget-facet">
                <div class="facet-title" data-bs-target="#price" role="button" data-bs-toggle="collapse" aria-expanded="true"
                    aria-controls="price">
                    <span class="h4 fw-semibold">Price</span>
                    <span class="icon icon-caret-down fs-20"></span>
                </div>
                <div id="price" class="collapse show">
                    <div class="collapse-body widget-price filter-price">
                        <div id="rang-slider" class="price-val-range" data-from="{{ $from ?? 0 }}"
                            data-to="{{ $to ?? 5000 }}" data-min="0" data-max="{{ 5000 }}">
                        </div>
                        
                        <div class="box-value-price">
                            <span class="h6 text-main">Price:</span>
                            <div class="price-box">
                                <div class="price-val" id="price-min-value" data-currency="$"></div>
                                <span>-</span>
                                <div class="price-val" id="price-max-value" data-currency="$"></div>
                            </div>
                        </div>
                        <div style="display: none;">
                            <input type="hidden" id="range-from" name="from" value="{{ $from ?? 0 }}">
                            <input type="hidden" id="range-to" name="to" value="{{ $to ?? 5000 }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget-facet">
                <div class="facet-title" data-bs-target="#brand" role="button" data-bs-toggle="collapse" aria-expanded="true"
                    aria-controls="brand">
                    <span class="h4 fw-semibold">Brand</span>
                    <span class="icon icon-caret-down fs-20"></span>
                </div>
                
                <div id="brand" class="collapse show">
                    <ul class="collapse-body filter-group-check current-scrollbar">
                        @foreach ($brands as $brand)
                            <li class="list-item">
                                 @php
                                    $isChecked = request()->filled('filter') && in_array("brand:$brand->id", request()->filter ?? []);
                                @endphp
                                <input type="checkbox" name="filter[]" class="tf-check" id="{{ $brand->name }}" value="brand:{{ $brand->id }}" {{ $isChecked ? 'checked' : '' }}>
                                <label for="{{ $brand->name }}" class="label">{{ $brand->name }}</label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @if(!empty($filters))
                 @foreach ($filters as $filter)
                  <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#{{ $filter->option_name }}" role="button" data-bs-toggle="collapse" aria-expanded="true"
                        aria-controls="{{ $filter->option_name }}">
                        <span class="h4 fw-semibold">{{ $filter->option_name }}</span>
                        <span class="icon icon-caret-down fs-20"></span>
                    </div>

                    <div id="{{ $filter->option_name }}" class="collapse show">
                        <ul class="collapse-body filter-group-check current-scrollbar">
                            @foreach ($filter->values as $value)
                                <li class="list-item">
                                    @php
                                        $filterKey = $filter->id . ':' . $value->id;
                                        $isChecked = request()->filled('filter') && in_array($filterKey, request()->filter ?? []);
                                    @endphp
                                    <input type="checkbox" name="filter[]" class="tf-check" id="{{ $value->option_value }}" value="{{ $filterKey }}" {{ $isChecked ? 'checked' : '' }}>
                                    <label for="{{ $value->option_value }}" class="label">{{ $value->option_value }}</label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                  </div>
                @endforeach
            @endif
            
        </div>
        {{-- <div class="canvas-bottom d-xl-none">
            <button id="reset-filter" class="tf-btn btn-reset">Reset Filters</button>
        </div> --}}
    </div>
</div>

@push('scripts')

    <script>
        jQuery(function($) {

            // Build query params
            function getFilters() {
                let params = new URLSearchParams();

                // Limit
                if ($('#input-limit').length) {
                    params.set('limit', $('#input-limit').val());
                }

                // Sort
                if ($('#input-sort').length) {
                    let sortVal = $('#input-sort').val();
                    if (sortVal === '') {
                        params.delete('sort');
                        params.delete('order');
                    } else if (sortVal.endsWith('_desc')) {
                        params.set('sort', sortVal.replace('_desc', ''));
                        params.set('order', 'DESC');
                    } else {
                        params.set('sort', sortVal);
                        params.set('order', 'ASC');
                    }
                }

                // Collect checked filters
                $('input[name="filter[]"]:checked').each(function() {
                    params.append('filter[]', $(this).val());
                });

                return params.toString();
            }

            // Load products
            function loadProducts(customUrl = null) {
                let url = customUrl || (window.location.pathname + '?' + getFilters());

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    beforeSend: function() {
                        $('#productLayout').addClass('loading');
                        $('#ajax-loader').show();
                    },
                    success: function(data) {
                        $('#productLayout').html(data);
                        if (typeof Livewire !== "undefined") {
                            Livewire.rescan();
                        }
                        history.pushState(null, '', url);
                    },
                    complete: function() {
                        $('#productLayout').removeClass('loading');
                        $('#ajax-loader').hide();
                    },
                    error: function(xhr) {
                        console.error("AJAX ERROR:", xhr.responseText);
                        alert('Failed to load products');
                    }
                });
            }

            // Events
            $(document).on('change', 'input[name="filter[]"]', function() {
                loadProducts();
            });

            $('#input-limit, #input-sort').on('change', function() {
                loadProducts();
            });

            $(document).on('click', '#productLayout .wg-pagination a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                loadProducts(url);
            });

            window.onpopstate = function() {
                $.ajax({
                    url: location.href,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(data) {
                        $('#productLayout').html(data);
                        if (typeof Livewire !== "undefined") {
                            Livewire.rescan();
                        }
                    }
                });
            };

        });
    </script>

    {{-- range filter --}}
    <script>
        jQuery(function($) {
            let slider = document.getElementById('rang-slider');

            let min = parseInt(slider.dataset.min);
            let max = parseInt(slider.dataset.max);
            let from = parseInt(slider.dataset.from);
            let to = parseInt(slider.dataset.to);

            let inputFrom = document.getElementById('range-from');
            let inputTo = document.getElementById('range-to');

            let skipValues = [
                document.getElementById("price-min-value"),
                document.getElementById("price-max-value")
            ];

            // Create slider only if not initialized
            if (!slider.noUiSlider) {
                noUiSlider.create(slider, {
                    start: [from, to],
                    connect: true,
                    step: 1,
                    range: {
                        min: min,
                        max: max
                    }
                });
            }

            slider.noUiSlider.on('update', function(values) {
                let val0 = Math.round(values[0]);
                let val1 = Math.round(values[1]);

                if (skipValues[0]) skipValues[0].innerText = val0;
                if (skipValues[1]) skipValues[1].innerText = val1;

                if (inputFrom) inputFrom.value = val0;
                if (inputTo) inputTo.value = val1;
            });


            // User types in FROM input
            inputFrom.addEventListener('change', function() {
                let valFrom = parseInt(this.value) || min;
                let valTo = parseInt(inputTo.value) || max;
                slider.noUiSlider.set([valFrom, valTo]);
                triggerAjax(valFrom, valTo);
            });

            // User types in TO input
            inputTo.addEventListener('change', function() {
                let valFrom = parseInt(inputFrom.value) || min;
                let valTo = parseInt(this.value) || max;
                slider.noUiSlider.set([valFrom, valTo]);
                triggerAjax(valFrom, valTo);
            });

            // When user stops sliding, trigger AJAX
            slider.noUiSlider.on('change', function(values) {
                triggerAjax(Math.round(values[0]), Math.round(values[1]));
            });

            // AJAX request (same as your sorting code)
            function triggerAjax(fromVal, toVal) {
                let params = new URLSearchParams(window.location.search);
                params.set('filter_price', `${fromVal}-${toVal}`);

                // Preserve limit & sort
                let limitVal = $('#input-limit').val();
                if (limitVal) params.set('limit', limitVal);

                let sortVal = $('#input-sort').val();
                if (sortVal) {
                    if (sortVal.endsWith('_desc')) {
                        params.set('sort', sortVal.replace('_desc', ''));
                        params.set('order', 'DESC');
                    } else {
                        params.set('sort', sortVal);
                        params.set('order', 'ASC');
                    }
                }

                let url = window.location.pathname + '?' + params.toString();

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    beforeSend: function() {
                        $('#productLayout').addClass('loading');
                        $('#ajax-loader').show();
                    },
                    success: function(data) {
                        $('#productLayout').html(data);
                        Livewire.rescan();
                        history.pushState(null, '', url);
                    },
                    complete: function() {
                        $('#productLayout').removeClass('loading');
                        $('#ajax-loader').hide();
                    },
                    error: function() {
                        alert('Failed to load products');
                    }
                });
            }
        });
    </script>

    <script>
        jQuery(function($) {
            $(document).on('click', '.filter-group .label', function() {
                $(this).closest('.filter-group').toggleClass('show');
            });
        });
    </script>

@endpush
