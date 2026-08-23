<?php

namespace App\Http\Livewire\AdminDetails;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class Productadd extends Component
{   
    private $cacheKey;
    
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $user_id;

    protected $listeners = [
        'delete_product' => 'delete',
    ];

    public function __construct()
    {
        $this->cacheKey = config('dbcachekey.product');
    }

    public function mount($user_id){
        $this->user_id = $user_id;
    }

     // Delete a product
     public function delete($id)
     {
         // Find the product by ID
         $product = Product::findOrFail($id);
 
         // Delete the product
         $product->delete();
 
         // Emit success message and refresh the cache
         $this->emit('info', __('Product was deleted.'));
         $this->refreshCache();
     }

     // Refresh the cache
    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Product::orderBy('id', 'desc')->get();
        });
    }

    public function render()
    {   
        $products = Product::where('user_id', $this->user_id)->latest()->paginate(10);
        return view('livewire.admin-details.productadd', [
            'products' => $products,
            'total'  => $products->total()
        ]);
    }
}
