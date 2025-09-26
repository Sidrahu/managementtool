<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $showDropdown = false;

    protected $listeners = ['notificationReceived' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();

        $this->notifications = $user->notifications()->latest()->take(10)->get();
        $this->unreadCount = $user->unreadNotifications()->count();
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();

        $notification = $user->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }

        $this->loadNotifications();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
