<a href="{{ route('product-management.show', $data->product->id) }}" class="btn btn-icon btn-active-light-primary w-30px h-30px" style="margin-right: -10px !important;
" target="_blank">
    <i class="ki-duotone ki-eye fs-3">
        <span class="path1"></span>
        <span class="path2"></span>
        <span class="path3"></span>
    </i>
</a>
<button class="btn btn-icon btn-active-light-primary w-30px h-30px" data-kt-action="delete_row" data-kt-review-id="{{ $data->id }}">
    <i class="ki-duotone ki-trash fs-3">
        <span class="path1"></span>
        <span class="path2"></span>
        <span class="path3"></span>
        <span class="path4"></span>
        <span class="path5"></span>
    </i>
</button>