<?php

namespace App\Http\Livewire\Notifications;

use Livewire\Component;
use App\Models\Order;
use App\Models\ContactMessage;
use App\Models\Question;
use App\Models\Notification as AppNotification;

class Notification extends Component
{
    public $orders;
    public $messages;
    public $question;

    public $hasUnread = false;

    public function markAllAsRead()
    {
        AppNotification::where('read', false)->update(['read' => true]);
        $this->checkUnread();
        $this->emit('notificationUpdated');
    }

    public function checkUnread()
    {
        $this->hasUnread = AppNotification::where('read', false)->exists();
    }

    public function render()
    {
        $this->orders = Order::where('is_seen', 0)->count();
        $this->messages = ContactMessage::where('is_read', 0)->count();
        $this->question = Question::where('is_read', 0)->count();

        $unreadCount = AppNotification::where('read', false)->count();
        $this->emit('notificationCountUpdated', $unreadCount);

        $this->hasUnread = AppNotification::where('read', false)->exists();
        return view('livewire.notifications.notification');
    }
}
