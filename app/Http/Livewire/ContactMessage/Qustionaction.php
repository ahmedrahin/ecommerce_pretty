<?php

namespace App\Http\Livewire\ContactMessage;

use Livewire\Component;
use App\Models\Question;

class Qustionaction extends Component
{
    public $message;

     public function mount($id){
        $this->message = Question::find($id);

        if ($this->message) {
            $this->message->update(['is_read' => 1]);
        } else {
            
        }
    }



    public function render()
    {
        return view('livewire.contact-message.qustionaction');
    }
}
