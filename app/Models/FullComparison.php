<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FullComparison extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function firstProduct()
    {
        return $this->belongsTo(Product::class, 'first_product_id');
    }

    public function secondProduct()
    {
        return $this->belongsTo(Product::class, 'second_product_id');
    }

}
