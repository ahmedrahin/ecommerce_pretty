<div>
    <table class="table table-row-bordered gy-5">
        <thead>
            <tr class="fw-bold fs-6 text-gray-800">
                <th>Sl.</th>
                <th>Provider Name</th>
                <th class="text-center">Shipping Charge</th>
                <th class="text-center">Status</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @if( $provider_methods->count() > 0 )
                @foreach( $provider_methods as $id => $provider_method )
                    <tr>
                        <td>{{ $id+1 }}</td>
                        <td style="font-weight: 600;">{{$provider_method->provider_name}}</td>
                        <td class="text-center">
                            <span class="badge badge-light-{{$provider_method->provider_charge > 20 ? 'primary' : 'danger'}}">
                                {{$provider_method->provider_charge}}৳
                            </span>
                        </td>
                        <td class="text-center">
                            <label class="form-check form-switch form-check-custom form-check-solid status-toggle">
                                <input 
                                    class="form-check-input change-status" 
                                    type="checkbox"  
                                    wire:click="updateStatus({{ $provider_method->id }}, {{ $provider_method->status == 1 ? 0 : 1 }})"
                                    {{ $provider_method->status == 1 ? 'checked' : '' }}
                                >
                            </label>
                        </td>
                        <td class="text-end">
                            <a href="javascript:;" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                                Actions
                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            </a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="javascript:;" class="menu-link px-3" data-kt-shipping-id="{{$provider_method->id}}" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_regular_shipping" data-kt-regular-action="update_row">
                                        Edit
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="javascript:;" class="menu-link px-3" data-kt-shipping-id="{{$provider_method->id}}" data-kt-regular-action="delete_row">
                                        Delete
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                @endforeach
            @else
                <tr >
                    <td colspan="5">
                        <div class="dataTables_empty">No data available in table</div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center" style="padding: 20px 0 10px;">
        <!-- Total and current records count -->
        <p style="margin: 0; font-weight: 500; color: #99A1B7;">
            Showing {{ $provider_methods->firstItem() }} to {{ $provider_methods->lastItem() }} of {{ $totalMethods }} entries.
        </p>
        
        <div>
            {{ $provider_methods->links() }}
        </div>
    </div>
</div>