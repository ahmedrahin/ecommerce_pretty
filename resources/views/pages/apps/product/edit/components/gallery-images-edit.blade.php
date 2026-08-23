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

                        {{-- Existing DB images --}}
                        <div class="image-preview-container"
                             id="gallery_existing_container"
                             style="width:100%; {{ $product->galleryImages->isEmpty() ? 'display:none;' : '' }}">
                            @foreach ($product->galleryImages as $img)
                                <div class="dz-preview dz-processing dz-image-preview dz-complete image-preview"
                                     id="existing_img_{{ $img->id }}"
                                     data-existing-id="{{ $img->id }}">
                                    <div class="dz-image">
                                        <img data-dz-thumbnail
                                             alt="{{ basename($img->image) }}"
                                             src="{{ asset($img->image) }}">
                                    </div>
                                    <div class="dz-details">
                                        <div class="dz-filename">
                                            <span data-dz-name>{{ basename($img->image) }}</span>
                                        </div>
                                    </div>
                                    <div class="dz-progress">
                                        <span class="dz-upload" style="width:100%;"></span>
                                    </div>
                                    <div class="dz-success-mark"></div>
                                    <div>
                                        <span class="dz-remove dz-remove-existing"
                                              data-existing-id="{{ $img->id }}"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- New file previews (JS injected) --}}
                        <div class="image-preview-container"
                             id="gallery_preview_container"
                             style="display:none; width:100%;"></div>

                        {{-- Placeholder --}}
                        <div id="gallery_placeholder"
                             style="{{ $product->galleryImages->isNotEmpty() ? 'display:none;' : '' }}">
                            <i class="ki-duotone ki-file-up fs-3x text-primary">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <div class="ms-4">
                                <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                <span class="fs-7 fw-semibold text-gray-500">Upload up to 10 files</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="text-muted fs-7 mt-4">
                    Only *.png, *.jpg, *.jpeg and *.webp image files are accepted. Max 1.5MB per file, up to 10 files total.
                </div>

                {{-- Hidden inputs: removed existing image IDs --}}
                <div id="gallery_remove_inputs"></div>

                <input type="file"
                       name="gallery_image_js[]"
                       multiple
                       accept="image/png,image/jpg,image/jpeg,image/webp"
                       id="gallery_image"
                       hidden>
            </div>

            {{-- Alert --}}
            <div id="gallery_alert"
                 class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row w-100 p-5 mt-5 mb-0"
                 style="display:none !important;">
                <i class="ki-duotone ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <h4 class="fw-semibold" id="gallery_alert_message"></h4>
                    <span>Only *.png, *.jpg, *.jpeg and *.webp files are accepted. Max 1.5MB per file, up to 10 files total.</span>
                </div>
                <button type="button"
                        class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                        id="gallery_alert_close">
                    <i class="ki-duotone ki-cross fs-1 text-danger">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {

    // ── Config ─────────────────────────────────────────────────────────────────
    var MAX_TOTAL      = 10;
    var MAX_SIZE_BYTES = 1.5 * 1024 * 1024; // 1.5 MB
    var ALLOWED_TYPES  = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    // ── State ──────────────────────────────────────────────────────────────────
    var galleryFiles = []; // new File objects pending upload
    var removedIds   = []; // existing DB image IDs marked for deletion

    // ── DOM refs ───────────────────────────────────────────────────────────────
    var $dropzone       = $('#kt_dropzonejs_example_1');
    var $dzMessage      = $('#gallery_dz_message');
    var $newContainer   = $('#gallery_preview_container');
    var $existContainer = $('#gallery_existing_container');
    var $removeInputs   = $('#gallery_remove_inputs');
    var $placeholder    = $('#gallery_placeholder');
    var $fileInput      = $('#gallery_image');
    var $alert          = $('#gallery_alert');
    var $alertMsg       = $('#gallery_alert_message');

    // ── Helpers ────────────────────────────────────────────────────────────────
    function formatSize(bytes) {
        if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
        if (bytes >= 1048576)    return (bytes / 1048576).toFixed(2) + ' MB';
        if (bytes >= 1024)       return (bytes / 1024).toFixed(2) + ' KB';
        if (bytes > 1)           return bytes + ' bytes';
        return '0 bytes';
    }

    function showAlert(msg) { $alertMsg.text(msg); $alert.css('display', 'flex'); }
    function hideAlert()    { $alert.hide(); $alertMsg.text(''); }

    // Existing images count — pending-remove class যেগুলোতে আছে সেগুলো বাদ
    function activeExistingCount() {
        return $existContainer.find('.dz-preview').not('.pending-remove').length;
    }

    // মোট কতটা নতুন file add করা যাবে (existing active count বাদ দিয়ে)
    function remainingSlots() {
        return Math.max(0, MAX_TOTAL - activeExistingCount());
    }

    // ── Placeholder visibility ────────────────────────────────────────────────
    function syncPlaceholder() {
        var hasExisting = $existContainer.find('.dz-preview').not('.pending-remove').length > 0;
        var hasNew      = galleryFiles.length > 0;
        $placeholder.toggle(!hasExisting && !hasNew);
        if ($existContainer.find('.dz-preview').length > 0) $existContainer.show();
    }

    // ── Sync hidden remove[] inputs into the form ─────────────────────────────
    function syncRemoveInputs() {
        $removeInputs.empty();
        removedIds.forEach(function (id) {
            $removeInputs.append(
                $('<input>').attr({ type: 'hidden', name: 'remove_gallery_ids[]', value: id })
            );
        });
    }

    // ── Validate a list of File objects ───────────────────────────────────────
    function validateFiles(files) {
        var valid = [], errors = [];
        files.forEach(function (f) {
            if (!ALLOWED_TYPES.includes(f.type)) {
                errors.push('"' + f.name + '" is not an allowed file type.');
                return;
            }
            if (f.size > MAX_SIZE_BYTES) {
                errors.push('"' + f.name + '" exceeds 1.5MB (' + formatSize(f.size) + ').');
                return;
            }
            valid.push(f);
        });
        return { valid: valid, errors: errors };
    }

    // ── Render new file previews ──────────────────────────────────────────────
    function renderNewPreviews() {
        $newContainer.empty();

        if (galleryFiles.length === 0) {
            $newContainer.hide();
            syncPlaceholder();
            return;
        }

        $newContainer.show();
        syncPlaceholder();

        galleryFiles.forEach(function (file, index) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var $preview = $(
                    '<div class="dz-preview dz-processing dz-image-preview dz-error dz-complete image-preview">' +
                        '<div class="dz-image">' +
                            '<img data-dz-thumbnail alt="' + $('<div>').text(file.name).html() + '" src="' + e.target.result + '">' +
                        '</div>' +
                        '<div class="dz-details">' +
                            '<div class="dz-size"><span><strong>' + formatSize(file.size) + '</strong></span></div>' +
                            '<div class="dz-filename"><span>' + $('<div>').text(file.name).html() + '</span></div>' +
                        '</div>' +
                        '<div class="dz-progress"><span class="dz-upload" style="width:100%;"></span></div>' +
                        '<div class="dz-success-mark"></div>' +
                        '<div><span class="dz-remove dz-remove-new" data-index="' + index + '"></span></div>' +
                    '</div>'
                );
                $newContainer.append($preview);
            };
            reader.readAsDataURL(file);
        });

        window.galleryFilesStore = galleryFiles;
    }

    // ── Handle incoming new files ─────────────────────────────────────────────
    function handleNewFiles(newFiles) {
        hideAlert();

        var slots     = remainingSlots(); // existing active count বাদ দিয়ে available slots
        var result    = validateFiles(newFiles);
        var allErrors = result.errors.slice();

        // Dedupe against already-queued new files
        result.valid.forEach(function (f) {
            var exists = galleryFiles.some(function (g) { return g.name === f.name && g.size === f.size; });
            if (!exists) galleryFiles.push(f);
        });

        // Existing active + new মিলিয়ে MAX_TOTAL এর বেশি হলে কাটো
        if (galleryFiles.length > slots) {
            var ignored = galleryFiles.length - slots;
            galleryFiles = galleryFiles.slice(0, slots);
            if (ignored > 0) {
                allErrors.push(
                    'Total limit is ' + MAX_TOTAL + '. ' + ignored + ' file(s) ignored ' +
                    '(' + activeExistingCount() + ' existing + ' + galleryFiles.length + ' new).'
                );
            }
        }

        if (allErrors.length) showAlert(allErrors.join(' '));

        renderNewPreviews();
    }

    // ── Remove NEW file (client-side only) ────────────────────────────────────
    $newContainer.on('click', '.dz-remove-new', function () {
        var idx = parseInt($(this).data('index'), 10);
        galleryFiles.splice(idx, 1);
        window.galleryFilesStore = galleryFiles;
        renderNewPreviews();
    });

    // ── Mark EXISTING image for removal (on form submit, not instant AJAX) ────
    $dzMessage.on('click', '.dz-remove-existing', function () {
        var imgId = $(this).data('existing-id');
        var $card = $('#existing_img_' + imgId);

        // Visual: fade + add class
        $card.addClass('pending-remove').css('opacity', '0.35');

        // Toggle button to Undo
        $(this)
            .text('Undo')
            .addClass('dz-remove-undo')
            .removeClass('dz-remove-existing');

        // Track ID
        if (!removedIds.includes(imgId)) removedIds.push(imgId);
        syncRemoveInputs();

        // Removed হলে আবার নতুন slot খালি হয়, তাই new files limit recheck
        syncPlaceholder();
    });

    // ── Undo removal mark ─────────────────────────────────────────────────────
    $dzMessage.on('click', '.dz-remove-undo', function () {
        var imgId = $(this).data('existing-id');
        var $card = $('#existing_img_' + imgId);

        $card.removeClass('pending-remove').css('opacity', '1');

        $(this)
            .text('')
            .addClass('dz-remove-existing')
            .removeClass('dz-remove-undo');

        removedIds = removedIds.filter(function (id) { return id != imgId; });
        syncRemoveInputs();

        // Slot কমে গেল, তাই new files যদি বেশি হয়ে যায় সেটা trim করো
        var slots = remainingSlots();
        if (galleryFiles.length > slots) {
            galleryFiles = galleryFiles.slice(0, slots);
            window.galleryFilesStore = galleryFiles;
            showAlert(
                'Total limit is ' + MAX_TOTAL + '. New files reduced to ' + galleryFiles.length + ' ' +
                'because ' + activeExistingCount() + ' existing image(s) are active.'
            );
        }

        syncPlaceholder();
        renderNewPreviews();
    });

    // ── Open file picker on dropzone click ────────────────────────────────────
    $dropzone.on('click', function (e) {
        if (!$(e.target).closest('.image-preview').length && !$(e.target).is('button')) {
            $fileInput.click();
        }
    });

    // ── Alert close ───────────────────────────────────────────────────────────
    $('#gallery_alert_close').on('click', hideAlert);

    // ── File input change ─────────────────────────────────────────────────────
    $fileInput.on('change', function (e) {
        handleNewFiles(Array.from(e.target.files));
        $fileInput.val(''); // reset so same file re-selectable after removal
    });

    // ── Drag & Drop ───────────────────────────────────────────────────────────
    $dropzone.on('dragover dragenter', function (e) {
        e.preventDefault(); e.stopPropagation();
        $dropzone.addClass('dz-drag-hover');
    }).on('dragleave drop', function (e) {
        e.preventDefault(); e.stopPropagation();
        $dropzone.removeClass('dz-drag-hover');
    }).on('drop', function (e) {
        var dt = e.originalEvent.dataTransfer;
        if (dt && dt.files.length) handleNewFiles(Array.from(dt.files));
    });

    // ── Reset after successful update AJAX ───────────────────────────────────
    // New previews clear হবে, existing গুলো blade থেকে fresh আসবে (page reload বা AJAX response)
    window.resetGalleryImages = function () {
        galleryFiles = [];
        removedIds   = [];
        window.galleryFilesStore = [];
        $newContainer.empty().hide();
        $removeInputs.empty();
        $fileInput.val('');
        hideAlert();
        syncPlaceholder();
    };

    // ── Init ──────────────────────────────────────────────────────────────────
    window.galleryFilesStore = galleryFiles;
    syncPlaceholder();

})();
</script>
@endpush