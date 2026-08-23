<?php

namespace App\Http\Livewire\Variant;

use App\Models\Attribute;
use Livewire\Component;
use Livewire\WithPagination;

class ListVariant extends Component
{
    use WithPagination;

    // Protected property to store attributes
    protected $paginationCount = 5;
    protected $attributes;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshList' => 'loadAttributes'];

    public function loadAttributes()
    {
        // Set attributes to the latest paginated results
        $this->attributes = Attribute::oldest()->paginate($this->paginationCount);
    }

    // Update status method
    public function updateStatus($id, $status)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->update(['status' => $status]);

        $message = $status == 0 ? "{$attribute->attr_name} is inactive" : "{$attribute->attr_name} is active";
        $type = $status == 0 ? 'info' : 'success';

        // Emit success message
        $this->emit($type, $message);

        // Reload the attributes
        $this->loadAttributes();
    }



    // Render method to load and pass paginated attributes to the view
    public function render()
    {
        return view('livewire.variant.list-variant', [
            'attributes' => Attribute::oldest()->paginate($this->paginationCount),
             'totalAttributes' => Attribute::count(),
             'paginationCount' => $this->paginationCount,
        ]);
    }
}
