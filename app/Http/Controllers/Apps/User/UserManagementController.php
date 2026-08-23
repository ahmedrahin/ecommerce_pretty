<?php

namespace App\Http\Controllers\Apps\User;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.user-management.user.list');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if(!$user){
            return redirect()->back()->with('error', 'The user is not found');
        }

        return view('pages.apps.user-management.user.show', compact('user'));
    }


}
