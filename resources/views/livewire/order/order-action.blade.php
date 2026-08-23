<div class="card-body pt-0">
    <label class="fw-semibold fs-6 mb-2 mt-5">Delivery Status</label>
    <select id="status" class="form-select form-select-solid">
        <option value="pending">Pending</option>
        <option value="processing">Processing</option>
        <option value="delivered">Delivered</option>
        <option value="canceled">Canceled</option>
        <option value="completed">Completed</option>
        <option value="fake">Fake Order</option>
    </select>

    @if (!is_null($order->note))
        <label class="fw-semibold fs-6 mb-2 mt-5">Note:</label>
        <p>{{ $order->note }}</p>
    @endif
</div>

<script>
    document.addEventListener('livewire:load', function() {
        initializeSelect2();

        Livewire.hook('message.processed', () => {
            // Sync the dropdown value with Livewire
            $('#status').val(@this.get('delivery_status')).trigger('change.select2');
        });
    });

    function initializeSelect2() {
        if ($('#status').hasClass('select2-hidden-accessible')) return;

        $('#status').select2({
            placeholder: "Select a status",
            minimumResultsForSearch: -1
        }).on('change', function() {
            // Update Livewire manually
            @this.set('delivery_status', $(this).val());
        });

        // Set initial value from Livewire
        $('#status').val(@this.get('delivery_status')).trigger('change.select2');
    }
</script>
