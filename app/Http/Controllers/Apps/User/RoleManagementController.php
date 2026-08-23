<?php

namespace App\Http\Controllers\Apps\User;

use App\DataTables\UsersAssignedRoleDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.apps.user-management.roles.list');
    }


    /**
     * Display the specified resource.
     */
    public function show(Role $role, UsersAssignedRoleDataTable $dataTable)
    {
        return $dataTable->with('role', $role)
            ->render('pages.apps.user-management.roles.show', compact('role'));
    }

}
