<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ProductsDataTable;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Product;
use App\Models\{ProductSpecification,ProductStockManage,AttributeValue,Attribute,Category,OrderItems,};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\FilterOption;
use App\Models\ProductFilterValue;
use App\Services\ProductDetailsService;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // dd($request->subcategory_id);
        $hasVariations = $this->checkHasValidVariation($request);

        $rules = [
            'name' => 'required|string',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|array',
            'subcategory_id.*' => 'exists:subcategories,id',
            'subsubcategory_id' => 'nullable|array',
            'subsubcategory_id.*' => 'exists:subsubcategories,id',
            'sku_code' => 'nullable|string|max:255',
            'quantity' => $hasVariations ? 'nullable|integer|min:0' : 'required|integer|min:1',
            'variations.*.quantity' => $hasVariations ? 'required|integer|min:0' : 'nullable',
            'status' => 'required|boolean',
            'base_price' => 'required|numeric',
            'back_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'discount_option' => 'nullable|in:1,2,3',
            'discount_percentage_or_flat_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:1,2,3,0',
            'publish_at' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:now',
            'thumb_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
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
            'thumb_image.required' => 'Select a thumbnail image',
            'category_id.required' => 'Category is required',
            'subcategory_id.*.exists' => 'Selected subcategory does not exist',
            'subsubcategory_id.*.exists' => 'Selected subsubcategory does not exist',
        ];

        // Validate the request with initial rules
        $validated = $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            // Custom validation for product options and discounts
            $basePrice = $validated['base_price'];
            $discountData = $this->calculateDiscount($request, $basePrice);

            if ($discountData['discount_amount'] > $basePrice) {
                DB::rollBack();
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

            // Store multiple subcategories (Many-to-Many)
            if ($request->has('subcategory_id') && !empty($request->subcategory_id)) {
                $product->subcategories()->attach($request->subcategory_id);
            }

            // Store multiple subsubcategories (Many-to-Many)
            if ($request->has('subsubcategory_id') && !empty($request->subsubcategory_id)) {
                $product->subsubcategories()->attach($request->subsubcategory_id);
            }

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

            if ($hasVariations) {
                $this->storeProductVariations($request, $product);
                // total varaint quantity sum
                $totalVariantQty = collect($request->input('variations', []))->sum(fn($v) => $v['quantity'] ?? 0);
                $product->update(['quantity' => $totalVariantQty]);
            } else {
                // Single product stock log
                if ($request->quantity > 0) {
                    $wholesale_price = $request->wholesale_price ?? $request->base_price;
                    ProductStockManage::create([
                        'product_id'       => $product->id,
                        'product_stock_id' => null,
                        'stock'            => 'stock_in',
                        'quantity'         => $request->quantity,
                        'wholesale_price'  => $wholesale_price,
                        'stocked_at'       => now(),
                        'total_amount'     => $wholesale_price * $request->quantity,
                    ]);
                }
            }

            // Save specifications if provided
            if ($request->filled('spec_group')) {
                foreach ($request->spec_group as $gIndex => $groupName) {
                    if (empty($groupName))
                        continue;

                    if (!empty($request->spec_name[$gIndex])) {
                        foreach ($request->spec_name[$gIndex] as $i => $specName) {
                            $specValue = $request->spec_value[$gIndex][$i] ?? null;

                            if ($specName || $specValue) {
                                ProductSpecification::create([
                                    'product_id' => $product->id,
                                    'group' => $groupName,
                                    'name' => $specName,
                                    'value' => $specValue,
                                ]);
                            }
                        }
                    }
                }
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

            // Handle gallery images
            if ($request->hasFile('gallery_image')) {
                foreach ($request->file('gallery_image') as $image) {
                    if (!in_array($image->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {
                        continue;
                    }

                    $path = $image->store('uploads/product_images/gallery', 'real_public');
                    $product->galleryImages()->create(['image' => $path]);
                }
            }

            $this->refreshCache();

            DB::commit();

            return response()->json([
                'message' => 'Product created successfully!',
                'product' => $product->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation failed: ' . $e->getMessage());

            return response()->json([
                'errors' => ['Product creation failed: ' . $e->getMessage()]
            ], 500);
        }
    }

    private function handleFileUploads(Request $request): array
    {
        $data = [];

        if ($request->hasFile('thumb_image')) {
            $thumbImage = $request->file('thumb_image');
            $thumbImageName = time() . '_' . $thumbImage->getClientOriginalName();
            $thumbImage->move(public_path('uploads/product_images'), $thumbImageName);
            $data['thumb_image'] = 'uploads/product_images/' . $thumbImageName;
        }

        if ($request->hasFile('back_image')) {
            $backImage = $request->file('back_image');
            $backImageName = time() . '_' . $backImage->getClientOriginalName();
            $backImage->move(public_path('uploads/product_images'), $backImageName);
            $data['back_image'] = 'uploads/product_images/' . $backImageName;
        }

        return $data;
    }

    private function prepareProductData(array $validated, Request $request): array
    {
        $discountDetails = $this->calculateDiscount($request, $validated['base_price']);
        $sku_code = rand(1000, 9999);
        return [
            'name' => $validated['name'],
            // 'brand_id' => $validated['brand_id'],
            'category_id' => $validated['category_id'],
            
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'key_features' => $request->key_features,
            'base_price' => $validated['base_price'],
            'wholesale_price' => $request->wholesale_price,
            'quantity' => $validated['quantity'] ?? 0,
            'sku_code' => $request->sku_code ?? '',
            'video_link' => $request->video_link,
            'status' => $request->status,
            'publish_at' => $request->publish_at,
            'free_shipping' => $request->free_shipping ?? 'no',
            'is_new' => $request->is_new ?? 2,
            'is_featured' => $request->is_featured ?? 2,
            'pre_order' => $request->preorder ?? 2,
            'stock_out' => $request->stock_out ?? 0,
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

    private function checkHasValidVariation(Request $request): bool
    {
        $attributes = $request->input('attributes', []);
        foreach ($attributes as $attributeGroup) {
            foreach ($attributeGroup as $attr) {
                if (!empty($attr['attribute_value'])) {
                    return true;
                }
            }
        }
        return false;
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
                'price'    => (!is_null($variation['price']) && $variation['price'] !== '') ? $variation['price'] : ($product->offer_price > 0 ? $product->offer_price : $product->base_price),
                'image'    => $imagePath,
            ]);

            $labelParts = [];

            if (!empty($attributes[$index])) {
                foreach ($attributes[$index] as $attr) {
                    if (!empty($attr['attribute_value'])) {
                        $stock->attributeOptions()->create([
                            'attribute_id'       => $attr['attribute'],
                            'attribute_value_id' => $attr['attribute_value'],
                        ]);

                        $attrName  = Attribute::find($attr['attribute'])?->attr_name;
                        $attrValue = AttributeValue::find($attr['attribute_value'])?->attr_value;

                        if ($attrName && $attrValue) {
                            $labelParts[] = "{$attrName}: {$attrValue}";
                        }
                    }
                }
            }

            $variationLabel = implode(' / ', $labelParts); // "Size: M / Color: Red"

            // Stock log with label
            $qty = $variation['quantity'] ?? 0;
            if ($qty > 0) {
                $wholesale_price = $request->wholesale_price ?? $variation['price'] ?? $product->base_price;
                    ProductStockManage::create([
                    'product_id'       => $product->id,
                    'product_stock_id' => $stock->id,
                    'variation_label'  => $variationLabel, //"Size: M / Color: Red"
                    'stock'            => 'stock_in',
                    'quantity'         => $qty,
                    'wholesale_price'  => $wholesale_price,
                    'stocked_at'       => now(),
                    'total_amount'     => $wholesale_price * $qty,
                ]);
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

    public function updateOrder(Request $request)
    {
        $order = $request->input('order');
        if (is_array($order)) {
            foreach ($order as $position => $id) {
                Product::where('id', $id)->update(['sort_order' => $position + 1]);
            }
            Cache::forget('home_featured_products');
            Cache::forget('home_new_arrivals');
            Cache::forget('home_best_selling');
            $this->refreshCache();
            return response()->json(['status' => 'success', 'message' => 'Product order updated successfully.']);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid order data.'], 400);
    }

    // Refresh the cache
    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Product::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        });
    }
}
