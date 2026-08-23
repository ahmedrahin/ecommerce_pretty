<?php

namespace App\Http\Livewire\Frontend\Shop;

use Livewire\Component;
use App\Models\Category;

class CategoryMenuMobile extends Component
{
    public function render()
    {
        $categories = Category::where('status', 1)
            ->with(['subcategories' => function ($query) {
                $query->orderBy('id', 'asc')
                    ->where('status', 1)
                    ->withCount('products');
            }, 'product'])
            ->orderBy('id', 'asc')
            ->get();
            
        return view('livewire.frontend.shop.category-menu-mobile', compact('categories'));
    }
}
