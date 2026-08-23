// Initialize KTMenu
KTMenu.init();

// Add click event listener to delete buttons
document.querySelectorAll('[data-kt-action="delete_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Are you sure you want to remove?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('delete_order', this.getAttribute('data-kt-order-id'));
            }
        });
    });
});

document.querySelectorAll('[data-kt-action="delete_permanent"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Are you sure you want to permanent delete this order?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('delete_permanent', this.getAttribute('data-kt-order-id'));
            }
        });
    });
});

document.querySelectorAll('[data-kt-action="restore"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.emit('restore', this.getAttribute('data-kt-order-id'));
    });
});



// Listen for 'success' event emitted by Livewire
Livewire.on('info', (message) => {
    LaravelDataTables['order-table'].ajax.reload();
});

Livewire.on('info', (message) => {
    LaravelDataTables['trashorder-table'].ajax.reload();
});

Livewire.on('success', (message) => {
    LaravelDataTables['trashorder-table'].ajax.reload();
});
