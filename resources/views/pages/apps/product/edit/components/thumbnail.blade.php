<div class="card card-flush py-4 pb-0">
    <div class="card-header">
        <div class="card-title">
            <h2>Thumbnail</h2>
        </div>
    </div>
    <div class="card-body text-center pt-0">

        @if(!is_null($product->thumb_image))
            @php
                $thumb_image = asset($product->thumb_image);
            @endphp
        @else
            @php
                $thumb_image = asset('assets/media/svg/files/blank-image.svg');
            @endphp
        @endif

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
            @if(!is_null($product->thumb_image))
                {{-- <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" id="remove_thumb" title="Remove thumbnail" >
                    <i class="ki-duotone ki-cross fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <input type="hidden" name="hasThumbRemove" id="hasThumbRemove"> --}}
            @endif
        </div>
        <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted</div>
        <span id="thumb_image" class="text-danger" style="padding-bottom: 0 !important"></span>

    </div>
</div>

<!-- update back thumb -->
<div class="card card-flush py-4 pb-0">
    <div class="card-header">
        <div class="card-title">
            <h2>Back Thumbnail</h2>
        </div>
        </div>
        <div class="card-body text-center pt-0">
            @if(!is_null($product->back_image))
                @php
                    $back_image = asset($product->back_image);
                @endphp
            @else
                @php
                    $back_image = asset('assets/media/svg/files/blank-image.svg');
                @endphp
            @endif

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
                @if(!is_null($product->back_image))
                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" id="back_thumb" title="Remove thumbnail" >
                        <i class="ki-duotone ki-cross fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <input type="hidden" name="hasBackRemove" id="hasBackRemove">
                @endif
            </div>
            <div class="text-muted fs-7">Set the product thumbnail back image. Only *.png, *.jpg and *.jpeg image files are accepted</div>
            <span id="back_image" class="text-danger" style="padding-bottom: 0 !important"></span>
    </div>
</div>


<script>
     document.addEventListener('DOMContentLoaded', function() {
        // @if(!is_null($product->thumb_image))
        //     document.getElementById('remove_thumb').addEventListener('click', function() {
        //         var isDarkTheme = document.querySelector('[data-bs-theme="dark"]');
        //         var placeholderImage = isDarkTheme ? "{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" : "{{ asset('assets/media/svg/files/blank-image.svg') }}";
        //         document.querySelector('.image-input-placeholder-thumb').style.backgroundImage = 'url("' + placeholderImage + '")';
        //         document.getElementById('hasThumbRemove').value = 1;
        //         this.remove();
        //     });

        //     document.getElementById('cancel_thumb').addEventListener('click', function() {
        //         var removeThumbBtn = document.getElementById('remove_thumb');
        //         if (removeThumbBtn) {
        //             removeThumbBtn.style.display = 'flex';
        //         }
        //     });

        //     document.querySelector('input[name="thumb_image"]').addEventListener('change', function(){
        //         var removeThumbBtn = document.getElementById('remove_thumb');
        //         if (removeThumbBtn) {
        //             removeThumbBtn.style.display = 'none';
        //         }
        //         document.getElementById('hasThumbRemove').value = null;
        //     });
        // @endif

        @if(!is_null($product->back_image))
            document.getElementById('back_thumb').addEventListener('click', function() {
                var isDarkThemeForBack = document.querySelector('[data-bs-theme="dark"]');
                var placeholderImageForBack = isDarkThemeForBack ? "{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" : "{{ asset('assets/media/svg/files/blank-image.svg') }}";
                document.querySelector('.image-input-placeholder').style.backgroundImage = 'url("' + placeholderImageForBack + '")';
                document.getElementById('hasBackRemove').value = 1;
                this.remove();
            });

            document.getElementById('cancel_back').addEventListener('click', function() {
                var removeBackBtn = document.getElementById('back_thumb');
                if (removeBackBtn) {
                    removeBackBtn.style.display = 'flex';
                }
            });

            document.querySelector('input[name="back_image"]').addEventListener('change', function(){
                var removeBackBtn = document.getElementById('back_thumb');
                if (removeBackBtn) {
                    removeBackBtn.style.display = 'none';
                }
                document.getElementById('hasBackRemove').value = null;
            });
        @endif
    });
</script>
