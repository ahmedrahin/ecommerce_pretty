<?php

namespace App\Http\Livewire\Frontend\User;

use Livewire\Component;
use App\Models\ContactMessage as ContactMessageModel;
use Illuminate\Support\Facades\Auth;

class ContactMessage extends Component
{
    public $name, $email, $phone, $subject, $message;

     public function mount()
    {
        if( Auth::check() ){
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone;
        }
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|numeric',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string',
    ];

    public function submit()
    {
        $validated = $this->validate();

        $message = ContactMessageModel::create($validated);

         // add notification
        $notification = new \App\Models\Notification();
        $notification->create([
            'type' => 'message',
            'contact_message_id' => $message->id,
        ]);

        $this->emit('success', 'Message sent successfully!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.frontend.user.contact-message');
    }
}
