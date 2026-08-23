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
use App\Models\ProductSpecification;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\FilterOption;
use App\Models\ProductFilterValue;

class ProductEditController extends Controller
{
    //edit product
    public function edit(string $id)
    {
        // Retrieve the product with the given ID and its associated tags
        $product = Product::with('tags', 'specifications', 'filterValues')->find($id);

        // Retrieve other necessary data
        $brands = Brand::orderBy('name')->get();
        $attributes = Attribute::orderBy('attr_name')->where('status', 1)->get();
        $attribute_values = AttributeValue::orderBy('attr_value')->get();
        $categories = Category::orderBy('name')->get();
        $tagItem = $product->tags->pluck('name')->toArray();
        $tags = Tag::distinct()->pluck('name')->toArray();
        $productStocks = $product->productStock()->get();

        $filters = FilterOption::orWhereDoesntHave('categories')
            ->with('values')
            ->get();

        // Return the view with the necessary data
        return view('pages.apps.product.edit.edit', compact('brands', 'categories', 'attributes', 'attribute_values', 'tags', 'tagItem', 'product', 'productStocks', 'filters'));
    }

    public function update(Request $request, string $id)
    {
        // Find the product
        $product = Product::findOrFail($id);

        if (!empty($product)) {

            // Validate the request data
            $rules = [
                'name' => 'required|string',
                'brand_id' => 'nullable|exists:brands,id',
                'category_id' => 'required|exists:categories,id',
                'sku_code' => 'nullable|string|max:255',
                'quantity' => 'required|integer',
                'status' => 'required|boolean',
                'base_price' => 'required|numeric',
                'thumb_image' => 'nullable|image',
                'back_image' => 'nullable|image',
                'gallery_image.*' => 'nullable|image',
                'discount_option' => 'nullable|in:1,2,3',
                'discount_percentage_or_flat_amount' => 'nullable|numeric|min:0',
                'status' => 'required|in:1,2,3,0',
                'publish_at' => 'nullable|date',
                // 'expire_date'               => 'nullable|date|after_or_equal:now',

            ];

            // Conditionally require the discount_percentage_or_flat_amount field
            if ($request->has('discount_option') && $request->discount_option != 1) {
                $rules['discount_percentage_or_flat_amount'] = 'required|numeric|min:1';
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

            // Validate the request data
            $validated = $request->validate($rules, $messages);

            $basePrice = $validated['base_price'];
            $discountData = $this->calculateDiscount($request, $basePrice);

            // Check if the discount amount exceeds the base price
            if ($discountData['discount_amount'] > $basePrice) {
                return response()->json([
                    'errors' => [
                        'discount_percentage_or_flat_amount' => ['Discount amount cannot exceed the base price.']
                    ]
                ], 422);
            }

            // Custom validation for variations' quantity based on attributes
            $errors = [];

            if (!empty($errors)) {
                return response()->json(['errors' => $errors], 422);
            }

            // update product data
            $data = $this->prepareProductData($validated, $request);

            // update product thumbnal
            $imageData = $this->handleFileUploads($request, $product);

            // update product
            $product->update(array_merge($data, $imageData));

            // Handle tags if provided
            $this->storeTags($request, $product);

            // In your update controller
            $existingSpecIds = ProductSpecification::where('product_id', $product->id)->pluck('id')->toArray();

            $newSpecIds = [];

            if ($request->filled('spec_group')) {
                foreach ($request->spec_group as $gIndex => $groupName) {
                    if (empty($groupName))
                        continue;

                    if (!empty($request->spec_name[$gIndex])) {
                        foreach ($request->spec_name[$gIndex] as $i => $specName) {
                            $specValue = $request->spec_value[$gIndex][$i] ?? null;
                            $specId = $request->spec_id[$gIndex][$i] ?? null; // hidden input in form

                            if ($specName || $specValue) {
                                if ($specId) {
                                    // update existing
                                    $spec = ProductSpecification::find($specId);
                                    if ($spec) {
                                        $spec->update([
                                            'group' => $groupName,
                                            'name' => $specName,
                                            'value' => $specValue,
                                        ]);
                                        $newSpecIds[] = $spec->id;
                                    }
                                } else {
                                    // create new
                                    $spec = ProductSpecification::create([
                                        'product_id' => $product->id,
                                        'group' => $groupName,
                                        'name' => $specName,
                                        'value' => $specValue,
                                    ]);
                                    $newSpecIds[] = $spec->id;
                                }
                            }
                        }
                    }
                }
            }

            // delete removed specs
            $toDelete = array_diff($existingSpecIds, $newSpecIds);
            ProductSpecification::whereIn('id', $toDelete)->delete();

            // category filtering
            $filters = $request->input('filters', []);
            $syncData = [];

            foreach ($filters as $filterId => $values) {
                foreach ($values as $valueId) {
                    $syncData[$valueId] = ['filter_option_id' => $filterId];
                }
            }
            $product->filterValues()->sync($syncData);

            // variation handle
            $this->updateProductVariations($request, $product);

            session::flash('success', 'The product updated successfully');
            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product->id,
            ]);
        }
    }

    protected function updateProductVariations(Request $request, Product $product)
    {
        $variations = $request->input('variations', []);
        $attributes = $request->input('attributes', []);
        $variationFiles = $request->file('variations', []);

        if (empty($variations)) {
            return;
        }

        // Get deleted variations from hidden field
        $deletedVariations = $request->input('deleted_variations', '');
        $deletedVariationIds = $deletedVariations ? explode(',', $deletedVariations) : [];

        // Filter out deleted variations from the variations array
        $filteredVariations = [];
        $filteredAttributes = [];
        $filteredFiles = [];
        $newIndex = 0;

        foreach ($variations as $index => $variation) {
             // raw attributes
            $rawAttributes = $attributes[$index] ?? [];

            // check if user actually selected at least one attribute
            $hasAttribute = false;

            foreach ($rawAttributes as $attr) {
                if (!empty($attr['attribute']) && !empty($attr['attribute_value'])) {
                    $hasAttribute = true;
                    break;
                }
            }

            if (!$hasAttribute) {
                continue;
            }
                
            $variationId = $variation['id'] ?? null;

            // Only keep variations that are NOT in the deleted list
            if (!$variationId || !in_array($variationId, $deletedVariationIds)) {
                $filteredVariations[$newIndex] = $variation;
                $filteredAttributes[$newIndex] = $attributes[$index] ?? [];
                $filteredFiles[$newIndex] = $variationFiles[$index] ?? [];
                $newIndex++;
            }
        }

        // Delete variations that were removed
        if (!empty($deletedVariationIds)) {
            foreach ($deletedVariationIds as $deleteId) {
                $stockToDelete = $product->productStock()->find($deleteId);
                if ($stockToDelete) {
                    if ($stockToDelete->image && file_exists(public_path($stockToDelete->image))) {
                        unlink(public_path($stockToDelete->image));
                    }
                    // Delete attribute options
                    $stockToDelete->attributeOptions()->delete();
                    // Delete the variation
                    $stockToDelete->delete();
                }
            }
        }

        // Update or create variations using FILTERED data
        foreach ($filteredVariations as $index => $variation) {
            $variationId = $variation['id'] ?? null;

            // Handle image upload
            $imageFile = $filteredFiles[$index]['image'] ?? null;
            $imagePath = $variation['existing_image'] ?? null;

            if ($imageFile instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $this->uploadVariationImage($imageFile);

                // Delete old image if new one is uploaded
                if ($variationId && !empty($variation['existing_image'])) {
                    $oldImagePath = public_path($variation['existing_image']);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }

            if ($variationId && $stock = $product->productStock()->find($variationId)) {
                $stockData = [
                    'quantity' => $variation['quantity'] ?? 0,
                    'price' => $variation['price'] ?? $product->base_price,
                    'printful_variant_id' => $variation['printful_variant_id'] ?? null,
                ];

                if ($imagePath) {
                    $stockData['image'] = $imagePath;
                }

                $stock->update($stockData);

                // Update attribute options
                if (!empty($filteredAttributes[$index])) {
                    // Delete existing attribute options
                    $stock->attributeOptions()->delete();

                    // Create new attribute options
                    foreach ($filteredAttributes[$index] as $attr) {
                        if (!empty($attr['attribute']) && !empty($attr['attribute_value'])) {
                            $stock->attributeOptions()->create([
                                'attribute_id' => $attr['attribute'],
                                'attribute_value_id' => $attr['attribute_value'],
                            ]);
                        }
                    }
                }

            } else {

                $stock = $product->productStock()->create([
                    'quantity' => $variation['quantity'] ?? 0,
                    'price' => $variation['price'] ?? $product->base_price,
                    'image' => $imagePath,
                    'printful_variant_id' => $variation['printful_variant_id'] ?? null,
                ]);

                // Create attribute options for new variation
                if (!empty($filteredAttributes[$index])) {
                    foreach ($filteredAttributes[$index] as $attr) {
                        if (!empty($attr['attribute']) && !empty($attr['attribute_value'])) {
                            $stock->attributeOptions()->create([
                                'attribute_id' => $attr['attribute'],
                                'attribute_value_id' => $attr['attribute_value'],
                            ]);
                        }
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
        $fileName = time() . '_' . uniqid() . '_' . $imageFile->getClientOriginalName();
        $imageFile->move($folderPath, $fileName);

        return 'uploads/product_images/variations/' . $fileName;
    }

    // Handle file uploads
    private function makeSafeFileName($file, $prefix = '')
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        // remove space & special chars
        $name = preg_replace('/[^A-Za-z0-9\-]/', '-', $name);
        $name = strtolower($name);

        return time() . '_' . $prefix . $name . '.' . $extension;
    }
    
    private function handleFileUploads(Request $request, Product $product): array
    {
        $data = [];

        /* ================= THUMB IMAGE ================= */
        if ($request->hasThumbRemove == 1) {

            if ($product->thumb_image && file_exists(public_path($product->thumb_image))) {
                unlink(public_path($product->thumb_image));
            }

            $data['thumb_image'] = null;

        } elseif ($request->hasFile('thumb_image')) {

            if ($product->thumb_image && file_exists(public_path($product->thumb_image))) {
                unlink(public_path($product->thumb_image));
            }

            $file = $request->file('thumb_image');
            $fileName = $this->makeSafeFileName($file);

            $file->move(public_path('uploads/product_images'), $fileName);
            $data['thumb_image'] = 'uploads/product_images/' . $fileName;
        }


        /* ================= BACK IMAGE ================= */
        if ($request->hasBackRemove == 1) {

            if ($product->back_image && file_exists(public_path($product->back_image))) {
                unlink(public_path($product->back_image));
            }

            $data['back_image'] = null;

        } elseif ($request->hasFile('back_image')) {

            if ($product->back_image && file_exists(public_path($product->back_image))) {
                unlink(public_path($product->back_image));
            }

            $file = $request->file('back_image');
            $fileName = $this->makeSafeFileName($file);

            $file->move(public_path('uploads/product_images'), $fileName);
            $data['back_image'] = 'uploads/product_images/' . $fileName;
        }


        /* ================= PRINTFUL LOGO ================= */
        if ($request->hasRemovePLogo == 1) {

            if ($product->p_logo && file_exists(public_path($product->p_logo))) {
                unlink(public_path($product->p_logo));
            }

            $data['p_logo'] = null;

        } elseif ($request->hasFile('p_logo')) {

            if ($product->p_logo && file_exists(public_path($product->p_logo))) {
                unlink(public_path($product->p_logo));
            }

            $file = $request->file('p_logo');
            $fileName = $this->makeSafeFileName($file, 'p_logo_');

            $file->move(public_path('uploads/product_images'), $fileName);
            $data['p_logo'] = 'uploads/product_images/' . $fileName;
        }

        return $data;
    }

    private function prepareProductData(array $validated, Request $request): array
    {
        $discountDetails = $this->calculateDiscount($request, $validated['base_price']);
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
            'quantity' => $validated['quantity'],
            'sku_code' => $request->sku_code,
            'video_link' => $request->video_link,
            'free_shipping' => $request->free_shipping ?? 'no',
            'is_new' => $request->is_new ?? 2,
            'is_featured' => $request->is_featured ?? 2,
            'status' => $request->status,
            'publish_at' => $request->publish_at,
            'expire_date' => $request->expire_date,
            'pre_order' => $request->preorder ?? 2,
            'badge' => $request->badge ?? null,

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
