<div class="form-check form-check-custom form-check-solid mb-2" style="justify-content:center;">
    <input class="form-check-input" type="checkbox"
        name="is_featured"
        value="1"
        @if($category->featured == 1) checked @endif
        data-id="{{ $category->id }}"
        onclick="updateFeaturedStatus(this)">
</div>

<script>
    function updateFeaturedStatus(element) {
        let productId = element.getAttribute("data-id");
        let isChecked = element.checked ? 1 : 0;

        Livewire.emit('update_featured', productId, isChecked);
    }
</script>