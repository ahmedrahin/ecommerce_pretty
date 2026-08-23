<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class)
                    ->whereHas('order', function($q) {
                        $q->whereNull('deleted_at');
                    });
    }


   public function filterValues()
    {
        return $this->belongsToMany(
            FilterOptionValue::class,
            'product_filter_values',
            'product_id',
            'filter_option_value_id'
        );
    }

    public function cancelOrderItems()
    {
        return $this->hasMany(OrderItems::class)
            ->whereHas('order', function ($q) {
                $q->where('delivery_status', 'canceled');
            });
    }

    public function fakeOrderItems()
    {
        return $this->hasMany(OrderItems::class)
            ->whereHas('order', function ($q) {
                $q->where('delivery_status', 'fake');
            });
    }

    public function orderItemsWithOrder()
    {
        return $this->hasMany(OrderItems::class)->with('order');
    }

    public function getRevenueAttribute()
    {
        return $this->orderItems()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.delivery_status', ['delivered', 'completed'])->whereNull('orders.deleted_at') 
            ->sum(DB::raw('order_items.price * order_items.quantity'));
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function QuesionAnswer()
    {
        return $this->hasMany(Question::class, 'product_id')->whereNotNull('answer');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function subsubcategory()
    {
        return $this->belongsTo(Subsubcategory::class);
    }

    public function galleryImages()
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function productStock()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(ProductStockManage::class);
    }

    public function latestStockIn()
    {
        return $this->hasOne(ProductStockManage::class)
            ->where('stock', 'stock_in')
            ->latest('id');
    }

    // Latest stock-out
    public function latestStockOut()
    {
        return $this->hasOne(ProductStockManage::class)
            ->where('stock', 'out_of_stock')
            ->latest('id');
    }


    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public static function boot()
    {
        parent::boot();

        // Before saving the product, generate the slug if the name has changed
        static::saving(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = $product->generateUniqueSlug($product->name, $product->id);
            }
        });

        // Before deleting the product, delete related product options
        static::deleting(function ($product) {
            $product->productStock()->delete();
        });
    }

    private function generateUniqueSlug($name, $id = 0)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function scopeActiveProducts($query)
    {
        return $query->whereIn('status', [1, 3])
            //  ->where(function ($query) {
            //      $query->whereNull('publish_at')
            //            ->orWhere('publish_at', '<=', Carbon::now());
            //  })
            ->where(function ($query) {
                $query->whereNull('expire_date')
                    ->orWhere('expire_date', '>', Carbon::now());
            });
    }
}
