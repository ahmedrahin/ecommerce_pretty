<?php

namespace App\Http\Livewire\Permission;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleModal extends Component
{
    public $name;
    public $checked_permissions = [];
    public $check_all;
    
    public Role $role;
    public Collection $permissions;
    public $edit_mode = false;

    
    // This is the list of listeners that this component listens to.
    protected $listeners = ['update' => 'mountRole', 'open_add_modal' => 'openAddModal', ];

    // This function is called when the component receives the `modal.show.role_name` event.
    public function mountRole($role_name = '')
    {
        $this->edit_mode = true;

        // Get the role by name.
        $role = Role::where('name', $role_name)->first();
        $this->role = $role;

        if (!$role) {
            // Handle case where role doesn't exist
            $this->emit('warning', 'Role not found.');
            return;
        }

        // Set the name and checked permissions properties to the role's values.
        $this->name = $this->role->name;
        $this->checked_permissions = $this->role->permissions->pluck('name');
    }

    // This function is called when the component is mounted.
    public function mount()
    {
        // Get all permissions.
        $this->permissions = Permission::all();

        // Set the checked permissions property to an empty array.
        $this->checked_permissions = [];
    }

    // This function renders the component's view.
    public function render()
    {
        // Create an array of permissions grouped by ability.
        $permissions_by_group = [];
        foreach ($this->permissions ?? [] as $permission) {
            $ability = Str::after($permission->name, ' ');

            $permissions_by_group[$ability][] = $permission;
        }

        // Return the view with the permissions_by_group variable passed in.
        return view('livewire.permission.role-modal', compact('permissions_by_group'));
    }

    // This function submits the form and updates the role's permissions.
    public function submit()
    {   
        // dd($this->checked_permissions);

        if ($this->edit_mode) {
            $rules['name'] = 'required|string|unique:roles,name,' . $this->role->id;
        } else {
            // For creating a new record, ensure the name is unique
            $rules['name'] = 'required|string|unique:roles,name';
        }
    
        // Validate the data
        $this->validate($rules);

        if ($this->edit_mode){
            $this->role->name = $this->name;
            if ($this->role->isDirty()) {
                $this->role->save();
            }
            $this->emit('success', 'Permissions for ' . ucwords($this->role->name) . ' role updated');
        } else {
            $this->role = Role::create(['name' => $this->name]);
            $this->emit('success', 'New role created');
        }

        // Sync the role's permissions with the checked permissions property.
        $this->role->syncPermissions($this->checked_permissions);
        $this->resetForm();
    }

    // This function checks all of the permissions.
    public function checkAll()
    {
        // If the check_all property is true, set the checked permissions property to all of the permissions.
        if ($this->check_all) {
            $this->checked_permissions = $this->permissions->pluck('name');
        } else {
            // Otherwise, set the checked permissions property to an empty array.
            $this->checked_permissions = [];
        }
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Method to open the add modal and reset the form
    public function openAddModal()
    {
        $this->resetForm();
    }

    // Reset form fields
    private function resetForm()
    {
        // Reset edit mode and form fields
        $this->edit_mode = false;
        $this->reset(['name', 'checked_permissions', 'check_all']);
    }
}
