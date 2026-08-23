<x-default-layout>

    @section('title') Product Variant @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('variant') }}
    @endsection

    <div class="card mb-4">
        <!--begin::Card header-->
            <div class="head-option">
                <h2>Attributes</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_attribute">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    Add Attribute
                </button>
            </div>
            @livewire('variant.add-variant')
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                @livewire('variant.list-variant')
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->

    </div>

    <!--begin::Card body-->
    <div class="card-body attr-value py-4">
           <div class="row">
                @livewire('variant.attr-value')
           </div>
    </div>
    <!--end::Card body-->

    <!-- DataTables Buttons JS -->
    @push('scripts')
        <!-- Blade Template e Scripts Part e: -->
        <script>
            function initializeActions() {
                // Initialize KTMenu
                KTMenu.init();

                // Add click event listener to delete buttons
                document.querySelectorAll('[data-kt-action="delete_row"]').forEach(function (element) {
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
                                Livewire.emit('delete_attr', this.getAttribute('data-kt-attr-id'));
                            }
                        });
                    });
                });

                // Add click event listener to update buttons
                document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
                    element.addEventListener('click', function () {
                        Livewire.emit('update_attr', this.getAttribute('data-kt-attr-id'));
                    });
                });
            }

            document.addEventListener('livewire:load', function () {
                // Initial call to initialize actions
                initializeActions();

                Livewire.on('success', function () {
                    $('#kt_modal_add_attribute').modal('hide');
                });

                // Re-initialize actions after Livewire updates
                Livewire.hook('message.processed', (message, component) => {
                    initializeActions();
                });

            });
            
        </script>

    @endpush

</x-default-layout>