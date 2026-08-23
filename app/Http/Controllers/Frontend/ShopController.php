<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Subsubcategory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\FilterOption;

class ShopController extends Controller
{
    // show product in shop page
    public function allProducts(Request $request)
    {
        $query = Product::activeProducts();
        [$products, $perPage, $from, $to] = $this->getFilteredProducts($request, $query);

        if ($request->ajax()) {
            if ($request->has('get_tags_only')) {
                return view('frontend.partials.filter-tags', compact('products', 'from', 'to'))->render();
            }
            return view('frontend.pages.shop.product-list', compact('products'))->render();
        }

        $brands = Brand::where('status', 1)->get();

        return view('frontend.pages.shop.shop', compact('products', 'perPage', 'from', 'to', 'brands'));
    }

    private function getFilteredProducts(Request $request, $query)
    {
        $perPage = $request->get('limit', config('website_settings.item_per_page'));

        // Price range filter
        $from = $request->input('from', 0);
        $to = $request->input('to', 5000);

        if ($request->filled('filter_price')) {
            [$minPrice, $maxPrice] = explode('-', $request->filter_price);
            $from = (int) $minPrice;
            $to = (int) $maxPrice;
            $query->whereBetween('offer_price', [$from, $to]);
        }

        // Sorting
        if ($request->filled('sort')) {
            $column = $request->sort;
            $direction = $request->get('order', 'ASC');
            $allowedColumns = ['offer_price', 'created_at', 'id'];
            $allowedDirections = ['ASC', 'DESC'];

            if ($column === 'best_selling') {
                $query->withCount('orderItems')
                    ->orderBy('order_items_count', 'DESC');
            }
            elseif (in_array($column, $allowedColumns) && in_array($direction, $allowedDirections)) {
                $query->orderBy($column, $direction);
            }
        } else {
            $query->orderBy('is_featured', 'asc')->orderBy('id', 'desc');
        }

        // Filters
        if ($request->filled('filter')) {
            $filters = [];
            foreach ($request->filter as $filter) {
                if (strpos($filter, ':') !== false) {
                    [$key, $value] = explode(':', $filter, 2);
                    $filters[$key][] = $value;
                }
            }

            foreach ($filters as $key => $values) {
                switch ($key) {
                    case 'brand':
                        $query->whereIn('brand_id', $values);
                        break;
                    default:
                        $query->whereHas('filterValues', function ($q) use ($values) {
                            $q->whereIn('filter_option_values.id', $values);
                        });
                        break;
                }
            }
        }

        // Paginate
        $products = $query->paginate($perPage)->appends($request->except('page'));

        return [$products, $perPage, $from, $to];
    }

    public function categoryProducts(Request $request, ...$slugs)
    {
        $slugCount = count($slugs);

        if ($slugCount === 1) {
            // 🟢 Category
            $category = Category::where('slug', $slugs[0])->where('status', 1)->firstOrFail();
            $query = Product::activeProducts()->where('category_id', $category->id);
            $filters = FilterOption::whereHas('categories', fn($q) => $q->where('categories.id', $category->id))
                ->orWhereDoesntHave('categories')
                ->with('categories')
                ->get();
        } elseif ($slugCount === 2) {
            // 🟢 Subcategory
            $parent = Category::where('slug', $slugs[0])->where('status', 1)->firstOrFail();
            $category = Subcategory::where('slug', $slugs[1])->where('category_id', $parent->id)->where('status', 1)->firstOrFail();
            $query = Product::activeProducts()->where('subcategory_id', $category->id);
            $filters = FilterOption::whereHas('categories', fn($q) => $q->where('categories.id', $parent->id))
                ->orWhereDoesntHave('categories')
                ->with('categories')
                ->get();
        } elseif ($slugCount === 3) {
            // 🟢 SubSubcategory
            $parent = Category::where('slug', $slugs[0])->where('status', 1)->firstOrFail();
            $parentSub = Subcategory::where('slug', $slugs[1])->where('status', 1)->firstOrFail();
            $category = SubSubcategory::where('slug', $slugs[2])->where('subcategory_id', $parentSub->id)->where('status', 1)->firstOrFail();
            $query = Product::activeProducts()->where('subsubcategory_id', $category->id);
            $filters = FilterOption::whereHas('categories', fn($q) => $q->where('categories.id', $parent->id))
                ->orWhereDoesntHave('categories')
                ->with('categories')
                ->get();
        }

        [$products, $perPage, $from, $to] = $this->getFilteredProducts($request, $query);

        if ($request->ajax()) {
            return view('frontend.pages.shop.product-list', compact('products'))->render();
        }

        $brands = Brand::where('status', 1)->get();

        return view('frontend.pages.shop.category-product', compact('category', 'products', 'perPage', 'from', 'to', 'filters', 'brands'));
    }


    public function searchProducts(Request $request)
    {
        $searchTerm = $request->get('query');

        if (empty($searchTerm)) {
            return redirect()->route('shop');
        }

        $categories = Category::where('status', 1)->where('name', 'like', '%' . $searchTerm . '%')->get();

        $perPage = $request->get('limit', config('website_settings.item_per_page'));

        // Base query
        $query = Product::activeProducts()
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhereHas('tags', function ($tagQuery) use ($searchTerm) {
                        $tagQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });

        // Sorting
        if ($request->filled('sort')) {
            $column = $request->sort;
            $direction = $request->get('order', 'ASC');

            $allowedColumns = ['offer_price', 'created_at', 'id'];
            $allowedDirections = ['ASC', 'DESC'];

            if (in_array($column, $allowedColumns) && in_array(strtoupper($direction), $allowedDirections)) {
                $query->orderBy($column, $direction);
            }
        } else {
            $query->orderBy('is_featured', 'asc')->orderBy('id', 'desc');
        }

        $count = $query->count();

        // Pagination
        $products = $query->paginate($perPage)->appends($request->except('page'));

        // Ajax load
        if ($request->ajax()) {
            return view('frontend.pages.shop.category-product-list', compact('products'))->render();
        }

        $brands = Brand::where('status', 1)->get();

        return view('frontend.pages.shop.search-product', compact('products', 'perPage', 'searchTerm', 'categories', 'count', 'brands'));
    }

    // product details page
    public function productDetails(string $slug)
    {
        if ($slug) {
            $product = Product::activeProducts()
                ->where('slug', $slug)
                ->with([
                    'specifications',
                    'category:id,name,slug,image',
                    'brand:id,name',
                    'galleryImages:id,product_id,image',
                    'tags:id,product_id,name',
                    'productStock:id,product_id',
                    'productStock.attributeOptions:id,product_stock_id,attribute_id,attribute_value_id'
                ])
                ->first();

            // Check if the product exists and is active
            if (!$product) {
                return redirect()->back()->with('error', 'The product is not available.');
            }
        }

        return view('frontend.pages.product.details', compact('product'));
    }

    // product wishlist
    public function wishlist()
    {
        return view('frontend.pages.shop.wishlist');
    }

    public function compare(Request $request)
    {
        $ids = $request->get('ids', '');

        if ($ids !== '') {
            $newIds = array_filter(explode(',', $ids));
            $compare = session()->get('compare_products', []);
            foreach ($newIds as $id) {
                if (!in_array($id, $compare))
                    $compare[] = $id;
            }
            $compare = array_slice($compare, -3);
            session()->put('compare_products', $compare);
        } else {
            $compare = session()->get('compare_products', []);
        }

        $products = empty($compare)
            ? collect()
            : Product::with([
                'productStock.attributeOptions.attribute',
                'productStock.attributeOptions.attributeValue',
                'specifications',
            ])->whereIn('id', $compare)
                ->orderByRaw('FIELD(id,' . implode(',', $compare) . ')')
                ->get();

        // collect all attributes (variants)
        $allAttributes = collect();
        foreach ($products as $product) {
            foreach ($product->productStock as $stock) {
                foreach ($stock->attributeOptions as $option) {
                    $allAttributes->put($option->attribute->id, $option->attribute->attr_name);
                }
            }
        }

        // collect all specification groups + names
        $allSpecifications = collect();
        foreach ($products as $product) {
            foreach ($product->specifications as $spec) {
                $allSpecifications
                    ->put($spec->group, collect())
                    ->put($spec->group, $allSpecifications->get($spec->group)->put($spec->name, $spec->name));
            }
        }

        return view('frontend.pages.product.compare', [
            'products' => $products,
            'compareProducts' => $products,
            'allAttributes' => $allAttributes,
            'allSpecifications' => $allSpecifications,
        ]);
    }

    public function fullCompare(Request $request)
    {
        $ids = $request->get('ids', '');
        $compareIds = array_filter(explode(',', $ids));

        $products = Product::with([
            'productStock.attributeOptions.attribute',
            'productStock.attributeOptions.attributeValue',
            'specifications',
        ])->whereIn('id', $compareIds)
            ->orderByRaw('FIELD(id,' . implode(',', $compareIds) . ')')
            ->get();

        $allAttributes = collect();
        foreach ($products as $product) {
            foreach ($product->productStock as $stock) {
                foreach ($stock->attributeOptions as $option) {
                    $allAttributes->put($option->attribute->id, $option->attribute->attr_name);
                }
            }
        }

        // collect all specification groups + names
        $allSpecifications = collect();
        foreach ($products as $product) {
            foreach ($product->specifications as $spec) {
                $allSpecifications
                    ->put($spec->group, collect())
                    ->put($spec->group, $allSpecifications->get($spec->group)->put($spec->name, $spec->name));
            }
        }

        return view('frontend.pages.product.compare', [
            'products' => $products,
            'compareProducts' => $products,
            'allAttributes' => $allAttributes,
            'allSpecifications' => $allSpecifications,
        ]);
    }

    public function removeCompare($id)
    {
        $compare = session()->get('compare_products', []);

        // Remove both string and int representation
        $compare = array_values(array_diff($compare, [(string) $id, (int) $id]));

        session()->put('compare_products', $compare);

        $ids = implode(',', $compare);

        return redirect()->route('compare', ['ids' => $ids]);
    }
}
