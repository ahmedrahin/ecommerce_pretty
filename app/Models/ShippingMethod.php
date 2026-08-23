<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['base_id', 'base_charge', 'provider_name', 'provider_charge', 'status'];

    public function District()
    {
        return $this->belongsTo(District::class, 'base_id');
    }
}
