<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subsubcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'subcategory_id', 'category_id', 'slug', 'description', 'status'
    ];

    public static function boot()
    {
        parent::boot();

        // Before saving the category, generate the slug if the name has changed
        static::saving(function ($subsubcategory) {
            if ($subsubcategory->isDirty('name')) {
                $subsubcategory->slug = $subsubcategory->generateUniqueSlug($subsubcategory->name, $subsubcategory->id);
            }
        });
    }

    private function generateUniqueSlug($name, $id = 0)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Subsubcategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Get the subcategory that owns the subsubcategory.
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Get the category that owns the subsubcategory.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

}
