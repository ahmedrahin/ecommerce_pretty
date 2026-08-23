<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name'        => 'required|string|unique:products,name',
            'brand_id'    => 'nullable|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'sku_code'    => 'nullable|string|max:255',
            'quantity'    => 'required|integer|min:1',
            'status'      => 'required|in:1,2,3,0',
            'base_price'  => 'required|numeric',
            'wholesale_price' => 'required|numeric',
            'thumb_image' => 'required|image',
            'back_image'  => 'nullable|image',
            'discount_option' => 'nullable|in:1,2,3',
            'discount_percentage_or_flat_amount' => 'nullable|numeric|min:0',
            'publish_at'  => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:now',
        ];

        if ($this->discount_option && $this->discount_option != 1) {
            $rules['discount_percentage_or_flat_amount'] = 'required|numeric|min:1';
        }

        if ($this->status == 3) {
            $rules['publish_at'] = 'required|date|after_or_equal:now';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'discount_percentage_or_flat_amount.required' => 'The discount amount is required when a discount option is selected.',
            'discount_percentage_or_flat_amount.numeric' => 'The discount amount must be a number.',
            'discount_percentage_or_flat_amount.min' => 'The discount amount must be at least 1.',
            'publish_at.required' => 'The publish date is required when scheduling the product.',
            'publish_at.after_or_equal' => 'The publish date must be current or future.',
            'expire_date.after_or_equal' => 'The expiry date must be current or future.',
            'thumb_image.required' => 'Select a thumbnail image.',
        ];
    }
}
