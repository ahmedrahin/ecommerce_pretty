<?php

namespace App\Http\Controllers\Apps\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ReviewDataTable;

class ReviewController extends Controller
{
    public function index(ReviewDataTable $dataTable){
        return $dataTable->render('pages.apps.review.list');
    }
}
