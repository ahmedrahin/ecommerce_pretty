<div class="card card-flush py-4">
    <div class="card-header">
        <div class="card-title">
            <h2>Gallery Image</h2>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="fv-row mb-2">

            <div class="fv-row">
                <div class="dropzone" id="kt_dropzonejs_example_1">
                    <div class="dz-message needsclick">
                        @if (!empty($imagePreviews))
                            <div class="image-preview-container">
                                @foreach ($imagePreviews as $index => $preview)
                                    <div class="dz-preview dz-processing dz-image-preview dz-error dz-complete image-preview">
                                        <div class="dz-image">
                                            <img data-dz-thumbnail="" alt="{{ $imageNames[$index] }}" src="{{ $preview }}">
                                        </div>
                                        <div class="dz-details">
                                            <div class="dz-size">
                                                <span data-dz-size="">
                                                    <strong>{{ $imageSizes[$index] }}</strong>
                                                </span>
                                            </div>
                                            <div class="dz-filename">
                                                <span data-dz-name="">{{ $imageNames[$index] }}</span>
                                            </div>
                                        </div>
                                        <div class="dz-progress">
                                            <span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span>
                                        </div>
                                        <div class="dz-success-mark"></div>
                                        <span class="dz-remove" data-dz-remove="" wire:click="removeImage({{ $index }})">Remove file</span>
                                    </div>
                                @endforeach
                            </div>
                        @else 
                            <i class="ki-duotone ki-file-up fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                            <div class="ms-4">
                                <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                <span class="fs-7 fw-semibold text-gray-500">Upload up to 10 files</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="text-muted fs-7 mt-4">Only *.png, *.jpg and *.jpeg image files are accepted.</div>
                <input type="file" wire:model="images" name="gallery_image[]" multiple accept="image/*"  id="gallery_image" hidden>
            </div>

            @if ($alertMessage)
            <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row w-100 p-5 mt-5 mb-0">
                <i class="ki-duotone ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>                   

                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <h4 class="fw-semibold">{{$alertMessage}}</h4>
                    <span>Please attention You can only *.png, *.jpg and *.jpeg image files are accepted.</span>
                </div>

                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                    <i class="ki-duotone ki-cross fs-1 text-danger"><span class="path1"></span><span class="path2"></span></i>                    
                </button>

            </div>
            @endif

            <button type="button" wire:click="removeImagesAll" class="btn btn-primary mt-3" id="saveGellaryImage" style="display: none"></button>
        </div>
    </div>
</div>

@push('scripts')

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('reset-file-input', function () {
            document.getElementById('gallery_image').value = null;
        });

        $('#kt_dropzonejs_example_1').on('click', function(e) {
            if (!$(e.target).closest('.image-preview').length && !$(e.target).is('button')) {
                $('#gallery_image').click();
            }
        });
    });
</script>

@endpush
