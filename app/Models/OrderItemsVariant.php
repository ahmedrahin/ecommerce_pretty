<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemsVariant extends Model
{
    use HasFactory;
    protected $table = 'order_item_variations';
    protected $guarded = [];
}
