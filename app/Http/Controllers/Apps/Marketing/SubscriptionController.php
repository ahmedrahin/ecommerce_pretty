<?php

namespace App\Http\Controllers\Apps\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\SubscriptionDataTable;
use App\Models\Subscription;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
     public function index(SubscriptionDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.subscription.list');
    }
}
