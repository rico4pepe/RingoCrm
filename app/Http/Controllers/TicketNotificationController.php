<?php

namespace App\Http\Controllers;

use App\Models\TicketNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketNotificationController extends Controller
{
    // Get all unread notifications for the current user
    public function getUnreadNotifications()
    {
        return response()->json([
            'success' => true,
            'notifications' => TicketNotification::forCurrentUser()
                ->unread()
                ->with('ticket')
                ->latest()
                ->get()
        ]);
    }

    // Mark a specific notification as read
    public function markAsRead($notificationId)
    {
        $notification = TicketNotification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        TicketNotification::forCurrentUser()
            ->unread()
            ->chunkById(100, function ($notifications) {
                foreach ($notifications as $notification) {
                    $notification->update(['is_read' => true]);
                }
            });

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    // Get notification count
    public function getNotificationCount()
    {
        return response()->json([
            'success' => true,
            'count' => TicketNotification::forCurrentUser()->unread()->count() ?? 0
        ]);
    }
}
