<div>
    <div class="d-flex flex-center flex-column py-5 pb-0">
        <!--begin::Avatar-->
        <div class="symbol symbol-100px symbol-circle mb-5">
            @if($user->avatar)
                <img src="{{ asset($user->avatar) }}" alt="image" />
            @else
            <div
                class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $user->name) }}">
                {{ substr($user->name, 0, 1) }}
            </div>
            @endif
        </div>
        <!--end::Avatar-->
        <!--begin::Name-->
        <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{{ $user->name }}</a>
        <!--end::Name-->

    </div>

    <div class="d-flex flex-wrap flex-center">
        <!--begin::Stats-->
        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
            <div class="fs-4 fw-bold text-gray-700 text-center">
                <span class="w-75px">{{ $total_orders ?? 0 }}</span>
                <i class="ki-duotone ki-arrow-{{ $total_orders > 0 ? 'up' : 'down' }} fs-3 text-{{ $total_orders > 0 ? 'success' : 'danger' }}">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <div class="fw-semibold text-muted">Total Orders</div>
        </div>

        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
            <div class="fs-4 fw-bold text-gray-700 text-center">
                <span class="w-50px">{{ $total_buying ?? 0 }}</span>
                <i class="ki-duotone ki-arrow-{{ $total_buying > 0 ? 'up' : 'down' }} fs-3 text-{{ $total_buying > 0 ? 'success' : 'danger' }}">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <div class="fw-semibold text-muted">Total Buying</div>
        </div>
    </div>
    <!--end::Info-->
    <div class="d-flex flex-stack fs-4 py-3">
        <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details"
            role="button" aria-expanded="false" aria-controls="kt_user_view_details">Details
            <span class="ms-2 rotate-180">
                <i class="ki-duotone ki-down fs-3"></i>
            </span>
        </div>

    </div>


    <div class="separator"></div>
    <!--begin::Details content-->
    <div id="kt_user_view_details" class="collapse show">
        <div class="pb-5 fs-6">
            <!--begin::Details item-->
            <div class="fw-bold mt-5">Account ID</div>
            <div class="text-gray-600">ID-#{{$user->id}}</div>
            <!--begin::Details item-->
            <!--begin::Details item-->
            <div class="fw-bold mt-5">Email</div>
            <div class="text-gray-600">
                <a href="mailto:{{$user->email}};" class="text-gray-600 text-hover-primary">{{$user->email}}</a>
            </div>

            <div class="fw-bold mt-5">Phone no.</div>
            <div class="text-gray-600">
                <a href="tel:{{$user->phone}}" class="text-gray-600 text-hover-primary">{{$user->phone}}</a>
            </div>
            <!--begin::Details item-->
            <!--begin::Details item-->
            <div class="fw-bold mt-5">Address</div>
            @if(!is_null($user->address_line1) && !is_null($user->address_line2) && !is_null($user->district_id))
                <div class="text-gray-600">
                    --
                </div>
            @else
                <div class="text-gray-600">
                    {{$user->address_line1}}
                    <br />{{$user->address_line2}}
                    @if( !is_null($user->division_id) )
                        <br />{{$user->division->name}}
                    @endif
                </div>
            @endif

            @if( !is_null($user->zipCode) )
                <div class="fw-bold mt-5">Post/Zip Code</div>
                <div class="text-gray-600">{{ $user->zipCode }}</div>
           @endif

            <div class="fw-bold mt-5">Last Login</div>
            <div class="text-gray-600">{{ $user->last_login_at ? $user->last_login_at->timezone('Asia/Dhaka')->format('d M Y, g:i a') : 'No login yet' }}
            </div>

            <div class="fw-bold mt-5">Join Date</div>
            <div class="text-gray-600">{{ $user->created_at->timezone('Asia/Dhaka')->format('d M Y, g:i a') }}
            </div>

            <div class="fw-bold mt-5">Last Updated</div>
            <div class="text-gray-600">{{ $user->updated_at->timezone('Asia/Dhaka')->format('d M Y, g:i a') }}
            </div>

             <div class="d-flex gap-5">
                <div>
                    <div class="fw-bold mt-5 mb-2">Status</div>
                    <label class="form-check form-switch form-check-custom form-check-solid status-toggle">
                        <input
                            class="form-check-input change-status"
                            type="checkbox"
                            wire:click="updateStatus({{ $user->id }})"
                            wire:model="status"
                        >
                    </label>
                </div>
            </div>
        </div>
    </div>

    </div>
