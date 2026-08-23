<?php

namespace App\Http\Controllers\Apps\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\DistrictsDataTable;
use App\DataTables\StateDataTable;

class ShippingController extends Controller
{
    // district list
    public function district(DistrictsDataTable $dataTable){
        return $dataTable->render('pages.apps.shipping.district.list');
    }

    // shipping method
    public function shipping_method(){
        return view('pages.apps.shipping.method.list');
    }
}
