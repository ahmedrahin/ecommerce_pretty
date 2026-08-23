<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use App\DataTables\CategoriesDataTable;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoriesDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.category.list');
    }

    /**
     * Update sort order of categories via drag & drop.
     */
    public function updateSortOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:categories,id',
        ]);

        foreach ($request->order as $index => $categoryId) {
            Category::where('id', $categoryId)->update(['sort_order' => $index + 1]);
        }

        Cache::forget('home_categories');
        Cache::forget(config('dbcachekey.category'));

        return response()->json([
            'status' => 'success',
            'message' => 'Category order updated successfully.'
        ]);
    }
}
