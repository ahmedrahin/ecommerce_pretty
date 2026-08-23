<?php

namespace App\Http\Livewire\Notifications;

use Livewire\Component;
use App\Models\Notification as AppNotification;
use Carbon\Carbon;

class AllNotification extends Component
{
    public $groupedNotifications = [];
    public $unread ;

    protected $listeners = [
        'notificationUpdated' => 'refreshNotifications',
    ];

    public function mount()
    {
        $this->refreshNotifications();
        $this->unread = AppNotification::where('read', false)->update(['read' => true]);

        $idsToKeep = AppNotification::latest('id')->take(100)->pluck('id');
        AppNotification::whereNotIn('id', $idsToKeep)->delete();
    }

    public function refreshNotifications()
    {
        $notifications = AppNotification::latest()->take(100)->get();

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
        return view('livewire.notifications.all-notification');
    }
}
