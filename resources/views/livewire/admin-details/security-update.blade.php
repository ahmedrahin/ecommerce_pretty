<div>
    
    <!--begin::Card-->
    <div class="card pt-4 mb-6 mb-xl-9">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>Profile</h2>
            </div>
            <!--end::Card title-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0 pb-5">
            <!--begin::Table wrapper-->
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed gy-5"
                    id="kt_table_users_login_session">
                    <tbody class="fs-6 fw-semibold text-gray-600">
                        <tr>
                            <td>Email</td>
                            <td>{{$user->email}}</td>
                            <td class="text-end">
                                @can('update admin')
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_update_email">
                                        <i class="ki-duotone ki-pencil fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                @else 
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        onclick="errorAccess('You have no permission to change admin email')">
                                        <i class="ki-duotone ki-pencil fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td>******</td>
                            <td class="text-end">
                                @can('update admin')
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_update_password">
                                        <i class="ki-duotone ki-pencil fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                @else 
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        onclick="errorAccess('You have no permission to change admin password')">
                                        <i class="ki-duotone ki-pencil fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Role</td>
                            <td>{{$user->roles->first()?->name ?? 'No Role'}}</td>
                            <td class="text-end">
                                @can('update admin')
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_update_role">
                                        <i class="ki-duotone ki-pencil fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                @else 
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        onclick="errorAccess('You have no permission to change admin role')">
                                        <i class="ki-duotone ki-pencil fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Table wrapper-->
        </div>
        <!--end::Card body-->
    </div>
    
    <!--begin::Modal - Update -->
    @include('pages.apps/user-management/admin/modals/_update-email')
    @include('pages.apps/user-management/admin/modals/_update-password')
    @include('pages.apps/user-management/admin/modals/_update-role')
</div>
