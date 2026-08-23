<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EditGellaryImages extends Component
{
    use WithFileUploads;

    public $product;
    public $images = [];
    public $imagePreviews = [];
    public $imageNames = [];
    public $imageSizes = [];
    public $removedImages = [];
    public $alertMessage = '';

    protected $listeners = ['save'];

    public function mount($product)
    {
        $this->product = Product::find($product);

        foreach ($this->product->galleryImages as $image) {
            $this->imagePreviews[] = Storage::disk('real_public')->url($image->image);
            $this->imageNames[] = basename($image->image);
            $this->imageSizes[] = $this->formatSizeUnits(Storage::disk('real_public')->size($image->image));
        }
    }

    public function updatedImages()
    {
        $this->resetAlertMessage();

        $validator = Validator::make(
            ['images' => $this->images],
            // ['images.*' => 'image|mimes:jpeg,jpg,png|max:1024'],
            [
                'images.*.image' => 'Only image files are allowed.',
                'images.*.mimes' => 'Only .jpeg, .jpg, and .png formats are allowed.',
                // 'images.*.max'   => 'Each file must be less than 2MB.'
            ]
        );

        if ($validator->fails()) {
            $this->alertMessage = implode(' ', $validator->errors()->all());
            $this->images = [];
            $this->emit('reset-file-input');
            return;
        }

        if (count($this->images) > 10) {
            $this->alertMessage = 'You can only upload up to 10 files.';
            $this->images = [];
            $this->emit('reset-file-input');
            return;
        }

        foreach ($this->images as $image) {
            $this->imagePreviews[] = $image->temporaryUrl();
            $this->imageNames[] = $image->getClientOriginalName();
            $this->imageSizes[] = $this->formatSizeUnits($image->getSize());
        }
    }

    public function removeImage($index)
    {
        // Check if the image exists in the newly uploaded images array
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        } 
        // Otherwise, check if the image exists in the previews array (already uploaded images)
        elseif (isset($this->imagePreviews[$index])) {
            $this->removedImages[] = basename($this->imagePreviews[$index]);
            unset($this->imagePreviews[$index]);
            unset($this->imageNames[$index]);
            unset($this->imageSizes[$index]);
            $this->imagePreviews = array_values($this->imagePreviews);
            $this->imageNames = array_values($this->imageNames);
            $this->imageSizes = array_values($this->imageSizes);
        }
    }


    public function save($productId)
    {
        $product = Product::find($productId);

        // Handle gallery images
        foreach ($this->removedImages as $imageName) {
            $imagePath = "uploads/product_images/gallery/{$imageName}";
            Storage::disk('real_public')->delete($imagePath);
            $this->product->galleryImages()->where('image', $imagePath)->delete();
        }

        foreach ($this->images as $image) {
            $path = $image->store('uploads/product_images/gallery', 'real_public');
            $product->galleryImages()->create(['image' => $path]);
        }

        // Clear removed images array
        $this->removedImages = [];
        $this->alertMessage = '';
        
    }

    public function removeImagesAll()
    {
        $this->imagePreviews = [];
        $this->images = [];
        $this->removedImages = [];
    }

    private function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    private function resetAlertMessage()
    {
        $this->alertMessage = '';
    }

    public function render()
    {
        return view('livewire.product.edit-gellary-images');
    }
}
