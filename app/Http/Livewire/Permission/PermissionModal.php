<?php

namespace App\Http\Livewire\Permission;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionModal extends Component
{
    public $name;
    public $edit_mode = false;
    public Permission $permission;

    // Listeners for component events
    protected $listeners = [
        'update_permission' => 'mountPermission',
        'delete_permission' => 'delete',
        'open_add_modal'    => 'openAddModal',
    ];

    public function render()
    {
        return view('livewire.permission.permission-modal');
    }

    public function mountPermission($permission_name = '')
    {   
        
        // If no permission name is passed, we are creating a new permission
        if (empty($permission_name)) {
            $this->permission = new Permission(); // Create new permission instance
            $this->name = ''; // Empty the name input field
            return;
        }

        // Fetch the permission by its name
        $permission = Permission::where('name', $permission_name)->first();

        if (is_null($permission)) {
            $this->emit('error', 'The selected permission [' . $permission_name . '] is not found');
            return;
        }

        $this->permission = $permission;
        $this->name = $this->permission->name;
        $this->edit_mode = true;
    }

    public function submit()
    {
        if($this->edit_mode){
            $this->validate([
                'name' => 'required|unique:permissions,name,' . $this->permission->id,
            ]);

            $this->permission->name = strtolower(($this->name));

            if ($this->permission->exists) {
                if ($this->permission->isDirty()) {
                    $this->permission->save(); 
                    $this->emit('success', 'Permission updated successfully!');
                }
            }
        }else {
            $this->validate([
                'name' => 'required|unique:permissions,name',
            ]);

            Permission::create([
                'name' => strtolower(($this->name)),
            ]);
            $this->emit('success', 'Permission created successfully!');
        }
        
        $this->resetForm();
        
    }

    public function delete($name)
    {
        // Find permission by name
        $permission = Permission::where('name', $name)->first();

        // If permission exists, delete it
        if (!is_null($permission)) {
            $permission->delete();
            $this->emit('info', 'Permission deleted');
        } else {
            $this->emit('error', 'Permission not found');
        }
    }

     // Reset form fields
     private function resetForm()
     {
         // Reset edit mode and form fields
         $this->edit_mode = false;
         $this->reset(['name']);
     }

    // Method to open the add modal and reset the form
    public function openAddModal()
    {
        $this->resetForm();
    }

    public function hydrate()
    {
        // Reset error and validation bags
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
