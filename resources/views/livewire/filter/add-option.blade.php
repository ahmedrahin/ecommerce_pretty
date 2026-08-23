<div>
    <div class="modal fade" id="kt_modal_add_attribute" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_attribute_header">
                    <h2 class="fw-bold">{{ $edit_mode ? 'Update Attribute' : 'Add Filter Attribute' }}</h2>

                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        {!! getIcon('cross', 'fs-1') !!}
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body px-5 my-7">
                    <form id="kt_modal_attribute_form" class="form" action="#" wire:submit.prevent="submit">
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_attribute_scroll"
                            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_add_attribute_header"
                            data-kt-scroll-wrappers="#kt_modal_add_attribute_scroll" data-kt-scroll-offset="300px">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Name</label>
                                <input type="text" wire:model.defer="attr_name" name="attr_name"
                                    class="form-control form-control-solid mb-3 mb-lg-0 @error('attr_name') error-border @enderror "
                                    placeholder="Attribute name" />
                                @error('attr_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="fv-row mb-7" wire:ignore>
                                <label class="fw-semibold fs-6 mb-2">Select Categories</label>
                               <select name="category_ids" id="categories"
                                    class="form-select form-select-solid @error('category_ids') error-border @enderror"
                                    wire:model="category_ids" multiple data-placeholder="Select categories">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($edit_mode)
                                <input type="hidden" wire:model.defer="attr_id" name="attr_id" />
                            @endif
                        </div>
                        <div class="text-center pt-3">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"
                                wire:loading.attr="disabled">Cancel</button>
                            <button type="submit" class="btn btn-primary" data-kt-attribute-modal-action="submit"
                                style="width: 200px !important;">
                                @if (!$edit_mode)
                                    <span class="indicator-label" wire:loading.remove wire:target="submit">Save
                                        Attribute</span>
                                @else
                                    <span class="indicator-label" wire:loading.remove wire:target="submit">Save
                                        Changes</span>
                                @endif
                                <span class="indicator-progress" wire:loading wire:target="submit">
                                    Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        document.querySelector('#kt_modal_add_attribute').addEventListener('show.bs.modal', () => {
            Livewire.emit('open_add_modal');
        });

        document.addEventListener('livewire:load', function () {
            // Initialize Select2
            $('#categories').select2({
                placeholder: "Select Categories",
                allowClear: true
            });

            // On change, update Livewire property
            $('#categories').on('change', function (e) {
                var data = $(this).val(); 
                @this.set('category_ids', data);
            });

            Livewire.on('refreshSelect2', function () {
                $('#categories').val(@this.get('category_ids')).trigger('change');
            });
        });
    </script>
@endpush

