<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        $userId = Auth::id();
        Notification::where(function ($q) use ($userId) {
            $q->whereNull('user_id');
            if ($userId) {
                $q->orWhere('user_id', $userId);
            }
        })->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
