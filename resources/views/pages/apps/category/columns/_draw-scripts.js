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
                Livewire.emit('delete_category', this.getAttribute('data-kt-category-id'));
            }
        });
    });
});


// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.emit('update_category', this.getAttribute('data-kt-category-id'));
    });
});

//change status
document.querySelectorAll('.change-status').forEach(function (element){
    element.addEventListener('click', function(){
        let status = this.checked ? 1 : 0;
        Livewire.emit('update_status', this.getAttribute('data-category-id'), status);
    })
})

// delete subcategory
document.querySelectorAll('.delsubCat').forEach(function (element) {
    element.addEventListener('click', function () {
        const subCatId = this.getAttribute('data-kt-sub-id');
        const listItem = this.closest('li');
        
        Livewire.emit('delete_subcategory', subCatId);
        
        Livewire.on('delSucCat', (message) => {
            toastr.info(message);

            // Remove the li with fade out effect
            listItem.style.transition = "opacity 0.5s ease";
            listItem.style.opacity = 0;
            
            setTimeout(() => {
                listItem.remove();
            }, 500); // Match the timeout duration with the transition time
        });
    });
});


// Listen for 'success' event emitted by Livewire
Livewire.on('info', (message) => {
    // Reload the users-table datatable
    LaravelDataTables['category-table'].ajax.reload();
});
