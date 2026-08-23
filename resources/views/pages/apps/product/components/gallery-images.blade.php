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
                    <div class="dz-message needsclick" id="gallery_dz_message">
                        {{-- Placeholder shown when no images are selected --}}
                        <i class="ki-duotone ki-file-up fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                        <div class="ms-4">
                            <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                            <span class="fs-7 fw-semibold text-gray-500">Upload up to 10 files</span>
                        </div>

                        {{-- Image previews injected here dynamically --}}
                        <div class="image-preview-container" id="gallery_preview_container" style="display:none; width:100%;"></div>
                    </div>
                </div>
                <div class="text-muted fs-7 mt-4">Only *.png, *.jpg, *.jpeg and *.webp image files are accepted. Max 1.5MB per file, up to 10 files.</div>
                <input type="file" name="gallery_image_js[]" multiple accept="image/png,image/jpg,image/jpeg,image/webp" id="gallery_image" hidden>
            </div>

            {{-- Alert message --}}
            <div id="gallery_alert" class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row w-100 p-5 mt-5 mb-0" style="display:none !important;">
                <i class="ki-duotone ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <h4 class="fw-semibold" id="gallery_alert_message"></h4>
                    <span>Only *.png, *.jpg, *.jpeg and *.webp files are accepted. Max 1.5MB per file, up to 10 files.</span>
                </div>
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" id="gallery_alert_close">
                    <i class="ki-duotone ki-cross fs-1 text-danger"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    // ── Config ─────────────────────────────────────────────────────────────────
    var MAX_FILES     = 10;
    var MAX_SIZE_MB   = 1.5;
    var MAX_SIZE_BYTES = MAX_SIZE_MB * 1024 * 1024; // 1,572,864 bytes
    var ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    // ── State ──────────────────────────────────────────────────────────────────
    var galleryFiles = [];

    // ── DOM refs ───────────────────────────────────────────────────────────────
    var $dropzone  = $('#kt_dropzonejs_example_1');
    var $dzMessage = $('#gallery_dz_message');
    var $container = $('#gallery_preview_container');
    var $fileInput = $('#gallery_image');
    var $alert     = $('#gallery_alert');
    var $alertMsg  = $('#gallery_alert_message');

    // ── Helpers ────────────────────────────────────────────────────────────────
    function formatSize(bytes) {
        if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
        if (bytes >= 1048576)    return (bytes / 1048576).toFixed(2) + ' MB';
        if (bytes >= 1024)       return (bytes / 1024).toFixed(2) + ' KB';
        if (bytes > 1)           return bytes + ' bytes';
        if (bytes === 1)         return '1 byte';
        return '0 bytes';
    }

    function showAlert(msg) {
        $alertMsg.text(msg);
        $alert.css('display', 'flex');
    }

    function hideAlert() {
        $alert.hide();
        $alertMsg.text('');
    }

    // ── Validate a list of new File objects, return {valid, errors} ────────────
    function validateFiles(newFiles) {
        var errors  = [];
        var valid   = [];

        newFiles.forEach(function (f) {
            if (!ALLOWED_TYPES.includes(f.type)) {
                errors.push('"' + f.name + '" is not an allowed file type.');
                return;
            }
            if (f.size > MAX_SIZE_BYTES) {
                errors.push('"' + f.name + '" exceeds the 1.5MB size limit (' + formatSize(f.size) + ').');
                return;
            }
            valid.push(f);
        });

        return { valid: valid, errors: errors };
    }

    // ── Render preview thumbnails ──────────────────────────────────────────────
    function renderPreviews() {
        $container.empty();

        if (galleryFiles.length === 0) {
            $container.hide();
            $dzMessage.find('> i, > div.ms-4').show();
            return;
        }

        $dzMessage.find('> i, > div.ms-4').hide();
        $container.show();

        galleryFiles.forEach(function (file, index) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var $preview = $(
                    '<div class="dz-preview dz-processing dz-image-preview dz-error dz-complete image-preview">' +
                        '<div class="dz-image">' +
                            '<img data-dz-thumbnail alt="' + $('<div>').text(file.name).html() + '" src="' + e.target.result + '">' +
                        '</div>' +
                        '<div class="dz-details">' +
                            '<div class="dz-size"><span data-dz-size><strong>' + formatSize(file.size) + '</strong></span></div>' +
                            '<div class="dz-filename"><span data-dz-name>' + $('<div>').text(file.name).html() + '</span></div>' +
                        '</div>' +
                        '<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress style="width:100%;"></span></div>' +
                        '<div class="dz-success-mark"></div>' +
                        '<div><span class="dz-remove" data-index="' + index + '"></span></div>' +
                    '</div>'
                );
                $container.append($preview);
            };
            reader.readAsDataURL(file);
        });
    }

    // ── Core: process incoming files ───────────────────────────────────────────
    function handleNewFiles(newFiles) {
        hideAlert();

        var result = validateFiles(newFiles);
        var allErrors = result.errors.slice();

        // Dedupe against already-added files
        result.valid.forEach(function (f) {
            var exists = galleryFiles.some(function (g) { return g.name === f.name && g.size === f.size; });
            if (!exists) galleryFiles.push(f);
        });

        // Enforce max file count
        if (galleryFiles.length > MAX_FILES) {
            var removed = galleryFiles.length - MAX_FILES;
            galleryFiles = galleryFiles.slice(0, MAX_FILES);
            allErrors.push('Only ' + MAX_FILES + ' files are allowed. ' + removed + ' extra file(s) were ignored.');
        }

        if (allErrors.length) {
            showAlert(allErrors.join(' '));
        }

        renderPreviews();
        window.galleryFilesStore = galleryFiles;
    }

    // ── Open file picker on dropzone click ────────────────────────────────────
    $dropzone.on('click', function (e) {
        if (!$(e.target).closest('.image-preview').length && !$(e.target).is('button')) {
            $fileInput.click();
        }
    });

    // ── Remove single image ────────────────────────────────────────────────────
    $container.on('click', '.dz-remove', function () {
        var idx = parseInt($(this).data('index'), 10);
        galleryFiles.splice(idx, 1);
        window.galleryFilesStore = galleryFiles;
        renderPreviews();
    });

    // ── Alert close ───────────────────────────────────────────────────────────
    $('#gallery_alert_close').on('click', function () {
        hideAlert();
    });

    // ── File input change ─────────────────────────────────────────────────────
    $fileInput.on('change', function (e) {
        handleNewFiles(Array.from(e.target.files));
        $fileInput.val(''); // reset so same file can be re-selected after removal
    });

    // ── Drag & Drop ───────────────────────────────────────────────────────────
    $dropzone.on('dragover dragenter', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $dropzone.addClass('dz-drag-hover');
    }).on('dragleave drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $dropzone.removeClass('dz-drag-hover');
    }).on('drop', function (e) {
        var dt = e.originalEvent.dataTransfer;
        if (dt && dt.files.length) {
            handleNewFiles(Array.from(dt.files));
        }
    });

    // ── Reset all (called after successful product save) ──────────────────────
    window.resetGalleryImages = function () {
        galleryFiles = [];
        window.galleryFilesStore = [];
        $container.empty().hide();
        $dzMessage.find('> i, > div.ms-4').show();
        $fileInput.val('');
        hideAlert();
    };

    // Init global store
    window.galleryFilesStore = galleryFiles;

})();
</script>
@endpush