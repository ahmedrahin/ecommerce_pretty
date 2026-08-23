<?php

namespace App\Http\Controllers\Apps\User;

use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productCatalouge = Permission::where('group_name', 'product-catalouge')->get();
        $adminCatalouge = Permission::where('group_name', 'admin-catalouge')->get();
        $OrderCatalouge = Permission::where('group_name', 'order-catalouge')->get();
        return view('pages.apps.user-management.permissions.list', [
            'productCatalouge' => $productCatalouge,
            'adminCatalouge' => $adminCatalouge,
            'OrderCatalouge' => $OrderCatalouge,
        ]);
    }
}
