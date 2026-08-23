<div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
    <div>
        <select name="statusFilter" id="statusFilter" class="form-select form-select-solid w-200px" data-control="select2" data-allow-clear="true" data-placeholder="Filter by status">
            <option></option>
            <option value="1">Published</option>
            <option value="0">Inactive</option>
            <option value="2">Draft</option>
            <option value="3">Scheduled</option>
        </select>        
    </div>

    <button type="button" class="btn btn-light-primary mx-4" data-kt-menu-trigger="click"
        data-kt-menu-placement="bottom-end">
        <i class="ki-duotone ki-exit-down fs-2"><span class="path1"></span><span class="path2"></span></i>
        Export Report
    </button>
    <!--begin::Add product-->
    <a href="{{ route('product-management.create') }}" class="btn btn-primary">
        {!! getIcon('plus', 'fs-2', '', 'i') !!}
        Add Product
    </a>
    <!--begin::Menu-->
    <div id="kt_datatable_example_export_menu"
        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
        data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-export="copy">
                Copy to clipboard
            </a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-export="excel">
                Export as Excel
            </a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-export="csv">
                Export as CSV
            </a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-export="pdf">
                Export as PDF
            </a>
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</div>
{{-- 
<div class="mb-10">
    <label class="form-label fw-semibold">Member Type:</label>
    <div class="d-flex">
        <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
            <input class="form-check-input" type="checkbox" value="1" />
            <span class="form-check-label">Author</span>
        </label>
        <label class="form-check form-check-sm form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" value="2" checked="checked" />
            <span class="form-check-label">Customer</span>
        </label>
    </div>
</div>
<div class="mb-10">
    <label class="form-label fw-semibold">Notifications:</label>
    <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
        <input class="form-check-input" type="checkbox" value="" name="notifications" checked="checked" />
        <label class="form-check-label">Enabled</label>
    </div>
</div> --}}