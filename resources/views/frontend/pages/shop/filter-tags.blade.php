<div class="tf-control-filter d-ld-none d-md-none">
    <button type="button" id="filterShop" class="tf-btn-filter">
        <span class="icon icon-filter"></span><span class="text">Filter</span>
    </button>
</div>

<div></div>
{{-- <div class="meta-filter-shop active" style="">
    <div id="product-count-grid" class="count-text"><span class="count">5</span> Products found</div>
    <div id="applied-filters"><span class="filter-tag remove-tag"><span class="icon icon-close"></span>Price: $0 -
            $458</span></div>
    <button id="remove-all" class="remove-all-filters" style="">
        <i class="icon icon-close"></i> Clear all
    </button>
</div> --}}


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
    
    // Update filter tags display
    function updateFilterTags(data) {
        // Check if the AJAX response contains the applied-filters HTML
        if ($(data).find('#applied-filters').length) {
            $('#applied-filters').html($(data).find('#applied-filters').html());
            $('#product-count-grid').html($(data).find('#product-count-grid').html());
            
            // Show/hide clear all button
            if ($(data).find('#remove-all').length) {
                $('#remove-all').show();
            } else {
                $('#remove-all').hide();
            }
        }
    }
    
    // Remove specific filter
    function removeFilter(type, value, filterId = null, valueId = null) {
        let params = new URLSearchParams(window.location.search);
        
        if (type === 'price') {
            params.delete('filter_price');
        } else if (type === 'brand') {
            let filters = params.getAll('filter[]');
            let newFilters = filters.filter(filter => filter !== `brand:${value}`);
            params.delete('filter[]');
            newFilters.forEach(filter => params.append('filter[]', filter));
        } else if (type === 'custom') {
            let filters = params.getAll('filter[]');
            let newFilters = filters.filter(filter => filter !== `${filterId}:${valueId}`);
            params.delete('filter[]');
            newFilters.forEach(filter => params.append('filter[]', filter));
        }
        
        let url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        loadProducts(url);
    }
    
    // Clear all filters
    function clearAllFilters() {
        let url = window.location.pathname;
        
        // Preserve only limit and sort if they exist
        let params = new URLSearchParams();
        
        if ($('#input-limit').length && $('#input-limit').val()) {
            params.set('limit', $('#input-limit').val());
        }
        
        if ($('#input-sort').length && $('#input-sort').val()) {
            let sortVal = $('#input-sort').val();
            if (sortVal.endsWith('_desc')) {
                params.set('sort', sortVal.replace('_desc', ''));
                params.set('order', 'DESC');
            } else {
                params.set('sort', sortVal);
                params.set('order', 'ASC');
            }
        }
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        // Also uncheck all filter checkboxes
        $('input[name="filter[]"]').prop('checked', false);
        
        // Reset price slider to min and max
        if (slider && slider.noUiSlider) {
            let min = parseInt($('#rang-slider').data('min'));
            let max = parseInt($('#rang-slider').data('max'));
            slider.noUiSlider.set([min, max]);
        }
        
        loadProducts(url);
    }
    
    // Load products with filter tag update
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
                
                // Update filter tags from the response
                updateFilterTags(data);
                
                // Update checkboxes based on current URL
                syncCheckboxes();
                
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
    
    // Sync checkboxes with URL parameters
    function syncCheckboxes() {
        let params = new URLSearchParams(window.location.search);
        let filterValues = params.getAll('filter[]');
        
        // Uncheck all first
        $('input[name="filter[]"]').prop('checked', false);
        
        // Check the ones in URL
        filterValues.forEach(filterValue => {
            $(`input[name="filter[]"][value="${filterValue}"]`).prop('checked', true);
        });
        
        // Update price slider
        if (params.has('filter_price')) {
            let priceRange = params.get('filter_price').split('-');
            if (priceRange.length === 2) {
                let from = parseInt(priceRange[0]);
                let to = parseInt(priceRange[1]);
                
                if (slider && slider.noUiSlider) {
                    slider.noUiSlider.set([from, to]);
                }
            }
        }
    }
    
    // Initialize slider (keep your existing slider code)
    let slider = document.getElementById('rang-slider');
    if (slider) {
        let min = parseInt(slider.dataset.min);
        let max = parseInt(slider.dataset.max);
        let from = parseInt(slider.dataset.from);
        let to = parseInt(slider.dataset.to);
        
        // ... your existing slider initialization code ...
    }
    
    // Events
    $(document).on('change', 'input[name="filter[]"]', function() {
        loadProducts();
    });
    
    $('#input-limit, #input-sort').on('change', function() {
        loadProducts();
    });
    
    // Remove filter tag
    $(document).on('click', '.remove-filter-tag', function(e) {
        e.stopPropagation();
        let tag = $(this).closest('.filter-tag');
        let type = tag.data('type');
        let value = tag.data('value');
        let filterId = tag.data('filter-id');
        let valueId = tag.data('value-id');
        
        removeFilter(type, value, filterId, valueId);
    });
    
    // Clear all filters
    $(document).on('click', '#remove-all', function() {
        clearAllFilters();
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
                updateFilterTags(data);
                syncCheckboxes();
                if (typeof Livewire !== "undefined") {
                    Livewire.rescan();
                }
            }
        });
    };
    
    // Initial sync on page load
    syncCheckboxes();
});
</script>
@endpush