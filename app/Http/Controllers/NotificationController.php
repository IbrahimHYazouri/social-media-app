<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

final class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return Inertia::render('Notifications/Index', [
            'notifications' => NotificationResource::collection($notifications),
        ]);
    }

    public function markRead($id)
    {
        $notification = Auth::user()->unreadNotifications()->findOrFail($id);
        $notification->markAsRead();

        return back();
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }
}
