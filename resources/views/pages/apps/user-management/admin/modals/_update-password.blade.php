
<div class="modal fade" id="kt_modal_update_password" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Update Password</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="kt_modal_update_password_form" class="form" wire:submit.prevent="ChnagePassword">
                    <!--begin::Input group=-->
                    <div class="fv-row mb-10">
                        <label class="required form-label fs-6 mb-2">Current Password</label>
                        <input class="form-control form-control-lg form-control-solid @error('current_password') error-border  @enderror" type="password" placeholder="******" name="current_password" autocomplete="off" wire:model="current_password" />
                        @error('current_password')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <!--end::Input group=-->
                    <!--begin::Input group-->
                    <div class="mb-10 fv-row" data-kt-password-meter="true">
                        <!--begin::Wrapper-->
                        <div class="mb-1">
                            <!--begin::Label-->
                            <label class="form-label fw-semibold fs-6 mb-2">New Password</label>
                            <!--end::Label-->
                            <!--begin::Input wrapper-->
                            <div class="position-relative mb-3">
                                <input class="form-control form-control-lg form-control-solid @error('new_password') error-border  @enderror" type="password" placeholder="enter a new password" name="new_password" autocomplete="off" wire:model="new_password" />
                                @error('new_password')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                    <i class="ki-duotone ki-eye-slash fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                    <i class="ki-duotone ki-eye d-none fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                            <!--end::Input wrapper-->
                            <!--begin::Meter-->
                            <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                            </div>
                            <!--end::Meter-->
                        </div>
                        <!--end::Wrapper-->
                        <!--begin::Hint-->
                        <div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols to strong password.</div>
                        <!--end::Hint-->
                    </div>
                    <!--end::Input group=-->
                    <!--begin::Input group=-->
                    <div class="fv-row mb-10">
                        <label class="form-label fw-semibold fs-6 mb-2">Confirm New Password</label>
                        <input class="form-control form-control-lg form-control-solid @error('new_password_confirmation') error-border  @enderror" type="password" placeholder="enter a confirm password" name="new_password_confirmation" autocomplete="off" wire:model="new_password_confirmation" />
                        @error('new_password_confirmation')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <!--end::Input group=-->
                    <!--begin::Actions-->
                    <div class="text-center pt-5">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <!--end::Button-->
                        <button type="ChnagePassword" class="btn btn-primary" data-kt-brand-modal-action="ChnagePassword" style="width: 200px !important;">
                            <span class="indicator-label" wire:loading.remove wire:target="ChnagePassword">Save Changes</span>
                            <span class="indicator-progress" wire:loading wire:target="ChnagePassword">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>

@push('scripts')
<script>
    const modal = document.querySelector('#kt_modal_update_password');
    modal.addEventListener('show.bs.modal', (e) => {
        Livewire.emit('open_add_modal');
    });
</script>
@endpush