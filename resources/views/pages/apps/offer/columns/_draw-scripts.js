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


//change status
document.querySelectorAll('.change-status').forEach(function (element){
    element.addEventListener('click', function(){
        let status = this.checked ? 1 : 0;
        Livewire.emit('update_status', this.getAttribute('data-id'), status);
    })
})


// Listen for 'success' event emitted by Livewire
Livewire.on('info', (message) => {
    LaravelDataTables['offer-table'].ajax.reload();
});
Livewire.on('success', (message) => {
    LaravelDataTables['offer-table'].ajax.reload();
});
