<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>
<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="{{route('admin-management.admin-list.show', $user->id)}}" class="menu-link px-3">
            View
        </a>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-3">
        @can('update admin')
            <a href="#" class="menu-link px-3" data-kt-user-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user" data-kt-action="update_row">
                Edit
            </a>
        @else
            <a href="javascript:;" class="menu-link px-3" onclick="errorAccess('You do not have permission to update admin.')">
                Edit
            </a>
        @endif
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-3">
        @can('delete admin')
            <a href="#" class="menu-link px-3" data-kt-user-id="{{ $user->id }}" data-kt-action="delete_row">
                Delete
            </a>
        @else
            <a href="javascript:;" class="menu-link px-3" onclick="errorAccess('You do not have permission to delete admin.')">
                Delete
            </a>
        @endif
    </div>
    <!--end::Menu item-->
</div>
<!--end::Menu-->
