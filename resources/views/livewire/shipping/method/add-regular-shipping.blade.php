<div>
    <div class="modal fade" id="kt_modal_regular_shipping" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_regular_shipping_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">
                        {{ $edit_mode ? 'Update Shipping' : 'Add New Shipping' }}
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
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_regular_shipping_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_regular_shipping_header" data-kt-scroll-wrappers="#kt_modal_add_regular_shipping_scroll" data-kt-scroll-offset="300px">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Provider Name</label>
                                <input type="text" wire:model.defer="provider_name" name="provider_name" class="form-control form-control-solid mb-3 mb-lg-0 @error('provider_name') error-border @enderror " placeholder="Provider name" />
                                @error('provider_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Provider Charge</label>
                                <input type="text" wire:model.defer="provider_charge" name="provider_charge" class="form-control form-control-solid mb-3 mb-lg-0 @error('provider_charge') error-border @enderror " placeholder="00.00" />
                                @error('provider_charge') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            @if($edit_mode)
                                <!-- <input type="hidden" wire:model.defer="brand_id" name="brand_id" /> -->
                            @endif
                        </div>
                        <div class="text-center pt-7">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">Cancel</button>
                            <button type="submit" class="btn btn-primary" data-kt-regular-shipping-action="submit" style="width: 200px !important;">
                                @if( !$edit_mode )
                                    <span class="indicator-label" wire:loading.remove wire:target="submit">Save Shipping</span>
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
        const modal = document.querySelector('#kt_modal_regular_shipping');
        modal.addEventListener('show.bs.modal', (e) => {
            // Livewire.emit('modal.show', e.relatedTarget.getAttribute('data-shipping-id'));
            // Emit the Livewire event to reset the form
            Livewire.emit('open_add_modal');
        });
    </script>
@endpush
</div>
