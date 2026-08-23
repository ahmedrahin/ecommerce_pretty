<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use App\DataTables\SubsubcategoriesDataTable;
use Illuminate\Http\Request;
use App\Models\Subsubcategory;

class SubsubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SubsubcategoriesDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.subsubcategory.list');
    }

    public function getSubsubcategories($subcategory_id)
    {
        $subsubcategories = Subsubcategory::where('subcategory_id', $subcategory_id)->get();
        return response()->json($subsubcategories);
    }

   public function getSubsubcategoriesForMultiple(Request $request)
    {
        $subcategoryIds = $request->query('ids');
        
        if (empty($subcategoryIds)) {
            return response()->json([]);
        }
        
        if (!is_array($subcategoryIds)) {
            $subcategoryIds = [$subcategoryIds];
        }
        
        $subsubcategories = Subsubcategory::whereIn('subcategory_id', $subcategoryIds)
            ->distinct()
            ->get();
        
        return response()->json($subsubcategories);
    }

}
