<script>
    $(document).ready(function () {
        let productTable = window.LaravelDataTables['order-table'];
        setInterval(function () {
            if (productTable) {
                productTable.draw(false);
            }
        }, 20000);
    });

    function dateRemove(){
            $('#kt_daterangepicker_4').val('');
        }
    $(document).ready(function () {
        var table = window.LaravelDataTables['order-table'];
        // Initialize Date Range Picker
        $("#kt_daterangepicker_4").daterangepicker({
            autoApply: false,
            timePicker: true,
            timePicker24Hour: false,
            timePickerSeconds: false,
            locale: {
                format: "YYYY-MM-DD",
                applyLabel: 'Apply',
                cancelLabel: 'Clear',
            },
            ranges: {
                "Today": [moment().startOf('day'), moment().endOf('day')],
                "Yesterday": [moment().subtract(1, "days").startOf('day'), moment().subtract(1, "days").endOf('day')],
                "Last 7 Days": [moment().subtract(6, "days").startOf('day'), moment().endOf('day')],
                "Last 30 Days": [moment().subtract(29, "days").startOf('day'), moment().endOf('day')],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            }
        });

        $('#kt_daterangepicker_4').val('');
        $('.ki-cross').hide();
        $('#kt_daterangepicker_4').on('change keyup', function () {
            if ($(this).val()) {
                $('.ki-cross').show();
            } else {
                $('.ki-cross').hide();
            }
        });


        // Apply Filter
        $('#apply').on('click', function () {
            var selectedRange = $('#kt_daterangepicker_4').val();
            var viewedStatus = $('input[name="viewed-status"]:checked').val();
            var typeStatus = $('input[name="type-status"]:checked').val();

            if (selectedRange) {
                table.column(6)
                    .search(selectedRange)
                    .draw();
            }

            table.column(8)
                .search(viewedStatus || "")
                .draw();

            table.column(3)
            .search(typeStatus || "")
            .draw();
        });

        // Reset Filter
        $('button[type="reset"]').on('click', function () {
            $('#kt_daterangepicker_4').val('');
            $('.ki-cross').hide();
            $('input[name="viewed-status"]').prop('checked', false);
            $('input[name="type-status"]').prop('checked', false);
            window.LaravelDataTables['order-table']
                .search('')
                .columns().search('')
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
            dateRange: '',
            userType: '',
            viewedStatus: ''
        };

        function updateSelectBoxes() {
            // Update the viewed-status radio buttons
            if (filters.viewedStatus) {
                $(`input[name="viewed-status"][value="${filters.viewedStatus}"]`).prop('checked', true);
            } else {
                $('input[name="viewed-status"]').prop('checked', false);
            }

            // Update the user-type radio buttons
            if (filters.userType) {
                $(`input[name="type-status"][value="${filters.userType}"]`).prop('checked', true);
            } else {
                $('input[name="type-status"]').prop('checked', false);
            }

            // Reset other fields (if applicable)
            $('#kt_daterangepicker_4').val(filters.dateRange || '');
        }


        // Update filters and display tags
        function updateFiltersDisplay() {
            const $filterContainer = $('#active-filters');
            const $resetFilterButton = $('#resetFilter');

            let hasActiveFilters = false;

            $filterContainer.empty();

            // Generate tags for each active filter and set `hasActiveFilters` if applicable
            if (filters.viewedStatus && filters.viewedStatus.length) {
                $filterContainer.append(createTag('Viewed'));
                hasActiveFilters = true;
            }

            if (filters.userType && filters.userType.length) {
                $filterContainer.append(createTag('User Type'));
                hasActiveFilters = true;
            }

            if (filters.dateRange && filters.dateRange.length) {
                $filterContainer.append(createTag('Date'));
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
            filters.dateRange = $('#kt_daterangepicker_4').val() || '';
            filters.viewedStatus = $('input[name="viewed-status"]:checked').val() || '';
            filters.userType = $('input[name="type-status"]:checked').val() || '';

            sessionStorage.setItem('filters', JSON.stringify(filters));
            updateFiltersDisplay();
        }


        // Apply filters to the DataTable
        function applyFilters(filters) {
            const table = window.LaravelDataTables['order-table'];


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
        $('#kt_daterangepicker_4').on('change', onFilterChange);
        $('input[name="viewed-status"], input[name="type-status"]').on('change', onFilterChange);


        // Reset filters
        $('button[type="reset"]').on('click', function () {

            window.LaravelDataTables['order-table']
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
