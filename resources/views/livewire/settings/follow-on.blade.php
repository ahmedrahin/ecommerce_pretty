<div>
    <form class="form" wire:submit.prevent="update">
        <!--begin::Card body-->
        <div class="card-body border-top p-9 py-4 pt-8">

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6 d-flex">
                    <i class="ki-duotone ki-facebook fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Facebook Link:
                </label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="title"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('facebook') error-border @enderror"
                                placeholder="Enter facebook link" wire:model="facebook" />
                            @error('facebook')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6 d-flex">
                    <i class="ki-duotone ki-instagram fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Instagram Link:
                </label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="title"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('instagram') error-border @enderror"
                                placeholder="Enter instagram link" wire:model="instagram" />
                            @error('instagram')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6 d-flex">
                    <i class="ki-duotone ki-youtube fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Youtube Link:
                </label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="title"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('youtube') error-border @enderror"
                                placeholder="Enter youtube link" wire:model="youtube" />
                            @error('youtube')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6 d-flex">
                    <i class="ki-duotone ki-whatsapp fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Whatsapp:
                </label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="title"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('whatsapp') error-border @enderror"
                                placeholder="Enter whatsapp number" wire:model="whatsapp" />
                            @error('whatsapp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

        </div>
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-0" style="border: none;">
            <button type="submit" class="btn btn-primary" data-kt-brand-modal-action="submit"
                style="width: 200px !important;">
                <span class="indicator-label" wire:loading.remove wire:target="update">Save Changes</span>
                <span class="indicator-progress" wire:loading wire:target="update">
                    Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
</div>
