<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductAction extends Component
{
    public $status = 'active';

    
    private $cacheKey;

    // Event listeners
    protected $listeners = [
        'delete_product' => 'delete',
        'update_status' => 'updateStatus',
    ];

    public function __construct()
    {
        $this->cacheKey = config('dbcachekey.product');
    }

    public function mount(){
        Product::where('status', 3)
        ->where('publish_at', '<=', now())
        ->update(['status' => 1, 'publish_at' => null]);
    }

    // Update product status
    public function updateStatus($id, $status)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => $status]);

        $message = $status == 0 ? "{$product->name} is inactive" : "{$product->name} is active";
        $type = $status == 0 ? 'info' : 'success';

        // Emit success message
        $this->emit($type, $message);
        $this->refreshCache();
    }

    // Delete a product
    public function delete($id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);

        // Delete the product images if they exist
        // $this->deleteImage($product->thumb_image);
        $this->deleteImage($product->back_image);

        // Find and delete associated gallery images
        $this->deleteGalleryImages($id);

        // Delete the product
        $product->delete();

        // Emit success message and refresh the cache
        $this->emit('info', __('Product was deleted.'));
        $this->refreshCache();
    }

    // Delete image from storage
    private function deleteImage($imagePath)
    {
        if ($imagePath) {
            Storage::disk('real_public')->delete($imagePath);
        }
    }

    // Delete gallery images associated with a product
    private function deleteGalleryImages($productId)
    {
        $galleryImages = GalleryImage::where('product_id', $productId)->get();

        foreach ($galleryImages as $galleryImage) {
            $this->deleteImage($galleryImage->image);
            $galleryImage->delete();
        }
    }

    // Refresh the cache
    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Product::orderBy('id', 'desc')->get();
        });
    }
}
