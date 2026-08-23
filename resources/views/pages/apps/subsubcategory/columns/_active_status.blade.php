<label class="form-check form-switch form-check-custom form-check-solid status-toggle">
    <input 
        class="form-check-input change-status" 
        type="checkbox"  
        data-subcategory-id="{{ $subsubcategory->id }}"
        {{ $subsubcategory->status == 1 ? 'checked' : '' }}
    >
</label>