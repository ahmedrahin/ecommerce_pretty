<x-default-layout>

    @section('title')
        Genarel Setting
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('setting') }}
    @endsection

    <style>
        .nav-line-tabs .nav-item .nav-link {
            margin: 0 1.3rem !important;
            font-size: 17px !important;
        }
    </style>


    <div id="kt_account_settings_profile_details" class="collapse show">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container">
                <!--begin::Card-->
                <div class="card card-flush">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold ">

                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 active"
                                    data-bs-toggle="tab" href="#kt_ecommerce_settings_store">
                                    <i class="ki-duotone ki-shop fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>Store</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5"
                                    data-bs-toggle="tab" href="#kt_ecommerce_settings_follow">
                                    <i class="ki-duotone ki-user fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Follow On</a>
                            </li>

                        </ul>
                        <!--end:::Tabs-->
                        <!--begin:::Tab content-->
                        <div class="tab-content" id="myTabContent">

                            <div class="tab-pane fade show active" id="kt_ecommerce_settings_store" role="tabpanel">
                                <livewire:settings.genarel-settings></livewire:settings.genarel-settings>
                            </div>

                            <div class="tab-pane fade " id="kt_ecommerce_settings_follow" role="tabpanel">
                                <livewire:settings.follow-on></livewire:settings.follow-on>
                            </div>

                        </div>
                        <!--end:::Tab content-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content container-->
        </div>
    </div>

</x-default-layout>
