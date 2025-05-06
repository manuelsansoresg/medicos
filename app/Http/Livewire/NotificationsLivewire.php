<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notification;

class NotificationsLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $limit;

    public function mount($limit = 5)
    {
        $this->limit = $limit;
    }

    public function render()
    {
        $notifications = Notification::getAllNotifications($this->limit);

        return view('livewire.notifications-livewire', [
            'notifications' => $notifications
        ]);
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->update(['leido' => 1]);
        }
    }
} 