<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use App\Models\GalleryImage;
use App\Models\ProductSpecification;
use App\Models\{ProductStockManage,ProductStock};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\FilterOption;
use App\Models\ProductFilterValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductEditController extends Controller
{
    //edit product
    public function edit(string $id)
    {
        $product = Product::with([
            'tags',
            'specifications',
            'filterValues',
            'subcategories',
            'subsubcategories'
        ])->find($id);

        // Retrieve other necessary data
        $brands = Brand::orderBy('name')->get();
        $attributes = Attribute::orderBy('attr_name')->where('status', 1)->get();
        $attribute_values = AttributeValue::orderBy('attr_value')->get();
        $categories = Category::orderBy('name')->get();
        $tagItem = $product->tags->pluck('name')->toArray();
        $tags = Tag::distinct()->pluck('name')->toArray();
        $productStocks = $product->productStock()->get();

        // Get subcategories for the selected category
        $subcategories = Subcategory::where('category_id', $product->category_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        // Get subsubcategories for the selected subcategories
        $selectedSubcategoryIds = $product->subcategories->pluck('id')->toArray();
        $subsubcategories = Subsubcategory::whereIn('subcategory_id', $selectedSubcategoryIds)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $filters = FilterOption::orWhereDoesntHave('categories')
            ->with('values')
            ->get();

        // Return the view with the necessary data
        return view('pages.apps.product.edit.edit', compact(
            'brands',
            'categories',
            'subcategories',
            'subsubcategories',
            'attributes',
            'attribute_values',
            'tags',
            'tagItem',
            'product',
            'productStocks',
            'filters'
        ));
    }


    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $oldBasePrice  = $product->base_price;
        $oldOfferPrice = $product->offer_price;

        // Validate the request data
        $rules = [
            'name'                                => 'required|string',
            'brand_id'                            => 'nullable|exists:brands,id',
            'category_id'                         => 'required|exists:categories,id',
            'subcategory_id'                      => 'nullable|array',
            'subcategory_id.*'                    => 'exists:subcategories,id',
            'subsubcategory_id'                   => 'nullable|array',
            'subsubcategory_id.*'                 => 'exists:subsubcategories,id',
            'sku_code'                            => 'nullable|string|max:255',
            'quantity'                            => 'required|integer',
            'status'                              => 'required|in:1,2,3,0',
            'base_price'                          => 'required|numeric',
            'thumb_image'                         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'back_image'                          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_image'                       => 'nullable|array|max:10',
            'gallery_image.*'                     => 'image|mimes:jpg,jpeg,png,webp|max:1536',
            'remove_gallery_ids'                  => 'nullable|array',
            'remove_gallery_ids.*'                => 'integer|exists:gallery_images,id',
            'discount_option'                     => 'nullable|in:1,2,3',
            'discount_percentage_or_flat_amount'  => 'nullable|numeric|min:0',
            'publish_at'                          => 'nullable|date',
        ];

        // Conditionally require discount amount
        if ($request->has('discount_option') && $request->discount_option != 1) {
            $rules['discount_percentage_or_flat_amount'] = 'required|numeric|min:1';
        }

        $messages = [
            'discount_percentage_or_flat_amount.required' => 'The discount amount is required when a discount option is selected.',
            'discount_percentage_or_flat_amount.numeric'  => 'The discount amount must be a number.',
            'discount_percentage_or_flat_amount.min'      => 'The discount amount must be at least 1.',
            'publish_at.required'                         => 'The publish date is required when scheduling the product.',
            'publish_at.date'                             => 'The publish date must be a valid date.',
            'publish_at.after_or_equal'                   => 'The publish date must be a current or future time.',
            'expire_date.after_or_equal'                  => 'The expiry date must be a current or future time.',
            'thumb_image.required'                        => 'Select a thumbnail image',
            'category_id.required'                        => 'Category is required',
            'subcategory_id.*.exists'                     => 'Selected subcategory does not exist',
            'subsubcategory_id.*.exists'                  => 'Selected subsubcategory does not exist',
        ];

        $validated = $request->validate($rules, $messages);

        // Discount check (before transaction)
        $basePrice    = $validated['base_price'];
        $discountData = $this->calculateDiscount($request, $basePrice);

        if ($discountData['discount_amount'] > $basePrice) {
            return response()->json([
                'errors' => [
                    'discount_percentage_or_flat_amount' => ['Discount amount cannot exceed the base price.']
                ]
            ], 422);
        }

        DB::beginTransaction();

        try {
            // ── Product core data ──────────────────────────────────────────────────
            $data      = $this->prepareProductData($validated, $request, $product);
            $imageData = $this->handleFileUploads($request, $product);

            // many-to-many fields products table এ নেই — remove
            unset($data['subcategory_id']);
            unset($data['subsubcategory_id']);

            $product->update(array_merge($data, $imageData));
            $product->refresh();

            // ── Subcategories sync ─────────────────────────────────────────────────
            $subcategoryIds = array_map('intval', $request->input('subcategory_id', []));
            $product->subcategories()->sync($subcategoryIds);

            // ── Subsubcategories sync ──────────────────────────────────────────────
            $subsubcategoryIds = array_map('intval', $request->input('subsubcategory_id', []));
            $product->subsubcategories()->sync($subsubcategoryIds);

            // ── Tags ───────────────────────────────────────────────────────────────
            $this->storeTags($request, $product);

            // ── Specifications ─────────────────────────────────────────────────────
            $existingSpecIds = ProductSpecification::where('product_id', $product->id)
                ->pluck('id')
                ->toArray();
            $newSpecIds = [];

            if ($request->filled('spec_group')) {
                foreach ($request->spec_group as $gIndex => $groupName) {
                    if (empty($groupName)) continue;

                    if (!empty($request->spec_name[$gIndex])) {
                        foreach ($request->spec_name[$gIndex] as $i => $specName) {
                            $specValue = $request->spec_value[$gIndex][$i] ?? null;
                            $specId    = $request->spec_id[$gIndex][$i] ?? null;

                            if ($specName || $specValue) {
                                if ($specId) {
                                    $spec = ProductSpecification::find($specId);
                                    if ($spec) {
                                        $spec->update([
                                            'group' => $groupName,
                                            'name'  => $specName,
                                            'value' => $specValue,
                                        ]);
                                        $newSpecIds[] = $spec->id;
                                    }
                                } else {
                                    $spec = ProductSpecification::create([
                                        'product_id' => $product->id,
                                        'group'      => $groupName,
                                        'name'       => $specName,
                                        'value'      => $specValue,
                                    ]);
                                    $newSpecIds[] = $spec->id;
                                }
                            }
                        }
                    }
                }
            }

            // Delete removed specs
            $toDelete = array_diff($existingSpecIds, $newSpecIds);
            if (!empty($toDelete)) {
                ProductSpecification::whereIn('id', $toDelete)->delete();
            }

            // ── Filters sync ───────────────────────────────────────────────────────
            $filters  = $request->input('filters', []);
            $syncData = [];
            foreach ($filters as $filterId => $values) {
                foreach ($values as $valueId) {
                    $syncData[$valueId] = ['filter_option_id' => $filterId];
                }
            }
            $product->filterValues()->sync($syncData);

            // ── Variations ─────────────────────────────────────────────────────────
            if ($request->has('variations') && !empty($request->input('variations'))) {
                $this->updateProductVariations($request, $product, $oldBasePrice, $oldOfferPrice);
            }

            // ── Gallery: delete removed existing images ────────────────────────────
            if ($request->has('remove_gallery_ids')) {
                $removeIds = array_filter((array) $request->remove_gallery_ids);
                if (!empty($removeIds)) {
                    $imagesToDelete = GalleryImage::whereIn('id', $removeIds)
                        ->where('product_id', $product->id)
                        ->get();

                    foreach ($imagesToDelete as $img) {
                        if (file_exists($img->image)) {
                            unlink($img->image);
                        }
                        $img->delete();
                    }
                }
            }

            // 2. New images add
            if ($request->hasFile('gallery_image')) {
                foreach ($request->file('gallery_image') as $image) {
                    if (!in_array($image->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {
                        continue;
                    }
                    $path = $image->store('uploads/product_images/gallery', 'real_public');
                    $product->galleryImages()->create(['image' => $path]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product update failed: ' . $e->getMessage());

            return response()->json([
                'errors' => ['Product update failed: ' . $e->getMessage()]
            ], 500);
        }
    }

    protected function updateProductVariations(Request $request, Product $product, $oldBasePrice = null, $oldOfferPrice = null)
    {
        $variations     = $request->input('variations', []);
        $attributes     = $request->input('attributes', []);
        $variationFiles = $request->file('variations', []);

        if (empty($variations)) {
            return;
        }

        // ── Deleted variations ─────────────────────────────────────────────────────
        $deletedVariations   = $request->input('deleted_variations', '');
        $deletedVariationIds = $deletedVariations ? explode(',', $deletedVariations) : [];

        if (!empty($deletedVariationIds)) {
            foreach ($deletedVariationIds as $deleteId) {
                $stockToDelete = $product->productStock()->find($deleteId);
                if ($stockToDelete) {
                    // Image delete
                    if ($stockToDelete->image && file_exists(public_path($stockToDelete->image))) {
                        unlink(public_path($stockToDelete->image));
                    }

                    $stockToDelete->attributeOptions()->delete();
                    $stockToDelete->delete();
                }
            }
        }

        // ── Filter valid variations ────────────────────────────────────────────────
        $filteredVariations = [];
        $filteredAttributes = [];
        $filteredFiles      = [];
        $newIndex           = 0;

        foreach ($variations as $index => $variation) {
            $rawAttributes = $attributes[$index] ?? [];
            $hasAttribute  = false;

            foreach ($rawAttributes as $attr) {
                if (!empty($attr['attribute']) && !empty($attr['attribute_value'])) {
                    $hasAttribute = true;
                    break;
                }
            }

            $variationId = $variation['id'] ?? null;

            // If it's a new variation and has no attributes, skip it.
            // Existing variations shouldn't be skipped because their attributes might just be read-only/unsubmitted.
            if (!$variationId && !$hasAttribute) {
                continue;
            }

            if (!$variationId || !in_array($variationId, $deletedVariationIds)) {
                $filteredVariations[$newIndex] = $variation;
                $filteredAttributes[$newIndex] = $attributes[$index] ?? [];
                $filteredFiles[$newIndex]      = $variationFiles[$index] ?? [];
                $newIndex++;
            }
        }

        // ── Update or Create variations ────────────────────────────────────────────
        foreach ($filteredVariations as $index => $variation) {
            $variationId = $variation['id'] ?? null;

            // Image handle
            $imageFile = $filteredFiles[$index]['image'] ?? null;
            $imagePath = $variation['existing_image'] ?? null;

            if ($imageFile instanceof \Illuminate\Http\UploadedFile) {
                // Old image delete
                if ($variationId && !empty($variation['existing_image'])) {
                    $oldImagePath = public_path($variation['existing_image']);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $imagePath = $this->uploadVariationImage($imageFile);
            }

            $newPrice = ($product->offer_price > 0 ? (float) $product->offer_price : (float) $product->base_price);
            $submittedPrice = $variation['price'] ?? null;
            
            \Log::info("DEBUG VARIATION PRICE: Submitted: " . json_encode($submittedPrice) . " | Old Base: " . json_encode($oldBasePrice) . " | Old Offer: " . json_encode($oldOfferPrice) . " | New Price: " . json_encode($newPrice));

            $isDefaultPrice = false;
            if ($submittedPrice === '' || is_null($submittedPrice)) {
                $isDefaultPrice = true;
            } else {
                $subPriceF = (float)$submittedPrice;
                $oldBaseF = (float)$oldBasePrice;
                $oldOfferF = (float)$oldOfferPrice;
                if ($subPriceF === $oldBaseF || $subPriceF === $oldOfferF) {
                    $isDefaultPrice = true;
                }
            }

            $priceToSave = $isDefaultPrice ? $newPrice : $submittedPrice;

            if ($variationId && $stock = $product->productStock()->find($variationId)) {
                // ── EXISTING variation update ──────────────────────────────────────
                $stockData = [
                    // quantity update করবো না — Stock In page থেকে manage হবে
                    'price' => $priceToSave,
                ];

                if ($imagePath) {
                    $stockData['image'] = $imagePath;
                }

                $stock->update($stockData);

                // Attribute options re-create (only if attributes are provided in the request)
                $hasAttribute = false;
                $rawAttributes = $filteredAttributes[$index] ?? [];
                foreach ($rawAttributes as $attr) {
                    if (!empty($attr['attribute']) && !empty($attr['attribute_value'])) {
                        $hasAttribute = true;
                        break;
                    }
                }

                if ($hasAttribute && !empty($filteredAttributes[$index])) {
                    $stock->attributeOptions()->delete();

                    foreach ($filteredAttributes[$index] as $attr) {
                        if (!empty($attr['attribute']) && !empty($attr['attribute_value'])) {
                            $stock->attributeOptions()->create([
                                'attribute_id'       => $attr['attribute'],
                                'attribute_value_id' => $attr['attribute_value'],
                            ]);
                        }
                    }

                    //variation_label update করো stock manage history তে
                    $newLabel = $stock->fresh()
                        ->attributeOptions
                        ->map(fn($opt) => $opt->attribute->attr_name . ': ' . $opt->attributeValue->attr_value)
                        ->implode(' / ');

                    ProductStockManage::where('product_stock_id', $stock->id)->update(['variation_label' => $newLabel]);
                }

            } else {
                // ── NEW variation create ───────────────────────────────────────────
                $newQty = $variation['quantity'] ?? 0;

                $stock = $product->productStock()->create([
                    'quantity' => $newQty,
                    'price'    => $priceToSave,
                    'image'    => $imagePath,
                ]);

                // Attribute options create
                if (!empty($filteredAttributes[$index])) {
                    foreach ($filteredAttributes[$index] as $attr) {
                        if (!empty($attr['attribute']) && !empty($attr['attribute_value'])) {
                            $stock->attributeOptions()->create([
                                'attribute_id'       => $attr['attribute'],
                                'attribute_value_id' => $attr['attribute_value'],
                            ]);
                        }
                    }
                }

                // নতুন variant এর stock log — attribute save এর পরে label তৈরি
                if ($newQty > 0) {
                    $label = $stock->fresh()
                        ->attributeOptions
                        ->map(fn($opt) => $opt->attribute->attr_name . ': ' . $opt->attributeValue->attr_value)
                        ->implode(' / ');

                    $wholesale_price = $request->wholesale_price ?? $variation['price'] ?? $product->base_price;

                    ProductStockManage::create([
                        'product_id'       => $product->id,
                        'product_stock_id' => $stock->id,
                        'variation_label'  => $label,
                        'quantity'         => $newQty,
                        'stocked_at'       => now(),
                        'product_price'    => $product->base_price ?? 0,
                        'wholesale_price'  => $wholesale_price,
                        'total_amount'     => $wholesale_price * $newQty,
                        'stock'            => 'stock_in',
                    ]);
                }
            }
        }

        //product quantity recalculate only if product has stock variations
        $totalStock = ProductStock::where('product_id', $product->id)->sum('quantity');
        if ($totalStock > 0 || ProductStock::where('product_id', $product->id)->exists()) {
            $product->update([
                'quantity' => $totalStock
            ]);
        }
    }

    private function uploadVariationImage($imageFile)
    {
        $folderPath = public_path('uploads/product_images/variations');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        $fileName = time() . '_' . uniqid() . '_' . $imageFile->getClientOriginalName();
        $imageFile->move($folderPath, $fileName);

        return 'uploads/product_images/variations/' . $fileName;
    }

    // Handle file uploads
    private function handleFileUploads(Request $request, Product $product): array
    {
        $data = [];

        // Handle thumb image
        if ($request->hasThumbRemove == 1) {
            // Delete old image from public folder
            if ($product->thumb_image && file_exists(public_path($product->thumb_image))) {
                unlink(public_path($product->thumb_image));
            }
            $data['thumb_image'] = null;
        } elseif ($request->hasFile('thumb_image')) {
            // Delete old image from public folder
            if ($product->thumb_image && file_exists(public_path($product->thumb_image))) {
                unlink(public_path($product->thumb_image));
            }

            $thumbImage = $request->file('thumb_image');
            $thumbImageName = time() . '_' . $thumbImage->getClientOriginalName();
            $thumbImage->move(public_path('uploads/product_images'), $thumbImageName);

            // Save the image path to the database
            $data['thumb_image'] = 'uploads/product_images/' . $thumbImageName;
        }


        // Handle back image
        if ($request->hasBackRemove == 1) {
            // Delete old back image from the public folder
            if ($product->back_image && file_exists(public_path($product->back_image))) {
                unlink(public_path($product->back_image));
            }
            $data['back_image'] = null;
        } elseif ($request->hasFile('back_image')) {
            // Delete old back image from the public folder
            if ($product->back_image && file_exists(public_path($product->back_image))) {
                unlink(public_path($product->back_image));
            }

            $backImage = $request->file('back_image');
            $backImageName = time() . '_' . $backImage->getClientOriginalName();
            $backImage->move(public_path('uploads/product_images'), $backImageName);

            // Save the image path to the database
            $data['back_image'] = 'uploads/product_images/' . $backImageName;
        }


        return $data;
    }


    private function prepareProductData(array $validated, Request $request, Product $product = null): array
    {
        $discountDetails = $this->calculateDiscount($request, $validated['base_price']);
        $productData = [
            'name' => $validated['name'],
            // 'brand_id' => $validated['brand_id'] ?? '',
            'category_id' => $validated['category_id'],
            'subcategory_id' => $request->subcategory_id,
            'subsubcategory_id' => $request->subsubcategory_id,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'key_features' => $request->key_features,
            'base_price' => $validated['base_price'],
            'sku_code' => $request->sku_code,
            'video_link' => $request->video_link,
            'free_shipping' => $request->free_shipping ?? 'no',
            'is_new' => $request->is_new ?? 2,
            'is_featured' => $request->is_featured ?? 2,
            'status' => $request->status,
            'publish_at' => $request->publish_at,
            'expire_date' => $request->expire_date,
            'pre_order' => $request->preorder ?? 2,
            'stock_out' => $request->stock_out ?? 0,

            ...$discountDetails,
        ];

        if ($product) {
            $productData['quantity'] = $product->quantity;
        } else {
            $productData['quantity'] = $validated['quantity'] ?? 0;
        }

        return $productData;
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

    //store tag
    private function storeTags(Request $request, Product $product): void
    {
        if ($request->has('tags')) {
            $tags = json_decode($request->tags, true);

            // Delete all existing tags for the product
            Tag::where('product_id', $product->id)->delete();

            if (!empty($tags)) {
                foreach ($tags as $tagData) {
                    if (!empty($tagData['value'])) {
                        // Create a new tag or find existing one
                        Tag::create([
                            'product_id' => $product->id,
                            'name' => $tagData['value'],
                        ]);
                    }
                }
            }
        }
    }
}
