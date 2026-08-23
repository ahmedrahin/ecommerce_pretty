<!--begin::Navbar-->

<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
    data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
    data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
    data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
    <!--begin::Menu-->
    <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
        id="kt_app_header_menu" data-kt-menu="true">
        <!--begin:Menu item-->
        <div data-kt-menu-placement="bottom-start"
            class="menu-item here menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
            <!--begin:Menu link-->
            <span class="menu-link">
                <span class="menu-title"><a href="{{ route('dashboard') }}">Dashboard</a></span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
        </div>

        {{-- <div data-kt-menu-placement="bottom-start"
            class="menu-item here menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
            <!--begin:Menu link-->
            <span class="menu-link">
                <span class="menu-title"><a href="{{ route('order-management.order.create') }}">Add New Order</a></span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
        </div> --}}
        <div data-kt-menu-placement="bottom-start"
            class="menu-item here menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
            <!--begin:Menu link-->
            <span class="menu-link">
                <span class="menu-title"><a href="{{ route('product-management.create') }}">Add New Product</a></span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
        </div>
    </div>
    <!--end::Menu-->
</div>

<div class="app-navbar flex-shrink-0">


    @include('partials/menus/_notifications-menu')

    <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
        <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
            data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            @if (Auth::user()->avatar)
                <img alt="Logo" src="{{ asset(Auth::user()->avatar) }}" />
            @else
                <div
                    class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', Auth::user()->name) }}">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            @endif
        </div>
        @include('partials/menus/_user-account-menu')

    </div>

    <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
        <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
            {!! getIcon('element-4', 'fs-1') !!}</div>
    </div>
</div>
