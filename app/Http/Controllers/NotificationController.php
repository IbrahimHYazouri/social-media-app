<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function read($id)
    {
        $notification = Auth::user()->unreadNotifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->noContent();
    }
}
