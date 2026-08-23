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
                Livewire.emit('delete', this.getAttribute('data-kt-id'));
            }
        });
    });
});


// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.emit('update_brand', this.getAttribute('data-kt-brand-id'));
    });
});

//change status
document.querySelectorAll('.change-status').forEach(function (element){
    element.addEventListener('click', function(){
        let status = this.checked ? 1 : 0;
        Livewire.emit('update_status', this.getAttribute('data-brand-id'), status);
    })
})


// Listen for 'success' event emitted by Livewire
Livewire.on('info', (message) => {
    // Reload the users-table datatable
    LaravelDataTables['brand-table'].ajax.reload();
});
