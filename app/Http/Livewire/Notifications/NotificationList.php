<?php

namespace App\Http\Livewire\Notifications;

use Livewire\Component;
use App\Models\Notification as AppNotification;
use Carbon\Carbon;

class NotificationList extends Component
{
    public $groupedNotifications = [];

    protected $listeners = [
        'notificationUpdated' => 'refreshNotifications',
    ];

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $notifications = AppNotification::latest()->take(15)->get();

        $this->groupedNotifications = [
            'Today' => $notifications->filter(fn($n) => $n->created_at->isToday()),
            'Yesterday' => $notifications->filter(fn($n) => $n->created_at->isYesterday()),
            'Last Week' => $notifications->filter(fn($n) =>
                $n->created_at->between(Carbon::now()->subWeek(), Carbon::now()->subDays(2))
            ),
            'Last Month' => $notifications->filter(fn($n) =>
                $n->created_at->between(Carbon::now()->subMonth(), Carbon::now()->subWeek())
            ),
            'Older' => $notifications->filter(fn($n) =>
                $n->created_at->lt(Carbon::now()->subMonth())
            ),
        ];
    }

    public function render()
    {
        return view('livewire.notifications.notification-list');
    }
}
