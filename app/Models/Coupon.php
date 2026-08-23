<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['code', 'discount_type', 'discount_amount', 'min_purchase_amount', 'usage_limit', 'used', 'status', 'expire_date', 'start_at'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'cupon_code', 'code')->whereNotNull('cupon_code');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_categories');
    }
}
