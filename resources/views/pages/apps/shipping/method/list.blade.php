<x-default-layout>

    @section('title')  Shipping Method @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('shipping_method') }}
    @endsection

    <div class="card mb-4">
        <!--begin::Card header-->
            <div class="head-option">
                <h2>Base Shipping Method</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_base_shipping">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    Add New
                </button>
            </div>
            @livewire('shipping.method.add-base-shipping')
        <!--end::Card header-->
        <div class="card-body py-4">
            <div class="table-responsive">
                @livewire('shipping.method.list-shipping-method')
            </div>
        </div>
    </div>

    <div class="card mb-4 mt-10">
        <!--begin::Card header-->
            <div class="head-option">
                <h2>Regular Shipping Method</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_regular_shipping">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    Add New
                </button>
            </div>
            @livewire('shipping.method.add-regular-shipping')
        <!--end::Card header-->
        <div class="card-body py-4">
            <div class="table-responsive">
                @livewire('shipping.method.list-regular-shipping-method')
            </div>
        </div>
    </div>

    <!-- DataTables Buttons JS -->
    @push('scripts')
    <script>
        function initializeActions() {
            // Initialize KTMenu
            KTMenu.init();

            // Add click event listener to delete buttons
            document.querySelectorAll('[data-kt-base-action="delete_row"]').forEach(function (element) {
                element.addEventListener('click', function () {
                    Swal.fire({
                        text: 'Are you sure you want to remove?',
                        icon: 'warning',
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            cancelButton: 'btn btn-secondary',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emit('delete_base_shipping', this.getAttribute('data-kt-shipping-id'));
                        }
                    });
                });
            });

            document.querySelectorAll('[data-kt-regular-action="delete_row"]').forEach(function (element) {
                element.addEventListener('click', function () {
                    Swal.fire({
                        text: 'Are you sure you want to remove?',
                        icon: 'warning',
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            cancelButton: 'btn btn-secondary',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emit('delete_regular_shipping', this.getAttribute('data-kt-shipping-id'));
                        }
                    });
                });
            });

            // Add click event listener to update buttons
            document.querySelectorAll('[data-kt-base-action="update_row"]').forEach(function (element) {
                element.addEventListener('click', function () {
                    Livewire.emit('update_base_shipping', this.getAttribute('data-kt-shipping-id'));
                });
            });

            document.querySelectorAll('[data-kt-regular-action="update_row"]').forEach(function (element) {
                element.addEventListener('click', function () {
                    Livewire.emit('update_regular_shipping', this.getAttribute('data-kt-shipping-id'));
                });
            });
        }

        document.addEventListener('livewire:load', function () {
            // Initial call to initialize actions
            initializeActions();

            Livewire.on('success', function () {
                $('#kt_modal_add_base_shipping').modal('hide');
            });

            Livewire.on('success', function () {
                $('#kt_modal_regular_shipping').modal('hide');
            });

            // Re-initialize actions after Livewire updates
            Livewire.hook('message.processed', (message, component) => {
                initializeActions();
            });
        });

    </script>
    @endpush

</x-default-layout>