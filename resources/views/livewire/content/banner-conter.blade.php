<div class="card product-datatable">

    <div class="card-body py-4 pt-10">
        <div class="row">
            @foreach ($sliders as $index => $value)
                <div class="col-md-4 mb-8">
                    <div class="card card-flush h-xl-100 mb-4">
                        <!--begin::Header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Banner {{ $index + 1 }}</span>
                            </h3>

                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_add{{ $value->id }}"  wire:click="openModal({{ $value->id }})">
                                    {!! getIcon('pencil', 'fs-2', '', 'i') !!}
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-7">
                            <!--begin::Row-->
                            <div class="row align-items-end h-100 gx-5 gx-xl-10">
                                <div class="col-md-12 mb-11 mb-md-0">
                                    <a class="d-block overlay" data-fslightbox="lightbox-hot-sales">
                                        <div class="overlay-wrapper bgi-position-center bgi-no-repeat bgi-size-cover h-200px card-rounded mb-3"
                                            style="height: 266px;background-image:url({{ asset($value->image) }})">
                                        </div>
                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                            <i class="ki-duotone ki-eye fs-3x text-white">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                    </a>
                                    <div class="mt-5 text-center">
                                        <h5>{!! $value->content->title ?? '<span class="text-muted">No title</span>' !!}</h5>
                                        <p>{!! $value->content->description ?? '<span class="text-muted">No description</span>' !!}</p>
                                        <a href="{{ $value->content->link ?? '#' }}" class="text-underline" ta>Link</a>
                                        
                                    </div>
                                </div>
                            </div>
                            <!--end::Row-->
                        </div>

                    </div>
                </div>

                <div class="modal fade" id="kt_modal_add{{ $value->id }}" tabindex="-1" aria-hidden="true" wire:ignore.self>
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="fw-bold">Update Banner Content</h2>
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    {!! getIcon('cross', 'fs-1') !!}
                                </div>
                            </div>

                            <div class="modal-body px-5 my-3">
                                <form wire:submit.prevent="submit" class="form">
                                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" style="height: 300px;">

                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Title</label>
                                            <input type="text" wire:model.defer="title" name="Title"
                                                class="form-control form-control-solid mb-3 mb-lg-0 "
                                                placeholder="Title" />
                                        </div>

                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Link</label>
                                            <input type="text" wire:model.defer="link" name="link"
                                                class="form-control form-control-solid mb-3 mb-lg-0 "
                                                placeholder="Link" />
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

                                        <div class="text-center pt-3">
                                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"
                                                aria-label="Close" wire:loading.attr="disabled">Cancel</button>
                                            <button type="submit" class="btn btn-primary"
                                                data-kt-brand-modal-action="submit" style="width: 200px !important;">
                                                <span class="indicator-label" wire:loading.remove
                                                    wire:target="submit">Save</span>
                                                <span class="indicator-progress" wire:loading wire:target="submit">
                                                    Please wait...
                                                    <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


</div>

@push('scripts')
@endpush
