<div class="modal fade" id="kt_modal_add_subcategory" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!-- Modal dialog -->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!-- Modal content -->
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header" id="kt_modal_add_subcategory_header">
                <!-- Modal title -->
                <h2 class="fw-bold">
                    {{ $edit_mode ? 'Update Subcategory' : 'Add New Subcategory' }}
                </h2>
                <!-- Close button -->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!-- End Close button -->
            </div>
            <!-- End Modal header -->
            <!-- Modal body -->
            <div class="modal-body px-5 my-7">
                <!-- Form -->
                <form id="kt_modal_subcategory_form" class="form" wire:submit.prevent="submit">
                    <!-- Scroll -->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_subcategory_scroll"
                        data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_subcategory_header"
                        data-kt-scroll-wrappers="#kt_modal_add_subcategory_scroll" data-kt-scroll-offset="350px" style="height: 350px;">

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Name</label>
                            <input type="text" wire:model.defer="name" name="name"
                                class="form-control form-control-solid mb-3 mb-lg-0 @error('name') error-border @enderror" placeholder="Subcategory name" />
                            @error('name')
                            <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Category</label>
                            <select name="category_id" wire:model="category_id" id="category_id"
                                class="form-select form-select-solid  @error('category_id') is_valid @enderror" data-placeholder="Select a category">
                                <option></option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Description</label>
                            <textarea name="description"
                            wire:model.defer="description"
                            class="form-control form-control-solid mb-3 mb-lg-0 @error('description') error-border @enderror"
                            placeholder="Write description..."
                            style="height: 130px;"></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="d-block fw-semibold fs-6 mb-3">Category Image</label>
                            <!--end::Label-->
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline {{ $image || $current_image ? '' : 'image-input-empty' }}" data-kt-image-input="true">
                                <!--begin::Preview existing avatar-->
                                @if ($image)
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $image->temporaryUrl() }}');"></div>
                                @elseif ($current_image)
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset($current_image) }}');"></div>
                                @else
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ asset('assets/media/svg/files/blank-image.svg') }});"></div>
                                @endif
                                <!--end::Preview existing avatar-->
                                
                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change image">
                                    {!! getIcon('pencil', 'fs-7') !!}
                                    <!--begin::Inputs-->
                                    <input type="file" wire:model.defer="image" name="image" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="avatar_remove" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                
                                <!--begin::Cancel-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                    {!! getIcon('cross', 'fs-2') !!}
                                </span>
                                <!--end::Cancel-->

                                <!--begin::Remove-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove image" wire:click="removeImage">
                                    {!! getIcon('cross', 'fs-2') !!}
                                </span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->
                            <!--begin::Hint-->
                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        
                        @if($edit_mode)
                        <input type="hidden" wire:model.defer="subcategory_id" name="subcategory_id" />
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
                <!-- End Form -->
            </div>
            <!-- End Modal body -->
        </div>
        <!-- End Modal content -->
    </div>
    <!-- End Modal dialog -->
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        const modal = document.querySelector('#kt_modal_add_subcategory');
        modal.addEventListener('show.bs.modal', (e) => {
            const categoryId = e.relatedTarget.getAttribute('data-category-id');
            const subcategoryId = e.relatedTarget.getAttribute('data-subcategory-id');
            Livewire.emit('modal.show.subcategory', subcategoryId);
            Livewire.emit('modal.show.categoryId', categoryId);

            Livewire.emit('open_add_modal');
        });

        Livewire.hook('message.processed', (message, component) => {
            if (component.el.id === 'kt_modal_add_subcategory') {
                const selectElement = $('#category_id');
                selectElement.select2({
                    placeholder: "Select a category",
                    allowClear: false,
                    dropdownParent: $("#kt_modal_add_subcategory"),
                });
                
                // Attach event handler only once
                selectElement.on('change', function (e) {
                    Livewire.find(component.id).set('category_id', e.target.value);
                });
            }
        });
    });
</script>
@endpush