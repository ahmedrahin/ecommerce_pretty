<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GellaryImages extends Component
{
    use WithFileUploads;

    public $images = [];
    public $imagePreviews = [];
    public $imageNames = [];
    public $imageSizes = [];
    public $removedImages = [];
    public $alertMessage = '';

    protected $listeners = ['save'];

    public function updatedImages()
    {
        $this->resetAlertMessage();

        // Validate images
        $validator = Validator::make(
            ['images' => $this->images],
            // ['images.*' => 'image|mimes:jpeg,jpg,png|max:1024'], 
            [
                'images.*.image' => 'Only image files are allowed.',
                'images.*.mimes' => 'Only .jpeg, .jpg, and .png formats are allowed.',
                // 'images.*.max' => 'Each file must be less than 1MB.'
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

        $this->imagePreviews = [];
        $this->imageNames = [];
        $this->imageSizes = [];
        $this->alertMessage = '';

        foreach ($this->images as $image) {
            $this->imagePreviews[] = $image->temporaryUrl();
            $this->imageNames[] = $image->getClientOriginalName();
            $this->imageSizes[] = $this->formatSizeUnits($image->getSize());
        }
    }

    public function removeImage($index)
    {
        unset($this->imagePreviews[$index]);
        unset($this->imageNames[$index]);
        unset($this->imageSizes[$index]);

        $this->imagePreviews = array_values($this->imagePreviews);
        $this->imageNames = array_values($this->imageNames);
        $this->imageSizes = array_values($this->imageSizes);

        unset($this->images[$index]);
        $this->images = array_values($this->images);
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

    public function save($productId)
    {
        $product = Product::find($productId);

        // Handle gallery images
        foreach ($this->images as $image) {
            $path = $image->store('uploads/product_images/gallery', 'real_public');
            $product->galleryImages()->create(['image' => $path]);
        }


        // Clear removed images array
        $this->removedImages = [];
        $this->alertMessage = '';
        
    }

    public function removeImagesAll(){
        $this->imagePreviews = [];
        $this->images = [];
        $this->removedImages = [];
    }


    public function render()
    {
        return view('livewire.product.gellary-images');
    }
}
