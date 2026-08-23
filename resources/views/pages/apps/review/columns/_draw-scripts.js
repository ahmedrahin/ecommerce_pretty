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
                Livewire.emit('delete_review', this.getAttribute('data-kt-review-id'));
            }
        });
    });
});


// Listen for 'success' event emitted by Livewire
Livewire.on('info', (message) => {
    // Reload the users-table datatable
    LaravelDataTables['review-table'].ajax.reload();
});
