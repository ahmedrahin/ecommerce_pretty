<div class="modal fade" id="kt_modal_add_base_shipping" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ $edit_mode ? 'Edit Base Shipping Method' : 'Add Base Shipping Method' }}</h2>
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
                <form wire:submit.prevent="submit">
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_state_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_header" data-kt-scroll-wrappers="#kt_modal_add_state_scroll" data-kt-scroll-offset="300px">

                        <!-- district list -->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Base Location</label>

                            <select name="base_id" wire:model="base_id" id="base_id"
                                class="form-select form-select-solid @error('base_id') error-border @enderror" data-placeholder="Select a district">
                                <option></option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>

                            
                            @error('base_id')
                                <span class="text-danger">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Charge Amount</label>

                            <input type="text" wire:model.defer="base_charge" name="base_charge"
                                class="form-control form-control-solid mb-3 mb-lg-0 @error('base_charge') error-border @enderror" placeholder="00.00" />
                            
                            @error('base_charge')
                                <span class="text-danger">{{ $message }}</span> 
                            @enderror
                        </div>

                    </div>

                     <div class="text-center pt-3">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"
                            >Cancel</button>
                        <button type="submit" class="btn btn-primary" data-kt-base-shipping-modal-action="submit" style="width: 200px !important;">
                            @if( !$edit_mode )
                                <span class="indicator-label" wire:loading.remove wire:target="submit">Save Method</span>
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
         document.addEventListener('livewire:load', function () {      
            Livewire.hook('message.processed', (message, component) => {
                if (component.el.id === 'kt_modal_add_base_shipping') {
                    const selectElement = $('#base_id');
                    selectElement.select2({
                        placeholder: "Select a district",
                        allowClear: false,
                        dropdownParent: $("#kt_modal_add_base_shipping"),
                    });
                    
                    // Attach event handler only once
                    selectElement.on('change', function (e) {
                        Livewire.find(component.id).set('base_id', e.target.value);
                    });
                }
            });
        });

        let Openmodal = document.querySelector('#kt_modal_add_base_shipping');
        Openmodal.addEventListener('show.bs.modal', (e) => {
                Livewire.emit('open_add_modal');
            });
    </script>
@endpush
