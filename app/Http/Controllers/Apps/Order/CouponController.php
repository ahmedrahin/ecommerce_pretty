<?php

namespace App\Http\Controllers\Apps\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\CouponDataTable;

class CouponController extends Controller
{
    public function index(CouponDataTable $dataTable){
        return $dataTable->render('pages.apps.coupon.list');
    }
}
