<x-default-layout>

    @section('title')
        {{ $user->name }} Details
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('user-management.users.show', $user) }}
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
                    <livewire:user-details.userinfo :user_id="$user->id" />
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
            
        </div>
       

        <div class="flex-lg-row-fluid ms-lg-15">
            <!--begin:::Tabs-->
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
               
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                        href="#add_order">Order List</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="add_order" role="tabpanel">
                    <livewire:user-details.user-order :user_id="$user->id" />
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>

    @push('scripts')
       
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