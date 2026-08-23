<x-default-layout>

    @section('title')
        {{ $user->name }} Details
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('admin-management.admin-list.show', $user) }}
    @endsection

    <!--begin::Layout-->
    <div class="d-flex flex-column flex-lg-row">
        <!--begin::Sidebar-->
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            <!--begin::Card-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Summary-->
                    <!--begin::User Info-->
                    <livewire:admin-details.admininfo :user_id="$user->id" />
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
            
        </div>
       

        <div class="flex-lg-row-fluid ms-lg-15">
            <!--begin:::Tabs-->
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
               
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-kt-countup-tabs="true" data-bs-toggle="tab"
                        href="#kt_user_view_overview_security">Security</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                        href="#add_order">Added Order</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                        href="#add_product">Added Product </a>
                </li>
                
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_user_view_overview_security" role="tabpanel">
                    <livewire:admin-details.security-update :user_id="$user->id" />
                </div>

                <div class="tab-pane fade" id="add_order" role="tabpanel">
                    <livewire:admin-details.orderadded :user_id="$user->id" />
                </div>

                <div class="tab-pane fade" id="add_product" role="tabpanel">
                    <livewire:admin-details.productadd :user_id="$user->id" />
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>

    @push('scripts')
        <script>
             document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('.modal').modal('hide');
                });
            });
        </script>

        <script>
            document.querySelectorAll('[data-kt-action="delete_product"]').forEach(function (element) {
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
                        Livewire.emit('delete_product', this.getAttribute('data-product-id'));
                    }
                });
            });
        });
        </script>
    @endpush
</x-default-layout>