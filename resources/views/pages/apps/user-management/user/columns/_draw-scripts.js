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
                Livewire.emit('delete_user', this.getAttribute('data-kt-user-id'));
            }
        });
    });
});


//change status
document.querySelectorAll('.change-status').forEach(function (element) {
    element.addEventListener('click', function() {
        const userId = this.getAttribute('data-user-id').toString();
        const currentUserId = this.getAttribute('data-current-user-id');
        const status = this.checked ? 1 : 0;
        
        if (userId == currentUserId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to change your own status. If you proceed, you will be signed out and won't be able to log in again unless your status is enabled.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, i agree!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('update_status', userId, status);
                } else {
                    this.checked = !this.checked;
                }
            });
        } else {
            Livewire.emit('update_status', userId, status);
        }
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.emit('update_user', this.getAttribute('data-kt-user-id'));
    });
});

// Listen for 'success' event emitted by Livewire
Livewire.on('info', (message) => {
    // Reload the users-table datatable
    LaravelDataTables['users-table'].ajax.reload();
});
