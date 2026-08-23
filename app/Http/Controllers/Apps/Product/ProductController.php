<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ProductsDataTable;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductSpecification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\OrderItems;
use App\Models\FilterOption;
use App\Models\ProductFilterValue;
use App\Services\ProductDetailsService;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    private $cacheKey;
    protected $productService;

    public function __construct(ProductDetailsService $productService)
    {
        $this->cacheKey = config('dbcachekey.product');
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ProductsDataTable $dataTable)
    {
        $categories = Category::where('status', 1)->get();
        return $dataTable->render('pages.apps.product.list', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::orderBy('name')->where('status', 1)->get();
        $attributes = Attribute::orderBy('attr_name')->where('status', 1)->get();
        $attribute_values = AttributeValue::orderBy('attr_value')->get();
        $categories = Category::orderBy('name')->where('status', 1)->get();
        $tags = Tag::distinct()->pluck('name')->toArray();

        $filters = FilterOption::orWhereDoesntHave('categories')
            ->with('values')
            ->get();

        return view('pages.apps.product.create', compact('brands', 'categories', 'attributes', 'attribute_values', 'tags', 'filters'));
    }

    public function fullCompare()
    {
        return view('pages.apps.product.full-compare');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Define the validation rules
        $rules = [
            'name' => 'required|string',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'sku_code' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|boolean',
            'base_price' => 'required|numeric',
            //'wholesale_price' => 'required|numeric',
            'thumb_image' => 'nullable|image',
            'back_image' => 'nullable|image',
            'discount_option' => 'nullable|in:1,2,3',
            'discount_percentage_or_flat_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:1,2,3,0',
            'publish_at' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:now',
            'thumb_image' => 'required'
        ];

        // Conditionally require the discount_percentage_or_flat_amount field
        if ($request->has('discount_option') && $request->discount_option != 1) {
            $rules['discount_percentage_or_flat_amount'] = 'required|numeric|min:1';
        }

        if ($request->status == 3) {
            $rules['publish_at'] = 'required|date|after_or_equal:now';
        }

        // Custom validation messages
        $messages = [
            'discount_percentage_or_flat_amount.required' => 'The discount amount is required when a discount option is selected.',
            'discount_percentage_or_flat_amount.numeric' => 'The discount amount must be a number.',
            'discount_percentage_or_flat_amount.min' => 'The discount amount must be at least 1.',
            'publish_at.required' => 'The publish date is required when scheduling the product.',
            'publish_at.date' => 'The publish date must be a valid date.',
            'publish_at.after_or_equal' => 'The publish date must be a current or future time.',
            'expire_date.after_or_equal' => 'The expiry date must be a current or future time.',
            'thumb_image.required' => 'Select a thumbnail image'
        ];

        // Validate the request with initial rules
        $validated = $request->validate($rules, $messages);

        // Custom validation for product options and discounts
        $basePrice = $validated['base_price'];
        $discountData = $this->calculateDiscount($request, $basePrice);

        if ($discountData['discount_amount'] > $basePrice) {
            return response()->json([
                'errors' => [
                    'discount_percentage_or_flat_amount' => ['Discount amount cannot exceed the base price.']
                ]
            ], 422);
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        $data = $this->handleFileUploads($request);

        // Merge validated data into the $data array
        $data = array_merge($data, $this->prepareProductData($validated, $request));

        // Create the product
        $product = Product::create($data);

        // Handle tags if provided
        $this->storeTags($request, $product);

        $attributes = $request->input('attributes', []);
        $hasValidVariation = false;

        // check if any variation actually has attribute values
        foreach ($attributes as $attributeGroup) {
            foreach ($attributeGroup as $attr) {
                if (!empty($attr['attribute_value'])) {
                    $hasValidVariation = true;
                    break 2;
                }
            }
        }

        if ($hasValidVariation) {
            $this->storeProductVariations($request, $product);
        }

        // Save specifications if provided
        // if ($request->filled('spec_group')) {
        //     foreach ($request->spec_group as $gIndex => $groupName) {
        //         if (empty($groupName))
        //             continue;

        //         if (!empty($request->spec_name[$gIndex])) {
        //             foreach ($request->spec_name[$gIndex] as $i => $specName) {
        //                 $specValue = $request->spec_value[$gIndex][$i] ?? null;

        //                 if ($specName || $specValue) {
        //                     ProductSpecification::create([
        //                         'product_id' => $product->id,
        //                         'group' => $groupName,
        //                         'name' => $specName,
        //                         'value' => $specValue,
        //                     ]);
        //                 }
        //             }
        //         }
        //     }
        // }

        // insert stock table
        if ($request->has('quantity') && $request->quantity > 0) {
            $wholesale_price = $request->wholesale_price ?? $request->base_price;
            \App\Models\ProductStockManage::create([
                'product_id' => $product->id,
                'stock' => 'stock_in',
                'quantity' => $request->quantity,
                'wholesale_price' => $wholesale_price,
                'stocked_at' => now(),
                'total_amount' => $wholesale_price * $request->quantity
            ]);
        }

        // filter
        if ($request->has('filters')) {
            foreach ($request->filters as $filterOptionId => $values) {
                foreach ($values as $valueId) {
                    ProductFilterValue::create([
                        'product_id' => $product->id,
                        'filter_option_id' => $filterOptionId,
                        'filter_option_value_id' => $valueId,
                    ]);
                }
            }
        }

        $this->refreshCache();

        return response()->json([
            'message' => 'Product created successfully!',
            'product' => $product->id,
        ]);
    }

    private function handleFileUploads(Request $request): array
    {
        $data = [];

        $cleanName = function ($file, $prefix = '') {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            // space + special char remove
            $name = preg_replace('/[^A-Za-z0-9\-]/', '-', $name);
            $name = strtolower($name);

            return time() . '_' . $prefix . $name . '.' . $extension;
        };

        if ($request->hasFile('thumb_image')) {
            $file = $request->file('thumb_image');
            $fileName = $cleanName($file);
            $file->move(public_path('uploads/product_images'), $fileName);
            $data['thumb_image'] = 'uploads/product_images/' . $fileName;
        }

        if ($request->hasFile('back_image')) {
            $file = $request->file('back_image');
            $fileName = $cleanName($file);
            $file->move(public_path('uploads/product_images'), $fileName);
            $data['back_image'] = 'uploads/product_images/' . $fileName;
        }

        if ($request->hasFile('p_logo')) {
            $file = $request->file('p_logo');
            $fileName = $cleanName($file, 'p_logo_');
            $file->move(public_path('uploads/product_images'), $fileName);
            $data['p_logo'] = 'uploads/product_images/' . $fileName;
        }

        return $data;
    }

    private function prepareProductData(array $validated, Request $request): array
    {
        $discountDetails = $this->calculateDiscount($request, $validated['base_price']);
        $sku_code = rand(1000, 9999);
        return [
            'name' => $validated['name'],
            'brand_id' => $validated['brand_id'],
            'category_id' => $validated['category_id'],
            'subcategory_id' => $request->subcategory_id,
            'subsubcategory_id' => $request->subsubcategory_id,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'key_features' => $request->key_features,
            'base_price' => $validated['base_price'],
            'wholesale_price' => $request->wholesale_price,
            'quantity' => $validated['quantity'],
            'sku_code' => $request->sku_code ?? $sku_code,
            'video_link' => $request->video_link,
            'status' => $request->status,
            'publish_at' => $request->publish_at,
            'free_shipping' => $request->free_shipping ?? 'no',
            'is_new' => $request->is_new ?? 2,
            'is_featured' => $request->is_featured ?? 2,
            'badge' => $request->badge ?? null,
            'pre_order' => $request->preorder ?? 2,
            'model' => $request->model,
            'user_id' => Auth::id(),
            'expire_date' => $request->expire_date,
            ...$discountDetails,
        ];
    }

    private function calculateDiscount(Request $request, float $basePrice): array
    {
        $discountPercentageOrFlatAmount = $request->discount_percentage_or_flat_amount ?? 0;
        $discountAmount = 0;

        if ($request->discount_option == 2) { // Percentage
            $discountAmount = round($basePrice * $discountPercentageOrFlatAmount / 100);
        } elseif ($request->discount_option == 3) { // Flat amount
            $discountAmount = $discountPercentageOrFlatAmount;
        }

        return [
            'discount_option' => $request->discount_option ?? 1,
            'discount_percentage_or_flat_amount' => $discountPercentageOrFlatAmount,
            'discount_amount' => $discountAmount,
            'offer_price' => $basePrice - $discountAmount,
        ];
    }

    private function storeTags(Request $request, Product $product): void
    {
        if ($request->has('tags')) {
            $tags = json_decode($request->tags, true);
            if (!empty($tags)) {
                foreach ($tags as $tagData) {
                    if (!empty($tagData['value'])) {
                        Tag::create([
                            'name' => $tagData['value'],
                            'product_id' => $product->id,
                        ]);
                    }
                }
            }
        }
    }

    protected function storeProductVariations(Request $request, Product $product)
    {
        $variations = $request->input('variations', []);
        $attributes = $request->input('attributes', []);
        $variationFiles = $request->file('variations', []);
        foreach ($variations as $index => $variation) {

             $imageFile = $variationFiles[$index]['image'] ?? null;

            $imagePath = null;
            if ($imageFile instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $this->uploadVariationImage($imageFile);
            }

            $stock = $product->productStock()->create([
                'sku_code' => $variation['sku_code'] ?? null,
                'quantity' => $variation['quantity'] ?? 0,
                'printful_variant_id' => $variation['printful_variant_id'] ?? null,
                'price' => $variation['price'] ?? $product->base_price,
                'image'    => $imagePath,
            ]);

            if (!empty($attributes[$index])) {
                foreach ($attributes[$index] as $attr) {
                    if (!empty($attr['attribute_value'])) {
                        $stock->attributeOptions()->create([
                            'attribute_id' => $attr['attribute'],
                            'attribute_value_id' => $attr['attribute_value'],
                        ]);
                    }
                }
            }
        }
    }

    private function uploadVariationImage($imageFile)
    {
        $folderPath = public_path('uploads/product_images/variations');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        $fileName = time() . '_' . $imageFile->getClientOriginalName();
        $imageFile->move($folderPath, $fileName);

        return 'uploads/product_images/variations/' . $fileName;
    }


    public function show(string $id)
    {
        $data = $this->productService->getProductDetails($id);

        if (!$data || !$data['product']) {
            return redirect()->back()->with('error', 'The product is not found');
        }
        return view('pages.apps.product.details', $data);
    }


    // Refresh the cache
    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Product::orderBy('id', 'desc')->get();
        });
    }
}
