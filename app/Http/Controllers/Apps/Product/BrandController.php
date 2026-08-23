<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use App\DataTables\BrandsDataTable;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator; // Add this line

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BrandsDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.brand.list');
    }

    public function getBrand($category_id)
    {
        $brands = Brand::where('category_id', $category_id)->get();
        return response()->json($brands);
    }

}
