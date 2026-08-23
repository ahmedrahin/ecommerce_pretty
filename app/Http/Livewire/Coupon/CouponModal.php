<?php

namespace App\Http\Livewire\Coupon;

use Livewire\Component;
use App\Models\Coupon;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Category;

class CouponModal extends Component
{
    public $edit_mode = false;
    public $coupon_id;
    public $code;
    public $discount_type;
    public $discount_amount;
    public $min_purchase_amount;
    public $usage_limit;
    public $used;
    public $start_at;
    public $expire_date;
    public $categories = [];
    public $category_ids = [];

    // Cache key for coupon
    private $cacheKey;
    public function __construct()
    {
        $this->cacheKey = config('dbcachekey.coupon');
    }

    protected $listeners = [
        'update_coupon'             => 'updateCoupon',
        'delete_coupon'             => 'delete',
        'open_add_modal'            => 'openAddModal',
        'update_status'             => 'updateStatus'
    ];

    public function mount()
    {
        $this->categories = Category::where('status',1)->get();
        // Fetch all coupons to check and update their status if needed
        $coupons = Coupon::all();

        foreach ($coupons as $coupon) {
            // Check if the coupon has expired or reached its usage limit
            $isExpired = $coupon->expire_date && Carbon::parse($coupon->expire_date)->isPast();
            $isUsageLimitReached = $coupon->usage_limit && $coupon->used >= $coupon->usage_limit;

            // Update the status based on conditions
            if ($isExpired || $isUsageLimitReached) {
                $coupon->status = 2;
            } else {
                if( $coupon->status != 0 ){
                    $coupon->status = 1;
                }
            }

            $coupon->save();
        }
    }

    public function submit()
    {
        // Validation rules
        $rules = [
            'code'                  => 'required|unique:coupons,code',
            'discount_type'         => 'required',
            'discount_amount'       => 'required|numeric',
            'min_purchase_amount'   => 'nullable|numeric|min:1',
            'usage_limit'           => 'nullable|numeric|min:1',
            'used'                  => 'nullable|numeric|min:1',
            'start_at'              => 'required|date',
            'expire_date'           => 'nullable|after:start_at',
        ];

        // Conditional rule for discount amount if type is percentage
        if ($this->discount_type === 'percentage') {
            $rules['discount_amount'] .= '|max:100';
        }

        // Validation messages
        $messages = [
            'discount_amount.max' => 'Percentage discount cannot exceed 100.',
            'expire_date.after' => 'Expire date must be after the start date.',
        ];

        if ($this->edit_mode) {
            $rules['code'] = 'required|unique:coupons,code,' . $this->coupon_id;
        }

        // Validate form input
        $this->validate($rules, $messages);

        // Check if we are in edit mode
        if ($this->edit_mode) {
            $this->updateExisting();
        } else {
            $this->createNew();
        }

        // Reset the form
        $this->resetForm();
    }

    // Create a new coupon
    public function createNew()
    {
        // Prepare the coupon data
        $data = [
            'code'                => $this->code,
            'discount_type'       => $this->discount_type,
            'discount_amount'     => $this->discount_amount,
            'min_purchase_amount' => $this->min_purchase_amount,
            'usage_limit'         => $this->usage_limit ?? null,
            'used'                => $this->used ?? null,
            'start_at'            => $this->start_at,
            'expire_date'         => $this->expire_date ?? null,
        ];

        // Create the coupon
        $coupon = Coupon::create($data);

        // attach selected categories
        $coupon->categories()->attach($this->category_ids);

        $this->emit('success', 'A new coupon created');
        // Reset form fields
        $this->resetForm();
        $this->refreshCache();
    }

    // update the coupon
    public function updateCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);

        $this->edit_mode = true;
        $this->coupon_id = $coupon->id;
        $this->code = $coupon->code;
        $this->discount_type = $coupon->discount_type;
        $this->discount_amount = $coupon->discount_amount;
        $this->min_purchase_amount = $coupon->min_purchase_amount;
        $this->usage_limit = $coupon->usage_limit;
        $this->used = $coupon->used;
        $this->start_at = $coupon->start_at;
        $this->expire_date = $coupon->expire_date ?? null;
        $this->category_ids = $coupon->categories()->pluck('categories.id')->toArray();

    }

     // Update an existing coupon
    public function updateExisting()
    {
        $coupon = Coupon::findOrFail($this->coupon_id);
        $minAmount = $this->min_purchase_amount !== '' ? $this->min_purchase_amount : null;
        
        $coupon->id = $this->coupon_id;
        $coupon->code = $this->code;
        $coupon->discount_type = $this->discount_type;
        $coupon->discount_amount = $this->discount_amount;
        $coupon->min_purchase_amount = $minAmount;
        $coupon->usage_limit = $this->usage_limit ?? null;
        $coupon->used = $this->used ?? null;
        $coupon->start_at = $this->start_at;
        $coupon->expire_date = $this->expire_date !== '' ? $this->expire_date : null;

        // sync selected categories
        $coupon->categories()->sync($this->category_ids);

        $coupon->save();
        $this->emit('success', __('Coupon updated successfully.'));
        $this->refreshCache();
        $this->resetForm();
    }

    //update status
    public function updateStatus($id, $status)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['status' => $status]);

        $message = $status == 0 ? "{$coupon->code} is inactive" : "{$coupon->code} is active";
        $type = $status == 0 ? 'info' : 'success';

        // Emit success message
        $this->emit($type, $message);
        $this->refreshCache();
    }

     // Delete a coupon
    public function delete($id)
    {
        // Find the coupon by ID
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        // Emit success message and reset the form
        $this->emit('info', __('Coupon was deleted.'));
        $this->resetForm();
        $this->refreshCache();
    }

    public function openAddModal()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        // Reset edit mode and form fields
        $this->edit_mode = false;
        $this->reset(['code', 'expire_date', 'start_at', 'used', 'usage_limit', 'min_purchase_amount', 'discount_amount', 'discount_type', 'category_ids']);
    }

    // Refresh the cache
    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Coupon::orderBy('id', 'desc')->get();
        });
    }

    public function hydrate()
    {
        // Reset error bag and validation
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.coupon.coupon-modal');
    }
}
