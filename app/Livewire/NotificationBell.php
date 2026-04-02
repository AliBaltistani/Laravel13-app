<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

/**
 * NotificationBell — Phase 10-D
 * Shows unread notification count and dropdown list in account sidebar.
 */
class NotificationBell extends Component
{
    public function markAsRead(string $notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifications = Auth::check()
            ? Auth::user()->notifications()->latest()->take(10)->get()
            : collect();

        $unreadCount = Auth::check()
            ? Auth::user()->unreadNotifications()->count()
            : 0;

        return view('livewire.notification-bell', compact('notifications', 'unreadCount'));
    }
}
