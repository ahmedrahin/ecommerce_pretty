<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'status', 'image' , 'category_id'
    ];

    //product summirize
    public function product()
    {
        return $this->hasMany(Product::class);
    }

    // Category relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function boot()
    {
        parent::boot();

        // Before saving the brand, generate the slug if the name has changed
        static::saving(function ($brand) {
            if ($brand->isDirty('name')) {
                $brand->slug = $brand->generateUniqueSlug($brand->name, $brand->id);
            }
        });
    }

    private function generateUniqueSlug($name, $id = 0)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Brand::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
