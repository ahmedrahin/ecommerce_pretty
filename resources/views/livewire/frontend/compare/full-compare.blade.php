<div class="card product-datatable">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#kt_modal_add_compare">
                    {!! getIcon('plus', 'fs-2', '', 'i') !!}
                    Add New
                </button>
            </div>
        </div>
    </div>

    <div class="card-body py-4">
        <div class="row">
            @foreach ($data as $index => $value)
                <div class="col-md-6 mb-8">
                    <div class="card card-flush h-xl-100 mb-4">
                        <!--begin::Header-->
                        <div class="card-header pt-7">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">{{ $index + 1 }}</span>
                            </h3>

                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-light-danger" data-kt-action="delete_row" data-kt-id="{{ $value->id }}">
                                    {!! getIcon('trash', 'fs-2', '', 'i') !!}
                                </button>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-7">
                            <!--begin::Row-->
                            <div class="row align-items-end h-100 gx-5 gx-xl-10">
                                <!--begin::Col-->
                                <div class="col-md-6 mb-11 mb-md-0">
                                    <!--begin::Overlay-->
                                    <a class="d-block overlay" data-fslightbox="lightbox-hot-sales"
                                        href="{{ route('product-management.show', $value->first_product_id) }}">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-position-center bgi-no-repeat bgi-size-cover h-200px card-rounded mb-3"
                                            style="height: 266px;background-image:url({{ asset($value->firstProduct->thumb_image) }})">
                                        </div>
                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                            <i class="ki-duotone ki-eye fs-3x text-white">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                    </a>
                                    <div class="m-0">
                                        <a href="{{ route('product-management.show', $value->first_product_id) }}"
                                            class="text-gray-800 text-center text-hover-primary fs-3 fw-bold d-block mb-2"
                                            style="font-size: 14px !important;">{{ $value->firstProduct->name }}</a>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-11 mb-md-0">
                                    <!--begin::Overlay-->
                                    <a class="d-block overlay" data-fslightbox="lightbox-hot-sales"
                                        href="{{ route('product-management.show', $value->second_product_id) }}">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-position-center bgi-no-repeat bgi-size-cover h-200px card-rounded mb-3"
                                            style="height: 266px;background-image:url({{ asset($value->secondProduct->thumb_image) }})">
                                        </div>
                                        <!--end::Image-->
                                        <!--begin::Action-->
                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                            <i class="ki-duotone ki-eye fs-3x text-white">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <!--end::Action-->
                                    </a>
                                    <!--end::Overlay-->
                                    <!--begin::Info-->
                                    <div class="m-0">
                                        <a href="{{ route('product-management.show', $value->second_product_id) }}"
                                            class="text-gray-800 text-center text-hover-primary fs-3 fw-bold d-block mb-2"
                                            style="font-size: 14px !important;">{{ $value->secondProduct->name }}</a>
                                    </div>
                                    <!--end::Info-->
                                </div>

                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <div class="modal fade" id="kt_modal_add_compare" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Add New Product Comparison</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        {!! getIcon('cross','fs-1') !!}
                    </div>
                </div>

                <div class="modal-body px-5 my-7">
                    <form wire:submit.prevent="submit" class="form">
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" style="height: 300px;">

                            <!-- First Product -->
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">First Product</label>
                                <select id="first_product_id" name="first_product_id"
                                    data-placeholder="Select first product" data-value="{{ $first_product_id ?? '' }}"
                                    class="form-select form-select-solid @error('first_product_id') is-invalid @enderror"
                                    wire:model.defer="first_product_id">
                                    <option value="">Select first product</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('first_product_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Second Product -->
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Second Product</label>
                                <select id="second_product_id" name="second_product_id"
                                    data-placeholder="Select second product" data-value="{{ $second_product_id ?? '' }}"
                                    class="form-select form-select-solid @error('second_product_id') is-invalid @enderror"
                                    wire:model.defer="second_product_id">
                                    <option value="">Select second product</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('second_product_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center pt-3">
                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"
                                    aria-label="Close" wire:loading.attr="disabled">Cancel</button>
                                <button type="submit" class="btn btn-primary" data-kt-brand-modal-action="submit"
                                    style="width: 200px !important;">
                                    <span class="indicator-label" wire:loading.remove wire:target="submit">Save</span>
                                    <span class="indicator-progress" wire:loading wire:target="submit">
                                        Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        document.querySelectorAll('[data-kt-action="delete_row"]').forEach(function (element) {
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
                        Livewire.emit('delete', this.getAttribute('data-kt-id'));
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('livewire:load', function () {
            const $modal = $('#kt_modal_add_compare');

            function initSelect(selector, propName) {
                const $el = $(selector);
                if (!$el.length) return;

                // Destroy previous Select2 if exists
                if ($el.hasClass('select2-hidden-accessible')) {
                    try { $el.select2('destroy'); } catch (e) { /* ignore */ }
                }

                $el.select2({
                    placeholder: $el.data('placeholder') || 'Select product',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $modal
                });

                // Remove previous handler then attach (namespaced)
                $el.off('change.select2Livewire').on('change.select2Livewire', function () {
                    const val = $(this).val() || null;
                    // find nearest Livewire component id
                    const lwId = $(this).closest('[wire\\:id]').attr('wire:id');
                    if (lwId && Livewire.find(lwId)) {
                        Livewire.find(lwId).set(propName, val);
                    } else {
                        // fallback emit (if no lwId found)
                        Livewire.emit('set_' + propName, val);
                    }
                });

                // Initialize Select2 UI value from data-value attribute or actual value
                const initial = $el.attr('data-value') ?? $el.val() ?? null;
                $el.val(initial || null).trigger('change.select2');
            }

            function initAllSelects() {
                initSelect('#first_product_id', 'first_product_id');
                initSelect('#second_product_id', 'second_product_id');
            }

            // Re-init when modal shown
            $modal.on('shown.bs.modal', function () {
                initAllSelects();
            });

            // If modal is opened via show.bs.modal (to emit livewire events) keep that too
            $modal.on('show.bs.modal', function (e) {
                const trigger = e.relatedTarget;
                if (trigger) {
                    const categoryId = trigger.getAttribute('data-category-id');
                    const subcategoryId = trigger.getAttribute('data-subcategory-id');
                    // optional: forward these to Livewire if needed
                    if (categoryId) Livewire.emit('modal.show.categoryId', categoryId);
                    if (subcategoryId) Livewire.emit('modal.show.subcategory', subcategoryId);
                    Livewire.emit('open_add_modal');
                }
            });

            // Re-init after each Livewire update (keeps Select2 alive)
            Livewire.hook('message.processed', (message, component) => {
                if (document.getElementById('kt_modal_add_compare')) {
                    initAllSelects();
                }
            });

            // Listen for server -> client events to refresh selected values
            Livewire.on('refresh_select_values', (payload) => {
                if (!payload) return;
                if (payload.first !== undefined) {
                    const $f = $('#first_product_id');
                    $f.attr('data-value', payload.first);
                    if ($f.hasClass('select2-hidden-accessible')) {
                        $f.val(payload.first || null).trigger('change.select2');
                    }
                }
                if (payload.second !== undefined) {
                    const $s = $('#second_product_id');
                    $s.attr('data-value', payload.second);
                    if ($s.hasClass('select2-hidden-accessible')) {
                        $s.val(payload.second || null).trigger('change.select2');
                    }
                }
            });

            // Optional: close modal when server asks
            Livewire.on('close_add_modal', () => {
                const inst = bootstrap.Modal.getOrCreateInstance(document.getElementById('kt_modal_add_compare'));
                inst.hide();
            });

            // initialize now if modal already open
            initAllSelects();
        });
    </script>
@endpush