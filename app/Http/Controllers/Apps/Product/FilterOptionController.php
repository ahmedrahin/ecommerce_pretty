<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FilterOption;
use App\Models\FilterOptionValue;
use App\Models\Product;

class FilterOptionController extends Controller
{
    public function index()
    {
        $attributes = FilterOption::orderBy('id', 'desc')->get();
        return view('pages.apps.product.filter.filter', compact('attributes'));
    }


    public function getCategoryFilters($id)
    {
        $filters = FilterOption::whereHas('categories', function ($q) use ($id) {
            $q->where('categories.id', $id);
        })
            ->orWhereDoesntHave('categories')
            ->with('values')
            ->get();

        $html = '';

        foreach ($filters as $filter) {
            $html .= '<div class="col-md-4 filter-box">
                <div class="card card-flush py-4">
                    <div class="card-body pt-0 pb-0">
                        <div class="mb-5">
                            <h3 class="mb-4">' . e($filter->option_name) . '</h3>';

            foreach ($filter->values as $value) {
                $inputId = 'filter_' . $filter->id . '_' . $value->id;
                $html .= '
                    <div class="form-check form-check-custom form-check-solid mb-3">
                        <input class="form-check-input" type="checkbox"
                            name="filters[' . $filter->id . '][]"
                            value="' . $value->id . '"
                            id="' . $inputId . '">
                        <label class="form-check-label" for="' . $inputId . '">' . e($value->option_value) . '</label>
                    </div>';
            }

            $html .= '       </div>
                    </div>
                </div>
            </div>';
        }

        return response()->json(['html' => $html]);
    }

    public function getCategoryFiltersEdit($id, Request $request)
    {
        $productId = $request->input('product_id');
        $selectedValueIds = [];

        if ($productId) {
            $product = Product::with('filterValues')->find($productId);
            if ($product) {
                $selectedValueIds = $product->filterValues->pluck('id')->toArray();
            }
        }

        $filters = FilterOption::whereHas('categories', function ($q) use ($id) {
            $q->where('categories.id', $id);
        })
            ->orWhereDoesntHave('categories')
            ->with('values')
            ->get();

        $html = '';

        foreach ($filters as $filter) {
            $html .= '<div class="col-md-4 filter-box">
            <div class="card card-flush py-4">
                <div class="card-body pt-0 pb-0">
                    <div class="mb-5">
                        <h3 class="mb-4">' . e($filter->option_name) . '</h3>';

            foreach ($filter->values as $value) {
                $inputId = 'filter_' . $filter->id . '_' . $value->id;
                $checked = in_array($value->id, $selectedValueIds) ? 'checked' : '';

                $html .= '
                <div class="form-check form-check-custom form-check-solid mb-3">
                    <input class="form-check-input" type="checkbox"
                        name="filters[' . $filter->id . '][]"
                        value="' . $value->id . '"
                        id="' . $inputId . '"
                        ' . $checked . '>
                    <label class="form-check-label" for="' . $inputId . '">' . e($value->option_value) . '</label>
                </div>';
            }

            $html .= '       </div>
                </div>
            </div>
        </div>';
        }

        return response()->json(['html' => $html]);
    }

}
