<div class="card card-flush py-4 pb-0">
    <div class="card-header">  
        <div class="card-title">
            <h2>Thumbnail</h2>
        </div>
    </div> 
    <div class="card-body text-center pt-0">
        @php
            $thumb_image = asset('assets/media/svg/files/blank-image.svg');
        @endphp
        
        <style>
            .image-input-placeholder-thumb {
                background-image: url('{{ $thumb_image }}');
            }
            [data-bs-theme="dark"] .image-input-placeholder-thumb {
                background-image: url('{{ $thumb_image }}');
            }
        </style>
    
        <div class="image-input image-input-empty image-input-outline image-input-placeholder-thumb mb-3" data-kt-image-input="true">
            <div class="image-input-wrapper w-150px h-150px"></div>
            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Upload thumbnails">
                <i class="ki-duotone ki-pencil fs-7">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="file" name="thumb_image" accept=".png, .jpg, .jpeg" />
                <input type="hidden" name="avatar_remove" />
            </label>
            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" id="cancel_thumb" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel thumbnail">
                <i class="ki-duotone ki-cross fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span>
        
        </div>

        <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted</div>
        <span id="thumb_image" class="text-danger" style="padding-bottom: 0 !important"></span>
    </div>
</div>

<div class="card card-flush py-4 pb-0">
    <div class="card-header">
        <div class="card-title">
            <h2>Back Thumbnail</h2>
        </div>
    </div>
        <div class="card-body text-center pt-0">
           
            @php
                $back_image = asset('assets/media/svg/files/blank-image.svg');
            @endphp

            <style>
                .image-input-placeholder {
                    background-image: url('{{ $back_image }}');
                }
                [data-bs-theme="dark"] .image-input-placeholder {
                    background-image: url('{{ $back_image }}');
                }
            </style>

            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                <div class="image-input-wrapper w-150px h-150px"></div>
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Upload thumbnails">
                    <i class="ki-duotone ki-pencil fs-7">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="file" name="back_image" accept=".png, .jpg, .jpeg" />
                    <input type="hidden" name="avatar_remove" />
                </label>
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" id="cancel_back" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel thumbnail">
                    <i class="ki-duotone ki-cross fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
            </div>
            <div class="text-muted fs-7">Set the product back image. Only *.png, *.jpg and *.jpeg image files are accepted</div>
            <span id="back_image" class="text-danger" style="padding-bottom: 0 !important"></span>
        </div>
</div>