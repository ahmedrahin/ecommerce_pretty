<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AddAdminModal extends Component
{
    use WithFileUploads;

    public $user_id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $image;
    public $current_image;

    public $edit_mode = false;

    protected $rules = [
        'name'      => 'required|string',
        'email' => ['required', 'email'],
        'role'      => 'nullable|string',
        'image'     => 'nullable|sometimes|image|max:2048',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
        'open_add_modal' => 'openAddModal',
        'update_status'   => 'updateStatus',
    ];

    public function render()
    {
        $roles = Role::all();
        return view('livewire.user.add-admin-modal', compact('roles'));
    }

   public function submit()
    {
        // Clone and customize validation rules
        $rules = $this->rules;

        if ($this->edit_mode) {
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user_id)->whereNull('deleted_at'),
            ];
        } else {
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ];
            $rules['password'] = 'required|min:6';
        }

        // Use the updated rules
        $this->validate($rules);

        DB::transaction(function () use ($rules) {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'created_by' => Auth::id(),
                'isAdmin' => 1,
            ];

            if ($this->image && !$this->edit_mode) {
                $imageName = time() . '_' . $this->image->getClientOriginalName();
                $this->image->storeAs('uploads/admin', $imageName, 'real_public');
                $data['avatar'] = 'uploads/admin/' . $imageName;
            }

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            if ($this->edit_mode) {
                $user = User::find($this->user_id);
                if ($this->image) {
                    if ($user->avatar) {
                        Storage::disk('real_public')->delete($user->avatar);
                    }
                    $user->avatar = $this->image->store('uploads/admin', 'real_public');
                } elseif ($this->current_image === null) {
                    if ($user->avatar) {
                        Storage::disk('real_public')->delete($user->avatar);
                    }
                    $user->avatar = null;
                }
                $user->save();
            }

            // ✅ Fix here — only check active users
            $user = User::where('email', $this->email)->whereNull('deleted_at')->first();

            if ($user) {
                $user->update($data);
            } else {
                $user = User::create($data);
            }

            if ($this->edit_mode) {
                $user->syncRoles($this->role);
                DB::table('model_has_roles')
                    ->where('model_id', $user->id)
                    ->where('model_type', User::class)
                    ->update(['created_at' => now()]);
                $this->emit('success', __('Admin is updated'));
            } else {
                $user->assignRole($this->role);
                DB::table('model_has_roles')
                    ->where('model_id', $user->id)
                    ->where('model_type', User::class)
                    ->update(['created_at' => now()]);
                $this->emit('success', __('New admin created'));
            }
        });

        $this->reset();
    }


   public function deleteUser($id)
    {
        // Prevent deletion of super admin account
        if ($id == 1) {
            $this->emit('warning', 'You cannot delete this account');
            return;
        }

        // Prevent deleting yourself
        if ($id == Auth::id()) {
            $this->emit('error', 'You cannot delete your own account');
            return;
        }

        // Get the user
        $user = User::find($id);

        if ($user) {
            // Delete avatar if exists
            if ($user->avatar) {
                Storage::disk('real_public')->delete($user->avatar);
            }

            // Delete user
            $user->delete();

            // Message based on role
            if ($user->isAdmin == 1) {
                $this->emit('info', 'Admin was deleted');
            } else {
                $this->emit('info', 'User was deleted');
            }
        } else {
            $this->emit('error', 'User not found');
        }
    }


    public function updateUser($id)
    {
        $this->edit_mode = true;
        $user = User::find($id);

        if ($user) {
            $this->user_id = $user->id;
            $this->current_image = $user->avatar;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->roles?->first()->name ?? '';
        }
    }

    //update status
    public function updateStatus($id, $status)
    {
        $user = User::findOrFail($id);
    
        // Prevent disabling the super admin
        if ($user->id == 1) {
            $this->emit('warning', 'You cannot disable this account.');
            return;
        }
    
        // Prevent the currently authenticated user from disabling their own account without logging out
        if (auth()->id() == $user->id && $status == 0) {
            $user->update(['status' => $status]);
    
            // Log the user out
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
    
            // Delete the user's session
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
    
            // Redirect to login
            return redirect()->route('login')->with('info', 'Your account has been disabled and you have been logged out.');
        }
    
        // Disable or enable another user's account
        $user->update(['status' => $status]);
    
        if ($status == 0) {
            // Delete the user's session
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
    
            // Emit a message for admin
            $message = "{$user->name}'s account has been disabled.";
            $this->emit('info', $message);
        } else {
            $message = "{$user->name}'s account has been enabled.";
            $this->emit('success', $message);
        }
    }
    

    public function removeImage()
    {
        $this->image = null;
        if ($this->current_image) {
            $this->current_image = null;
        }
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openAddModal()
    {
        $this->reset();
        $this->edit_mode = false; // Ensure edit mode is reset
    }
}
