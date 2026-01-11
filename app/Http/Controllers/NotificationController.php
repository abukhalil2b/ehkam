<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
        return back();
    }

    public function readAndRedirect($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        // Get the target URL from 'action_url' or 'link'
        $url = $notification->data['action_url'] ?? $notification->data['link'] ?? url('/');

        // Delete the notification as per user request to "delete notification"
        $notification->delete();

        return redirect($url);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }

    public function deleteAll()
    {
        auth()->user()->notifications()->delete();
        return back();
    }
}
