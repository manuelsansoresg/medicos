<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notification;

class AllNotificationsLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $filter = 'all'; // all, read, unread

    public function render()
    {
        $query = Notification::query();

        if ($this->filter === 'read') {
            $query->where('leido', 1);
        } elseif ($this->filter === 'unread') {
            $query->where('leido', 0);
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.all-notifications-livewire', [
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

    public function markAllAsRead()
    {
        Notification::where('leido', 0)->update(['leido' => 1]);
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }
} 