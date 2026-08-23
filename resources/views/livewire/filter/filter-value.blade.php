<div class="row">
    @foreach($attributeValues as $attributeValue)
        <div class="col-md-4 mb-5">
            <div class="card bg-light shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">{{ $attributeValue->option_name }}</h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn add btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_attribute_value{{ $attributeValue->id }}" wire:click="openAddModal({{ $attributeValue->id }})">
                            <i class="ki-duotone ki-plus fs-2"></i>
                            Add Value
                        </button>
                    </div>
                </div>

                <!-- add modal -->
                <div class="modal fade" id="kt_modal_add_attribute_value{{$attributeValue->id}}" tabindex="-1" aria-hidden="true" wire:ignore.self>
                    <!--begin::Modal dialog-->
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <!--begin::Modal content-->
                        <div class="modal-content">
                            <!--begin::Modal header-->
                            <div class="modal-header" id="kt_modal_add_attribute_value_header">
                                <!--begin::Modal title-->
                                <h2 class="fw-bold">Add a Value for {{ $attributeValue->option_name }}</h2>
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                                    {!! getIcon('cross','fs-1') !!}
                                </div>
                            </div>

                            <div class="modal-body px-5 my-7">
                                <!--begin::Form-->
                                <form id="kt_modal_attribute_form" class="form" action="#" wire:submit.prevent="submit">
                                    <!--begin::Scroll-->
                                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_attribute_value_scroll"
                                        data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                                        data-kt-scroll-dependencies="#kt_modal_add_attribute_value_header"
                                        data-kt-scroll-wrappers="#kt_modal_add_attribute_value_scroll" data-kt-scroll-offset="300px">
                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Name</label>
                                            <input type="text" wire:model.defer="attr_value" name="attr_value"
                                                class="form-control form-control-solid mb-3 mb-lg-0 @error('attr_value') error-border @enderror " placeholder="Value name" />
                                            @error('attr_value')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="text-center pt-5">
                                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"
                                            wire:loading.attr="disabled">Cancel</button>
                                            <button type="submit" class="btn btn-primary" data-kt-attribute-modal-action="submit" style="width: 200px !important;">
                                                @if( !$edit_mode )
                                                    <span class="indicator-label" wire:loading.remove wire:target="submit">Save Value</span>
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

                <div class="card-body card-scroll h-200px">
                    <ul>
                        @forelse($attributeValue->values as $valuesItem)
                            <li>
                                {{ $valuesItem->option_value }}
                                <div class="action-box">
                                    <button data-bs-toggle="modal" data-bs-target="#kt_modal_edit_attribute_value{{ $valuesItem->id }}" wire:click="updateAttrValue({{ $valuesItem->id }})">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="delVal" data-kt-val-id="{{$valuesItem->id}}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </li>

                            <div class="modal fade" id="kt_modal_edit_attribute_value{{$valuesItem->id}}" tabindex="-1" aria-hidden="true" wire:ignore.self>
                                <!--begin::Modal dialog-->
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header" id="kt_modal_add_attribute_value_header">
                                            <!--begin::Modal title-->
                                            <h2 class="fw-bold">{{ $edit_mode ? 'Update Value' : 'Add a Value' }}</h2>
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
                                            <form id="kt_modal_attribute_form" class="form" action="#" wire:submit.prevent="submit">
                                                <!--begin::Scroll-->
                                                <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_attribute_value_scroll"
                                                    data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                                                    data-kt-scroll-dependencies="#kt_modal_add_attribute_value_header"
                                                    data-kt-scroll-wrappers="#kt_modal_add_attribute_value_scroll" data-kt-scroll-offset="300px">
                                                    <div class="fv-row mb-7">
                                                        <label class="fw-semibold fs-6 mb-2">Name</label>
                                                        <input type="text" wire:model.defer="attr_value" name="attr_value"
                                                            class="form-control form-control-solid mb-3 mb-lg-0 @error('attr_value') error-border @enderror " placeholder="Value name" />
                                                        @error('attr_value')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <input type="hidden" wire:model.defer="attr_id" name="attr_id" value="{{ $attributeValue->id }}">

                                                    @if($edit_mode)
                                                        <input type="hidden" wire:model.defer="value_id" name="value_id" />
                                                    @endif
                                                </div>
                                                <div class="text-center pt-5">
                                                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"
                                                        wire:loading.attr="disabled">Cancel</button>
                                                        <button type="submit" class="btn btn-primary" data-kt-attribute-modal-action="submit" style="width: 200px !important;">
                                                            @if( !$edit_mode )
                                                                <span class="indicator-label" wire:loading.remove wire:target="submit">Save Value</span>
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
                        @empty
                            <div class="noValue">
                                <span>No value found</span>
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
    <!-- Blade Template e Scripts Part e: -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn.add').forEach(button => {
                button.addEventListener('click', function () {
                    const attrValueId = this.getAttribute('data-bs-target').replace('#kt_modal_add_attribute_value', '');
                    Livewire.emit('open_value_modal', attrValueId);
                });
            });

            Livewire.on('close_edit_modal', function (attrValueId) {
                $('#kt_modal_edit_attribute_value' + attrValueId).modal('hide');
            });

            Livewire.on('close_modal', function (attrValueId) {
                $('#kt_modal_add_attribute_value' + attrValueId).modal('hide');
            });
        });

            // Initialize KTMenu
            KTMenu.init();

            // Add click event listener to delete buttons
            function initializeDeleteButtons() {
                document.querySelectorAll('.delVal').forEach(function (element) {
                    element.addEventListener('click', function () {
                        Swal.fire({
                            text: 'Are you sure you want to remove?',
                            icon: 'warning',
                            buttonsStyling: false,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            customClass: {
                                confirmButton: 'btn btn-danger',
                                cancelButton: 'btn btn-secondary',
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Livewire.emit('delete_attrVal', this.getAttribute('data-kt-val-id'));
                            }
                        });
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                initializeDeleteButtons();

                Livewire.hook('message.processed', (message, component) => {
                    initializeDeleteButtons();
                });
            });
    </script>
@endpush
