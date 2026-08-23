<?php

namespace App\Http\Livewire\Permission;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleList extends Component
{
    public array|Collection $roles;

    protected $listeners = ['success' => 'updateRoleList', 'delete' => 'delete', 'remove_from_role' => 'remove_from_role'];

    public function render()
    {
        $this->roles = Role::with('permissions')->get();

        return view('livewire.permission.role-list');
    }

    public function updateRoleList()
    {
        $this->roles = Role::with('permissions')->get();
    }

    public function confirmDelete($role_name)
    {
        $this->emit('triggerDeleteConfirmation', $role_name);
    }

    public function remove_from_role($adminId, $roleName)
    {
        // Fetch the user and role using the provided IDs
        $user = User::findOrFail($adminId);
        $role = Role::where('name', $roleName)->first();

        if ($user && $role) {
            // Remove the role from the user
            $user->removeRole($roleName);
            $this->emit('info', 'The admin removed from this role successfully.');
        } else {
            $this->emit('error', 'Role or admin not found.');
        }
    }

    

    public function delete($role_name = ''){
        if(!empty($role_name) && $role_name !== 'Super Admin'){
            $role = Role::where('name', $role_name)->first();
            $role->delete();
            $this->emit('info', 'The role is deleted');
            $this->updateRoleList();
        }else{
            $this->emit('warning', 'You cannot delete super admin.');
        }
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
