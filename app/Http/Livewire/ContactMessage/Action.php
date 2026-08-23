<?php

namespace App\Http\Livewire\ContactMessage;

use Livewire\Component;
use App\Models\ContactMessage;

class Action extends Component
{

    public $message;

    public function mount($id){
        $this->message = ContactMessage::find($id);

        if ($this->message) {
            $this->message->update(['is_read' => 1]); 
        } else {
            throw new \Exception('Order not found.');
        }
    }

    public function render()
    {
        return view('livewire.contact-message.action');
    }
}
