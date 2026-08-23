<div class="modal fade" id="kt_modal_add_subsubcategory" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_subsubcategory_header">
                <h2 class="fw-bold">
                    {{ $edit_mode ? 'Update Subsubcategory' : 'Add New Subsubcategory' }}
                </h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_subsubcategory_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_subsubcategory_scroll"
                        data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_subsubcategory_header"
                        data-kt-scroll-wrappers="#kt_modal_add_subsubcategory_scroll" data-kt-scroll-offset="300px">
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Name</label>
                            <input type="text" wire:model.defer="name" name="name"
                                class="form-control form-control-solid mb-3 mb-lg-0"
                                placeholder="Subsubcategory name" />
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Subcategory</label>
                            <select name="subcategory_id" wire:model="subcategory_id" id="subcategory_id"
                                class="form-select form-select-solid @error('subcategory_id') is_valid @enderror" data-placeholder="Select a subcategory" >
                                <option></option>
                                @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->category->name . ' -> ' .
                                    $subcategory->name }}</option>
                                @endforeach
                            </select>
                            @error('subcategory_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if($edit_mode)
                        <input type="hidden" wire:model.defer="id" name="id" />
                        @endif
                    </div>

                    <div class="text-center pt-3">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"
                            wire:loading.attr="disabled">Cancel</button>
                        <button type="submit" class="btn btn-primary" data-kt-brand-modal-action="submit" style="width: 200px !important;">
                            @if( !$edit_mode )
                                <span class="indicator-label" wire:loading.remove wire:target="submit">Save Subcategory</span>
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
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        const modal = document.querySelector('#kt_modal_add_subsubcategory');

        modal.addEventListener('show.bs.modal', (e) => {
            const subsubcategoryId = e.relatedTarget.getAttribute('data-subsubcategory-id');
            const subcategoryId = e.relatedTarget.getAttribute('data-subcategory-id');
            Livewire.emit('modal.show.subsubcategory', subsubcategoryId);
            Livewire.emit('modal.show.subcategoryId', subcategoryId);
            Livewire.emit('open_add_modal');
        });

        Livewire.hook('message.processed', (message, component) => {
            if (component.el.id === 'kt_modal_add_subsubcategory') {
                const selectElement = $('#subcategory_id');
                selectElement.select2({
                    placeholder: "Select a subcategory",
                    allowClear: true,
                    dropdownParent: $("#kt_modal_add_subsubcategory"),
                });
                
                // Attach event handler only once
                selectElement.on('change', function (e) {
                    Livewire.find(component.id).set('subcategory_id', e.target.value);
                });
            }
        });
    });
</script>
@endpush