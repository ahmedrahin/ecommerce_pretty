<div>
    <div class="modal fade" id="kt_modal_add_district" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_district_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">
                        {{ $edit_mode ? 'Update District' : 'Add New District' }}
                    </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        {!! getIcon('cross','fs-1') !!}
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body px-5 my-7">
                    <!--begin::Form-->
                    <form wire:submit.prevent="submit" enctype="multipart/form-data">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_district_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_district_header" data-kt-scroll-wrappers="#kt_modal_add_district_scroll" data-kt-scroll-offset="300px">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">District Name</label>
                                <input type="text" wire:model.defer="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0 @error('name') is_valid @enderror" placeholder="District name" />
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            @if($edit_mode)
                                <input type="hidden" wire:model.defer="district_id" name="district_id" />
                            @endif
                        </div>
                        <div class="text-center pt-3">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"
                                wire:loading.attr="disabled">Cancel</button>
                            <button type="submit" class="btn btn-primary" data-kt-brand-modal-action="submit" style="width: 200px !important;">
                                @if( !$edit_mode )
                                    <span class="indicator-label" wire:loading.remove wire:target="submit">Save District</span>
                                @else
                                    <span class="indicator-label" wire:loading.remove wire:target="submit">Save Changes</span>
                                @endif
                                <span class="indicator-progress" wire:loading wire:target="submit">
                                    Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
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
            const modal = document.querySelector('#kt_modal_add_district');
            modal.addEventListener('show.bs.modal', (e) => {
                Livewire.emit('modal.show.district', e.relatedTarget.getAttribute('data-district-id'));
                // Emit the Livewire event to reset the form
                Livewire.emit('open_add_modal');
            });
        </script>
    @endpush
</div>
