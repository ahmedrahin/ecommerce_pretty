<script>
    $(document).ready(function () {
        let productTable = window.LaravelDataTables['product-table'];
        setInterval(function () {
            if (productTable) {
                productTable.draw(false); 
            }
        }, 60000);

        $('#created_at_filter').flatpickr({
            altInput: true,
            altFormat: "d F, Y",
            dateFormat: "Y-m-d"
        });

        $('#rating-select').select2({
            
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function(data) {
                if (data.id == '0') {
                    return data.text; 
                }
                
                var rating = parseInt(data.id);
                var stars = '';
                for (var i = 0; i < 5; i++) {
                    if (i < rating) {
                        stars += '<i class="ki-duotone ki-star fs-6 " style="color:#ffad0f;"></i>'; 
                    } else {
                        stars += '<i class="ki-duotone ki-star fs-6" style="opacity: 0.3;"></i>';
                    }
                }

                return $(stars); 
            },
            templateSelection: function(data) {

                if (data.id == '0') {
                    return data.text; 
                }
                
                var rating = parseInt(data.id);
                var stars = '';
                for (var i = 0; i < 5; i++) {
                    if (i < rating) {
                        stars += '<i class="ki-duotone ki-star fs-6" style="color:#ffad0f;"></i>'; 
                    } else {
                        stars += '<i class="ki-duotone ki-star fs-6" style="opacity: 0.3;"></i>'; 
                    }
                }

                return $(stars);
            }
        });

        $('#selling-select').select2({
            minimumResultsForSearch: -1 
        });
        $('#offer-select').select2({
            minimumResultsForSearch: -1 
        });

        $('#apply').on('click', function () {
            // Gather selected filters
            const selectedExpire = $("#expire").val(); 
            window.LaravelDataTables['product-table']
                .column('expire_date:name') 
                .search(selectedExpire) 
                .draw();

            // crated_at
            const selectedDate = $("#created_at_filter").val(); 
            window.LaravelDataTables['product-table']
                .column('created_at:name') 
                .search(selectedDate) 
                .draw();

            // offer filter
            var selectedSelling = $('#offer-select').val(); 
                window.LaravelDataTables['product-table']
                    .column('base_price:name')
                    .search(selectedSelling)
                    .draw();

            // salling filter
            var selectedSelling = $('#selling-select').val(); 
                window.LaravelDataTables['product-table']
                    .column('selling:name')
                    .search(selectedSelling)
                    .draw();

            // Rating filter
            var selectedRatings = $('#rating-select').val(); 
            window.LaravelDataTables['product-table']
                .column('rating:name') 
                .search(selectedRatings ? selectedRatings.join('|') : '', true, false) 
                .draw();

            // Category filter
            var selectedCategories = $('#category-select').val(); 
            window.LaravelDataTables['product-table']
                .column('category_id:name') 
                .search(selectedCategories ? selectedCategories.join('|') : '', true, false)
                .draw();
        });

      
    });
</script>

<script>
    
    $(document).ready(function () {

        window.triggerReset = function () {
            $('button[type="reset"]').trigger('click');
        };


        const filters = {
            categories: [],
            ratings: [],
            selling: '',
            offer: '',
            createdAt: '',
            expire: ''
        };

        function updateSelectBoxes() {
            $('#category-select').val(filters.categories).trigger('change'); 
            $('#rating-select').val(filters.ratings).trigger('change'); 
            $('#selling-select').val(filters.selling).trigger('change');
            $('#offer-select').val(filters.offer).trigger('change'); 
            $('#created_at_filter').val(filters.createdAt); 
            $('#expire').val(filters.expire); 
        }

        // Update filters and display tags
        function updateFiltersDisplay() {
            const $filterContainer = $('#active-filters');
            const $resetFilterButton = $('#resetFilter');

            let hasActiveFilters = false;

            $filterContainer.empty(); // Clear existing tags

            // Generate tags for each active filter and set `hasActiveFilters` if applicable
            if (filters.categories && filters.categories.length) {
                $filterContainer.append(createTag('Category'));
                hasActiveFilters = true;
            }

            if (filters.ratings && filters.ratings.length) {
                $filterContainer.append(createTag('Rating'));
                hasActiveFilters = true;
            }

            if (filters.selling) {
                $filterContainer.append(createTag('Selling'));
                hasActiveFilters = true;
            }

            if (filters.offer) {
                $filterContainer.append(createTag('Offer'));
                hasActiveFilters = true;
            }

            if (filters.createdAt) {
                $filterContainer.append(createTag('Created At'));
                hasActiveFilters = true;
            }

            if (filters.expire) {
                $filterContainer.append(createTag('Expire'));
                hasActiveFilters = true;
            }

            // Show or hide the reset filter button based on active filters
            if (hasActiveFilters) {
                $resetFilterButton.show();
            } else {
                $resetFilterButton.hide();
            }
        }


        // Create a tag element
        function createTag(label,) {
            return $(`
                 <span class="filter-tag badge badge-light-primary mx-1">
                    ${label}
                </span>
                
            `);
        }

        // Update and save filters on change
        function onFilterChange() {
            filters.categories = $('#category-select').val() || [];
            filters.ratings = $('#rating-select').val() || [];
            filters.selling = $('#selling-select').val() || '';
            filters.offer = $('#offer-select').val() || '';
            filters.createdAt = $('#created_at_filter').val() || '';
            filters.expire = $('#expire').val() || '';

            sessionStorage.setItem('filters', JSON.stringify(filters));
            updateFiltersDisplay();
        }

        // Apply filters to the DataTable
        function applyFilters(filters) {
            const table = window.LaravelDataTables['product-table'];

            table.column('category_id:name').search(filters.categories.join('|'), true, false).draw();
            table.column('rating:name').search(filters.ratings.join('|'), true, false).draw();
            table.column('selling:name').search(filters.selling).draw();
            table.column('base_price:name').search(filters.offer).draw();
            table.column('created_at:name').search(filters.createdAt).draw();
            table.column('expire_date:name').search(filters.expire).draw();
        }

        // Initialize filters from session storage
        const storedFilters = JSON.parse(sessionStorage.getItem('filters'));
        if (storedFilters) {
            Object.assign(filters, storedFilters);
            updateFiltersDisplay();
            updateSelectBoxes(); // Sync select boxes
            applyFilters(filters);
        }

        // Listen to filter changes
        $('#category-select, #rating-select, #selling-select, #offer-select, #created_at_filter, #expire').on('change', onFilterChange);

        // Reset filters
        $('button[type="reset"]').on('click', function () {
            $('#category-select').val(null).trigger('change'); 
            $('#rating-select').val(null).trigger('change'); 
            $('#selling-select').val(null).trigger('change'); 
            $('#offer-select').val(null).trigger('change'); 
            $('#created_at_filter').val('');
            // $('.form-check-input').prop('checked', false); 
            const flatpickrInstance = $('#created_at_filter').flatpickr();
            flatpickrInstance.clear();

            window.LaravelDataTables['product-table']
                .search('')
                .columns().search('')
                .draw();

            Object.keys(filters).forEach(key => {
                if (Array.isArray(filters[key])) {
                    filters[key] = [];
                } else {
                    filters[key] = '';
                }
            });

            sessionStorage.removeItem('filters');
            updateFiltersDisplay();
            updateSelectBoxes();
            applyFilters(filters);
        });
    });


</script>