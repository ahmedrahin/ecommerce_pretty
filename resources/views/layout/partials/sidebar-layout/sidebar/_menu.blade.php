<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true"
        data-kt-scroll-activate="true" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
        data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu"
            data-kt-menu="true" data-kt-menu-expand="false">
            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="ki-duotone ki-home-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="menu-title">Dashboard</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div class="menu-item pt-1">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">User Directory</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('admin-management.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <i class="ki-duotone ki-user-tick">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <span class="menu-title">Admin Management</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin-management.admin-list.*') ? 'active' : '' }}"
                            href="{{ route('admin-management.admin-list.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Admin List</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin-management.roles.*') ? 'active' : '' }}"
                            href="{{ route('admin-management.roles.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Roles</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('admin-management.permissions.*') ? 'active' : '' }}"
                            href="{{ route('admin-management.permissions.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Permissions</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>

            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('user-management.*') || request()->routeIs('question.*') || request()->routeIs('contact.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <i class="ki-duotone ki-profile-user">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    <span class="menu-title">User Management</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('user-management.users.index') ? 'active' : '' }}"
                            href="{{ route('user-management.users.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">User List</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('contact.*') ? 'active' : '' }}"
                            href="{{ route('contact.weekly.message') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Customer Messages</span>
                        </a>
                        <!--end:Menu link-->
                    </div>

                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('question.*') ? 'active' : '' }}"
                            href="{{ route('question.weekly') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Product Questions</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                </div>
                <!--end:Menu sub-->
            </div>


            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">PRODUCT CATALOGUE</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('product-catalogue.brand.index') ? 'active' : '' }}"
                    href="{{ route('product-catalogue.brand.index') }}">
                    <i class="ki-duotone ki-medal-star">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    <span class="menu-title">Brand</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            <!-- categories -->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('product-catalogue.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <i class="ki-duotone ki-category">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    <span class="menu-title">Categories</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('product-catalogue.category.index') ? 'active' : '' }}"
                            href="{{ route('product-catalogue.category.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Category</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('product-catalogue.subcategory.index') ? 'active' : '' }}"
                            href="{{ route('product-catalogue.subcategory.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Subcategory</span>
                        </a>
                        <!--end:Menu link-->
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('product-catalogue.subsubcategory.index') ? 'active' : '' }}"
                            href="{{ route('product-catalogue.subsubcategory.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Subsubcategory</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('product-catalogue.product.filter') ? 'active' : '' }}"
                            href="{{ route('product-catalogue.product.filter') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Category Filter</span>
                        </a>
                    </div>

                </div>
            </div>

            <!--begin:Menu item product-menu-items -->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('product-management.*', 'product-variant.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <i class="ki-duotone ki-gift">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    <span class="menu-title">Product Management</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('product-management.index*') ? 'active' : '' }}"
                            href="{{ route('product-management.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">All Products</span>
                        </a>
                        <!--end:Menu link-->
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('product-management.create*') ? 'active' : '' }}"
                            href="{{ route('product-management.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Add New Product</span>
                        </a>
                        <!--end:Menu link-->
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('product-variant.index*') ? 'active' : '' }}"
                            href="{{ route('product-variant.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Product Variant</span>
                        </a>

                        <a class="menu-link {{ request()->routeIs('product-management.printful*') ? 'active' : '' }}"
                            href="{{ route('product-management.printful') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Printful Products</span>
                        </a>
                        <!--end:Menu link-->
                        {{-- <a class="menu-link {{ request()->routeIs('product-management.full.compare*') ? 'active' : '' }}"
                            href="{{ route('product-management.full.compare') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Featured Compare</span>
                        </a> --}}
                    </div>

                </div>
                <!--end:Menu sub-->
            </div>

            {{-- order catelogue start --}}

            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content" style="display: flex;align-items: center;justify-content: space-between;">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Order management</span>
                    {{-- order notification --}}
                    <livewire:order.order-count-notify />
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('order-management.order*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <i class="ki-duotone ki-handcart"></i>
                    <span class="menu-title">Order Management</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('order-management.order.today') ? 'active' : '' }}"
                            href="{{ route('order-management.order.today') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Today Order List</span>
                        </a>
                        <a class="menu-link {{ request()->routeIs('order-management.order.monthly') ? 'active' : '' }}"
                            href="{{ route('order-management.order.monthly', [date('Y'), \Carbon\Carbon::now()->format('M')]) }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Monthly Order Sheet</span>
                        </a>
                        <a class="menu-link {{ request()->routeIs('order-management.order.index*') ? 'active' : '' }}"
                            href="{{ route('order-management.order.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">All Order List</span>
                        </a>

                        <!--begin:Menu link-->
                        {{-- <a class="menu-link {{ request()->routeIs('order-management.order.create*') ? 'active' : '' }}"
                            href="{{ route('order-management.order.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Add New Order</span>
                        </a> --}}
                        <!--end:Menu link-->

                        <a class="menu-link {{ request()->routeIs('order-management.order.trash*') ? 'active' : '' }}"
                            href="{{ route('order-management.order.trash') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Trash Orders</span>
                        </a>
                    </div>

                </div>
                <!--end:Menu sub-->
            </div>

            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Marketing CATALOGUE</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('subscription*') ? 'active' : '' }}"
                    href="{{ route('subscription.index') }}">
                    <i class="ki-duotone ki-sms">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="menu-title">Subscription Emails</span>
                </a>
                <!--end:Menu link-->
            </div>

            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('coupon*') ? 'active' : '' }}"
                    href="{{ route('coupon.index') }}">
                    <i class="ki-duotone ki-discount">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="menu-title">Coupon</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('review*') ? 'active' : '' }}"
                    href="{{ route('review.index') }}">
                    <i class="ki-duotone ki-star"></i>
                    <span class="menu-title">Reviews</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

             <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('slider.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <i class="ki-duotone ki-picture">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="menu-title">Home Banner</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('slider.index') ? 'active' : '' }}"
                            href="{{ route('slider.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Banner Images</span>
                        </a>
                        <a class="menu-link {{ request()->routeIs('slider.content') ? 'active' : '' }}"
                            href="{{ route('slider.content') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Banner Content</span>
                        </a>
                    </div>

                </div>
            </div>

            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Shipping Management</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('shipping.state') ? 'active' : '' }}"
                    href="{{ route('shipping.state') }}">
                    <i class="ki-duotone ki-geolocation">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="menu-title">State / Cities</span>
                </a>

                 <a class="menu-link {{ request()->routeIs('shipping.shipping_method') ? 'active' : '' }}"
                    href="{{ route('shipping.shipping_method') }}">
                    <i class="ki-duotone ki-truck">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                    <span class="menu-title">Shipping Method</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            {{-- <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Reports</span>
                </div>
                <!--end:Menu content-->
            </div> --}}

             {{-- <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('report.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <i class="ki-duotone ki-questionnaire-tablet">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="menu-title">Stock & Inventory</span>
                    <span class="menu-arrow"></span>
                </span>

                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('report.stockout') ? 'active' : '' }}"
                            href="{{ route('report.stockout') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Stock Out Report</span>
                        </a>
                         <a class="menu-link {{ request()->routeIs('report.lowstock') ? 'active' : '' }}"
                            href="{{ route('report.lowstock') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Low Stock Report</span>
                        </a>
                         <a class="menu-link {{ request()->routeIs('report.stockin') ? 'active' : '' }}"
                            href="{{ route('report.stockin', [date('Y'), \Carbon\Carbon::now()->format('M')]) }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Stock In Report</span>
                        </a>
                    </div>
                </div>
            </div> --}}

            <!--settings:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Platform Settings</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->

            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('setting.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <i class="ki-duotone ki-setting-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="menu-title">Settings</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('setting.manage') ? 'active' : '' }}"
                            href="{{ route('setting.manage') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Genarel Settings</span>
                        </a>
                        <a class="menu-link {{ request()->routeIs('setting.website') ? 'active' : '' }}"
                            href="{{ route('setting.website') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Website Settings</span>
                        </a>
                    </div>

                </div>
            </div>

        </div>
        <!--end::Menu-->
    </div>
    <!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
