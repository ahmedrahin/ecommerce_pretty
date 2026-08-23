<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 g-xl-6">
    @foreach($roles as $role)
    <!--begin::Col-->
    <div class="col-md-4">
        <!--begin::Card-->
        <div class="card card-flush h-md-100">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2>{{ ucwords($role->name) }}</h2>
                    @if( $role->name !== 'Super Admin' )
                        @can('delete role')
                            <button class="btn btn-icon btn-active-light-primary w-30px h-30px"  wire:click="confirmDelete('{{ $role->name }}')" style="margin-right: -25px;" >
                                {!! getIcon('trash','fs-3') !!}
                            </button>
                        @endcan
                    @else
                        <img src="{{asset('uploads/medal.png')}}" alt="" style="width: 24px;margin-right: -20px;">
                    @endif
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-1">
                <!--begin::Users-->
                <div class="fw-bold text-gray-600 mb-5">Total users with this role: {{ $role->users->count() }}</div>
                <!--end::Users-->
                <!--begin::Permissions-->
                <div class="d-flex flex-column text-gray-600">
                   
                    @if($role->permissions->count() === Spatie\Permission\Models\Permission::count())
                        <div class="d-flex align-items-center py-2">
                            <span class="bullet bg-primary me-3"></span>
                            <em>All permissions given...</em>
                        </div>
                    @else 
                        @foreach($role->permissions->shuffle()->take(3) ?? [] as $permission)
                            <div class="d-flex align-items-center py-2">
                                <span class="bullet bg-primary me-3"></span>{{ ucfirst($permission->name) }}
                            </div>
                        @endforeach

                        @if($role->permissions->count() > 3)
                            <div class='d-flex align-items-center py-2'>
                                <span class='bullet bg-primary me-3'></span>
                                <em>and {{ $role->permissions->count() - 3 }} more...</em>
                            </div>
                        @endif

                        @if($role->permissions->count() === 0)
                            <div class="d-flex align-items-center py-2">
                                <span class='bullet bg-primary me-3'></span>
                                <em>No permissions given...</em>
                            </div>
                        @endif
                    @endif

                </div>
                <!--end::Permissions-->
            </div>
            <!--end::Card body-->
            <!--begin::Card footer-->
            @canany(['details role', 'update role'])
                <div class="card-footer flex-wrap pt-0">
                    @can('details role')
                        <a href="{{ route('admin-management.roles.show', $role) }}"
                            class="btn btn-light btn-active-primary my-1 me-2">View Role</a>
                    @endif
                    @can('update role')
                        @if( $role->name !== 'Super Admin' )
                            <button type="button" class="btn btn-light btn-active-light-primary my-1"
                                data-role-id="{{ $role->name }}" data-bs-toggle="modal" data-bs-target="#kt_modal_update_role" data-kt-action="update_row" data-role-name="{{ $role->name }}">Edit Role</button>
                        @endif    
                    @endif
                </div>
            @endif
            <!--end::Card footer-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Col-->
    @endforeach

    <!--begin::Add new card-->
    <div class="ol-md-4">
        <!--begin::Card-->
        <div class="card h-md-100">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-center">
                <!--begin::Button-->
                @can('add role')
                    <button type="button" class="btn btn-clear d-flex flex-column flex-center" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_update_role">
                        <!--begin::Illustration-->
                        <img src="{{ image('illustrations/sketchy-1/4.png') }}" alt="" class="mw-100 mh-150px mb-7" />
                        <!--end::Illustration-->
                        <!--begin::Label-->
                        <div class="fw-bold fs-3 text-gray-600 text-hover-primary">Add New Role</div>
                        <!--end::Label-->
                    </button>
                <!--begin::Button-->
                @else
                    <button type="button" class="btn btn-clear d-flex flex-column flex-center" onclick="errorAccess('You do not have permission to create role.')">
                        <!--begin::Illustration-->
                        <img src="{{ image('illustrations/sketchy-1/4.png') }}" alt="" class="mw-100 mh-150px mb-7" />
                        <!--end::Illustration-->
                        <!--begin::Label-->
                        <div class="fw-bold fs-3 text-gray-600 text-hover-primary">Add New Role</div>
                        <!--end::Label-->
                    </button>
                @endif
            </div>
            <!--begin::Card body-->
        </div>
        <!--begin::Card-->
    </div>
    <!--begin::Add new card-->
</div>

@push('scripts')
<script>

    document.addEventListener('livewire:load', function () {
        attachListeners();

        // Listen for "success" event to re-render and reattach listeners
        Livewire.on('success', function () {
            $('#kt_modal_update_role').modal('hide');
            Livewire.emit('updateRoleList'); 
        });

        Livewire.on('triggerDeleteConfirmation', (roleName) => {
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
                    Livewire.emit('delete', roleName);
                }
            });
        });
    });

    document.addEventListener('livewire:update', function () {
        attachListeners();
    });

    function attachListeners() {
        // Attach update role listeners
        document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
            element.addEventListener('click', function () {
                Livewire.emit('update', this.getAttribute('data-role-name'));
            });
        });
    }

</script>
@endpush
