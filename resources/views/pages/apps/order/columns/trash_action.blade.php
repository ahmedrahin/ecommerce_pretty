<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click"
    data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>
<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
    data-kt-menu="true">
    
    <div class="menu-item px-3">
        <a href="{{ route('order-management.order.show', $order->id) }}" class="menu-link px-3">
            Details
        </a>
    </div>

    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3" data-kt-order-id="{{ $order->id }}" data-kt-action="restore">
            Restore
        </a>
    </div>
    
    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3" data-kt-order-id="{{ $order->id }}" data-kt-action="delete_permanent">
            Permanent Delete
        </a>
    </div>
</div>
<!--end::Menu-->