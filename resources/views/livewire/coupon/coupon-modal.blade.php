<div class="modal fade" id="kt_modal_coupon" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-850px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_coupon_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">
                    {{ $edit_mode ? 'Update Coupon' : 'Add New Coupon' }}
                </h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                
            </div>

            <div class="modal-body px-5 my-7">
                <form wire:submit.prevent="submit" enctype="multipart/form-data">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10"  data-kt-scroll-offset="300px">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="fv-row mb-7">
                                    <label class="fw-semibold fs-6 mb-2 required">Coupon Code</label>
                                    <input type="text" wire:model.defer="code" name="code"
                                        class="form-control form-control-solid mb-3 mb-lg-0 @error('code') error-border @enderror" placeholder="Enter code" />

                                    @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="fw-semibold fs-6 mb-2">Min Purchase Amount</label>
                                    <input type="text" wire:model.defer="min_purchase_amount" name="min_purchase_amount"
                                        class="form-control form-control-solid mb-3 mb-lg-0 @error('min_purchase_amount') error-border @enderror" placeholder="Enter amount" />
                                    @error('min_purchase_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="fw-semibold fs-6 mb-2">Usage Limit</label>
                                    <input type="text" wire:model.defer="usage_limit" name="usage_limit"
                                        class="form-control form-control-solid mb-3 mb-lg-0 @error('usage_limit') error-border @enderror" placeholder="Enter limit" />
                                    @error('usage_limit')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="fw-semibold fs-6 mb-2">Used User</label>
                                    <input type="text" wire:model.defer="used" name="used"
                                        class="form-control form-control-solid mb-3 mb-lg-0 @error('used') error-border @enderror" placeholder="Enter used limit" />
                                    @error('used')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="fv-row mb-7">
                                    <label class="fw-semibold fs-6 mb-2 required">Discount Amount</label>
                                    <input type="text" wire:model.defer="discount_amount" name="discount_amount"
                                        class="form-control form-control-solid mb-3 mb-lg-0 @error('discount_amount') error-border @enderror" placeholder="Discount Amount" />

                                    @error('discount_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="fw-semibold fs-6 mb-2 required">Discount Type</label>
                                    <select name="discount_type" id="discount_type"
                                    class="form-select form-select-solid @error('discount_type') error-border @enderror" wire:model="discount_type" data-placeholder="Select type">
                                        <option></option>
                                        <option value="percentage">Percentage</option>
                                        <option value="fixed">Fixed</option>
                                    </select>

                                    @error('discount_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="fw-semibold fs-6 mb-2 required">Start Date</label>
                                    <input id="coupon_start_at" name="start_at" placeholder="Select a date" class="form-control form-control-solid mb-0 @error('start_at') error-border @enderror" wire:model="start_at" />
                                    @error('start_at')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="fw-semibold fs-6 mb-2">Expire Date</label>
                                    <input id="coupon_expire_at" name="expire_date" placeholder="Select a date" class="form-control form-control-solid mb-0 @error('expire_date') error-border @enderror" wire:model="expire_date" />
                                    @error('expire_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Select Categories</label>
                                <select name="category_ids" id="coupon_categories"
                                    class="form-select form-select-solid @error('category_ids') error-border @enderror"
                                    wire:model="category_ids" multiple data-placeholder="Select categories">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_ids')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="text-center pt-7">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary" data-kt-coupon-modal-action="submit" style="width: 200px !important;">
                            @if( !$edit_mode )
                                <span class="indicator-label" wire:loading.remove wire:target="submit">Save Coupon</span>
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
        // Initialize flatpickr on load and reinitialize after each Livewire update
        function initializeFlatpickr() {
            $('#coupon_start_at').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });
            $('#coupon_expire_at').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });
        }

        initializeFlatpickr();

        // Define option format
        const optionFormat = (item) => {
            if (!item.id) {
                return item.text;
            }

            var span = document.createElement('span');
            span.innerHTML = item.text;
            return $(span);
        };

        // Emit event to Livewire when the modal opens
        document.querySelector('#kt_modal_coupon').addEventListener('show.bs.modal', () => {
            Livewire.emit('open_add_modal');
        });

        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', (message, component) => {
                if (component.el.id === 'kt_modal_coupon') {
                    initializeFlatpickr();

                    const categorySelect = $('#coupon_categories');
                    categorySelect.select2({
                        placeholder: "Select categories",
                        dropdownParent: $("#kt_modal_coupon"),
                        allowClear: true,
                        minimumResultsForSearch: 0
                    });

                    categorySelect.off('change').on('change', function (e) {
                        Livewire.find(component.id).set('category_ids', $(this).val());
                    });

                    const typeSelect = $('#discount_type');
                    typeSelect.select2({
                        placeholder: "Select type",
                        allowClear: false,
                        dropdownParent: $("#kt_modal_coupon"),
                        minimumResultsForSearch: Infinity
                    });

                    typeSelect.off('change').on('change', function (e) {
                        Livewire.find(component.id).set('discount_type', e.target.value);
                    });
                }
            });
        });

    </script>
@endpush
